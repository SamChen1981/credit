<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

APP::import('Controller','InterfaceHeader');
App::uses('String', 'Utility');
App::import('Vendor','RequestBase');
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class InterfaceController extends InterfaceHeaderController {

    /**
    * 接口控制器
    *
    */
    public $uses = array('Product','ProductLog','ExchangeLog','ProductImg');

    public $isInterfacePage = false;
    //会员模块地址
    public $member_url = MEMBER_URL;
    public $tenentid='';
    //图片地址
    public $pic_url = '';
    //public $components = array( 'Upload' );

    //const upladAddress = 'interfaces/upload';

    //初始化
    public function beforeFilter(){
        parent::beforeFilter();
        $this->tenentid=$this->Session->read("tenantid");
        $url="http://".trim($this->tenentid).'.'.str_ireplace("http://","",Router::fullbaseUrl());
		if(defined("SERVER_PORT")){
			$this->pic_url = $url.':'.SERVER_PORT;
		}else{
			$this->pic_url = $url.':80';
		}
    }
    //获取商品列表
    public function goodsList(){ //GET
        $time = time();
        $condition = array(
            'conditions' => array(
                'status' => 1,
                '(quantity-exchange_quantity) >' => 0,
                ),
            'fields' => array('id','activity_name as title','type','thumb_img as thumbnail','credits1 as redites','market_price as price'),
            'contain'=>array(),
            'order' => 'create_time DESC',
            );
        $data = array();
       $count = $this->Product->find('count',$condition);
        //调用组装分页条件
        $page = @$this->app_page($condition,$count,$this->request->query['pageSize'],$this->request->query['page'],'create_time DESC');
        $data = $this->Product->find('all',$page['condition']);
        $list = Hash::extract($data, '{n}.Product');
        foreach ($list as $key => $value) {
            $list[$key]['thumbnail'] = $this->pic_url.$value['thumbnail'];
        }
        echo json_encode(array('code'=>'0000','description'=>'获取成功','data'=>array('page'=>$page['page'],'ismore'=>$page['ismore'],'list'=>$list)));  
    }
    //获取商品详细信息
    public function goodsDetail(){ //GET
        $gid = $this->request->params['gid'];
        if(!is_numeric($gid)){ 
            echo json_encode(array('code'=>'0001','description'=>'商品不存在'));
            exit;
        }
        $time = time();
        $condition = array(
            'conditions' => array(
                'status' => 1,
                '(quantity-exchange_quantity) >' => 0,
                'id' => $gid,
                ),
             'fields' => array('id','exchange_quantity','quantity','type','activity_name as title','banner_img as thumbnail','credits1 as redites',
                'product_name as description','accept_addr as instruction' ,'accept_way  as way','accept_addr as addr',
                'link_man  as  link','link_phone as phone ','accept_time as days','accept_time_desc as time','exchange_times as num',
                "market_price as price",'start_time as start','end_time as end'),
            'contain'=>array()
            );
         
        $data = $this->Product->find('first',$condition);
        if(empty($data)){ 
            echo json_encode(array('code'=>'0002','description'=>'商品不存在或没有库存'));
            exit;
        }

        //判断活动是否在进行
        if($data['Product']['start'] >time()){
            $data['Product']['activityState']=1;//未开始
        }elseif ($data['Product']['start'] <=time() && $data['Product']['end'] >=time()) {

            $data['Product']['activityState']=2;//进行中
        }else{
           $data['Product']['activityState']=3;//已结束 
        }
        $data['Product']['start']=date('Y-m-d H:i:s',$data['Product']['start']);
        $data['Product']['end']=date('Y-m-d H:i:s',$data['Product']['end']);
        $imgs = $this->ProductImg->find('first',array('conditions'=>array('id'=>$gid)));

        $data['Product']['thumbnail'] = null;//$this->pic_url.$data['Product']['thumbnail'];
        $data['Product']['stock'] = $data['Product']['quantity']-$data['Product']['exchange_quantity'];
        $pics = unserialize($imgs['ProductImg']['content']);
        if(is_array($pics)){ 
            $pic = array();
            foreach ($pics as $key => $value) {
                if(!$value){ continue;}
                $pic[] = $this->pic_url.$value;
            }
        }
        $data['Product']['imgs'] = $pic;
        //print_r($data['Product']);
        //exit;
        echo json_encode(array('code'=>'0000','description'=>'获取成功','data'=>$data['Product']));
    }
    //获取用户兑换记录列表
    public function exchangeList(){ //GET
        $uid = $this->request->params['uid'];
        if(!is_numeric($uid)){ 
            echo json_encode(array('code'=>'0001','description'=>'用户不存在'));
            exit;
        }
        $condition = array(
            'conditions' => array(
                'ExchangeLog.userid' => $uid,
                'ExchangeLog.exchange_type' => 1,
                ),
            'order' => 'ExchangeLog.create_time DESC',
            'fields' => array('ExchangeLog.id as id','Product.activity_name as title','Product.thumb_img as thumbnail','Product.credits1 as redites','ExchangeLog.status as status','ExchangeLog.modify_time as datetime'),
            );

        $count = $this->ExchangeLog->find('count',$condition);
        //调用组装分页条件
        $page = @$this->app_page($condition,$count,$this->request->params['pageSize'],$this->request->params['page']);
        $data = $this->ExchangeLog->find('all',$page['condition']);
        $list = array();
        foreach ($data as $key => $value) {//去除模型名称
            $value['Product']['thumbnail'] = $this->pic_url.$value['Product']['thumbnail'];
            $list[] = array_merge($value['ExchangeLog'],$value['Product']);
        }
        echo json_encode(array('code'=>'0000','description'=>'获取成功','data'=>array('page'=>$page['page'],'ismore'=>$page['ismore'],'list'=>$list)));

    }
    //获取奖品详细信息
    public function exchangeDetail(){ //GET
        $rid = $this->request->params['rid'];
        if(!is_numeric($rid)){
            echo json_encode(array('code'=>'0001','description'=>'商品不存在'));
            exit;
        }
        $condition = array(
            'conditions' => array(
                'ExchangeLog.id' => $rid,
                ),
            'fields' => array('ExchangeLog.id as id','ExchangeLog.product_id as productid','Product.activity_name as title','Product.thumb_img as thumbnail','Product.credits1 as redites','ExchangeLog.status as status','ExchangeLog.create_time as datetime','ExchangeLog.code as code','Product.validity_date','Product.accept_addr as address','ExchangeLog.credits as payment','ExchangeLog.modify_time as paydate'),
            );
        $data = $this->ExchangeLog->find('first',$condition);
        $data['Product']['thumbnail'] = $this->pic_url.$data['Product']['thumbnail'];
        $data = array_merge($data['ExchangeLog'],$data['Product']);
        $data['paydate'] = date('Y-m-d',$data['paydate']);
        $data['expdate'] = date('Y-m-d',$data['datetime']+$data['validity_date']);
        echo json_encode(array('code'=>'0000','description'=>'获取成功','data'=>$data));
    }
    //兑换商品
    public function exchange(){ //POST
        $data = $this->request->data;
        $gid = $this->request->params['gid'];
        $condition = array(
            'conditions' => array(
                'id'=>$gid
            ),
            'contain'=>array()
        );
        $Product=$this->Product->find('first',$condition)['Product'];
        $Product_exchange_times=$Product['exchange_times'];
        $condition = array(
            'conditions' => array(
                'product_id'=>$gid,
                'userid'=>$data['user_id']
            ),
            'contain'=>array()
        );
        $Exchange_counts=$this->ExchangeLog->find('count',$condition);
        if($Product_exchange_times<= $Exchange_counts){
            echo json_encode(array('code'=>'0001','description'=>'不能大于用户最大兑换数'));
            exit;
        }
        if(!is_numeric($gid)){ 
            echo json_encode(array('code'=>'0001','description'=>'商品不存在'));
            exit;
        }
        //获取商品信息
        $time = time();
        $condition = array(
            'conditions' => array(
                'status' => 1,
                'start_time <' => $time,
                'end_time >' => $time,
                '(quantity-exchange_quantity) >' => 0,
                'id' => $gid,
                ),
            'contain'=>array()
            );
        $product = $this->Product->find('first',$condition);
        if(empty($product)){ 
            echo json_encode(array('code'=>'0001','description'=>'商品下架或者没有库存'));
            exit;
        }
        //请求用户模块生成订单
        $pdata = array(
            'goodsID' => $gid,
            'goodsInfo' => array( 
                'name' => $product['Product']['activity_name'],
                'imgUrl' => $this->pic_url.$product['Product']['thumb_img'],
                'credits' => $product['Product']['credits1'],
                'introduction' => $product['Product']['product_name'],
                //互动添加返回字段
                //'validity' => $product['Product']['validity_date']/86400,//领取有效期,为0表示不限制领取时间
                'addr'=>$product['Product']['accept_addr'],//自领地址
                'link'=>$product['Product']['link_man'],//联系人
                'phone'=>$product['Product']['link_phone'],//联系人电话
                //'days'=>$product['Product']['accept_time'],//领取大概时间
                //'time'=>$product['Product']['accept_time_desc'],//领取具体时间
                'order_end_time'=>$product['Product']['order_end_time'],//订单自取结束时间
                'order_end'=>$product['Product']['order_end_time']==0?'0':date('Y/m/d',$product['Product']['order_end_time']),
                'order_strat'=>$product['Product']['order_start_time']==0?'0':date('Y/m/d',$product['Product']['order_start_time']),
                'order_start_time'=>$product['Product']['order_start_time'],//订单自取开始时间
                ),
            'goodsType' => $product['Product']['type'],
            'virtualGoodsCode' => '',
            'sum' => $data['num']?$data['num']:1,
            'price' => $product['Product']['credits1'],
            'addresseeInfo' => array(
                'name' => $data['name'],
                'phoneNum' => $data['phone_num'],
                'address' => $data['address'],
                'zipCode' => $data['zip_code'],
            ),
            'shippingMethod'=>$product['Product']['accept_way']==1?2:1,//商品兑换领取方式。
        );
        $param = array(
            'uid' => $data['user_id'],
            'token' => $data['token'],
            'data' => json_encode($pdata),
            'tenantid'=>$this->tenentid,
            );
        $result = $this->curl_sgin($this->member_url.'orders/createOrders/1',$param,'post');
        if($result['code']!='0000'){ 
            echo json_encode(array('code'=>$result['code'],'description'=>$result['description']));
        }else{
            $pcdata1['Product'] = array(
                'exchange_quantity' => $product['Product']['exchange_quantity']+1,
                );
            $this->Product->id = $gid;
            $this->Product->save($pcdata1);
            $this->saveExchange_logs($data);
            echo json_encode(array('code'=>$result['code'],'description'=>$result['description'],'ordersID'=>$result['data']['ordersID'],'ordersNo'=>$result['data']['ordersNo']));
        }
    }
    //获取兑换码
    private function rand_code($uid=0){
        $code = time().$uid.mt_rand(0,9999999);
        return md5($code);
    }
    /*以下为其他模块调用接口*/
    //获取某用户对某摇一摇活动参与次数
    public function getShakeTimes(){ //GET
        $param = $this->data;
        $time = time()-$param['interval_time'];
        $count = $this->ExchangeLog->find('count',array('conditions'=>array('ExchangeLog.activity_id'=>$param['aid'],'userid'=>$param['uid'],'exchange_type'=>2,'ExchangeLog.create_time >'=>$time)));
        $limit = $this->ExchangeLog->find('first',array('fields'=>array('id','create_time'),'order'=>array('create_time'),'limit'=>1,'conditions'=>array('ExchangeLog.activity_id'=>$param['aid'],'userid'=>$param['uid'],'exchange_type'=>2,'ExchangeLog.create_time >'=>$time)));
        echo json_encode(array('count'=>$count,'limit'=>$limit));
    }
    //保存摇一摇 摇奖记录
    public function saveShakeData(){ //GET
        $param = $this->data;
        $data = array(
            'userid' => $param['user_id'],
            'user_name' => $param['user_name'],
            'mobile_phone' => $param['user_mobile_phone'],
            'activity_id' => $param['activity_id'],
            'activity_name' => $param['activity_name'],
            'credits' => $param['credits'],
            'code' => $param['code'],
            'status' => 1,
            'prize_id' => $param['prize_id'],
            'hit_status' => $param['hit_status'],
            'exchange_type' => 2,
            'create_time' => $param['create_time'],
            'modify_time' => $param['modify_time'],
        );
        $re = $this->ExchangeLog->save();
        //$re = Hash::extract($re, '{n}.ExchangeLog');
        echo json_encode($re);
    }

    //保存摇一摇 摇奖记录
    public function saveExchange_logs($data){ //GET
        $param = $data;
        $gid=$this->request->params['gid'];
        $data = array(
            'userid' => $param['user_id'],
            'user_name' => $param['name'],
            'product_id'=>$gid,
        );
        $re = $this->ExchangeLog->save($data);
        //$re = Hash::extract($re, '{n}.ExchangeLog');
        return $re;
    }

    //绑定摇一摇用户和中奖记录
    public function bingdingShake(){ //GET
        $param = $this->data;
        $time = time();
        $log = $this->ExchangeLog->find('first',array('conditions'=>array('code'=>$param['exchange'],'exchange_type'=>2)));
        if(empty($log)){ 
            echo json_encode(array('code'=>'0003','description'=>'该中奖信息已经被认领或未中奖'));
        }else{ 
            if(((int)$log['ExchangeLog']['create_time']+30*60)<=$time){ 
                echo json_encode(array('code'=>'0004','description'=>'该中奖信息已过期'));
                exit;
            }
            $data = array('userid'=>$param['user_id'],'user_name'=>$param['user_name'],'mobile_phone'=>$param['user_mobile_phone']);
            $this->ExchangeLog->id = $log['ExchangeLog']['id'];
            if($re = $this->ExchangeLog->save($data)){
                //给用户奖励对应积分(中奖后积分奖励)
                if($data['type']==1){
                    $limit_data = array('type'=>1,'limit'=>$log['ExchangeLog']['credits'],'consumption'=>'credits1','logtype'=>8, 'tenantid'=>$this->tenentid,);
                    $result = $this->curl_sgin($this->member_url.'/user/addCredit/'.$param['user_id'],$limit_data,'post');

                    if($result['code']!='0000'){ 
                        echo json_encode(array('code'=>'0004','description'=>'加积分失败'));
                        exit;
                    }
                }
                echo json_encode(array('code'=>'0000','description'=>'绑定成功'));
            }else{ 
                echo json_encode(array('code'=>'0001','description'=>'绑定失败'));
            }
        }
    }
    //获取用户摇一摇中奖列表
    public function shakeLogList(){
        $param = $this->data;

        $condition = array(
            'conditions'=>array('userid'=>$param['uid'],'exchange_type'=>$param['type'],'hit_status'=>2)
            );
        $count = $this->ExchangeLog->find('count',$condition);
        //调用组装分页条件
        $page = @$this->app_page($condition,$count,$param['pageSize'],$param['page'],array('ExchangeLog.create_time DESC'));
        $data = $this->ExchangeLog->find('all',$page['condition']);
        if($param['type']!=3){ 
            $data = Hash::extract($data, '{n}.ExchangeLog');
        }
        echo json_encode(array('code'=>'0000','description'=>'获取成功','data'=>array('page'=>$page['page'],'ismore'=>$page['ismore'],'list'=>$data)));
    }
    //获取用户摇一摇中奖详情
    public function shakePrize(){ 
        $param = $this->data;
        $log = $this->ExchangeLog->find('first',array('conditions'=>array('userid'=>$param['uid'],'ExchangeLog.id'=>$param['logid'])));
        echo json_encode($log);
    }
    //接收第三方系统中奖信息(互动游戏)
    public function addPrize(){ 
        $param = $this->data;

        $uid = $this->request->params['uid'];
        $time = time();
        //获取用户信息(走接口)
        $user = $this->curl_sgin($this->member_url.'user/detail?uid='.$uid);
        if($user['code']=='0000'){
            $user = $user['data'];
        }else{ 
            echo json_encode(array('code'=>'0003','description'=>'获取用户信息失败'));
            exit;
        }
        if($param['type']==1){ 
            //添加积分
            $limit_data = array('type'=>1,'limit'=>$param['credit'],'consumption'=>'credits1','logtype'=>10, 'tenantid'=>$this->tenentid,);
            $result = $this->curl_sgin($this->member_url.'/user/addCredit/'.$uid,$limit_data,'post');
            $code = '';
            if($result['code']!='0000'){
                echo json_encode(array('code'=>'0001','description'=>'积分不够或报错'));
                exit;
            }
        }else{ 
            $code = $this->rand_code($uid);
        }
        $data = array(
            'userid' => $uid,
            'user_name' => $user['username'],
            'mobile_phone' => $user['mobile'],
            'activity_id' => $param['objectid'],
            'activity_name' => $param['title'],
            'credits' => $param['credit'],
            'code' => $code,
            'status' => 1,
            'prize_id' => $param['prizeid'],
            'hit_status' => 2,
            'exchange_type' => 3,
            'create_time' => $time,
            'modify_time' => $time,
            );
        $re = $this->ExchangeLog->save($data);
        //$re = Hash::extract($re, '{n}.ExchangeLog');
        if($re){ 
            echo json_encode(array('code'=>'0000','description'=>'接收成功'));
            exit;
        }else{ 
            echo json_encode(array('code'=>'0001','description'=>'接收失败'));
            exit;
        }
    }
    //获取积分商城首页置顶banner
    public function topBanners(){
        $param = $this->data;
        $param['limit'] = isset($param['limit'])?$param['limit']:5;
        $condition = array(
            'conditions' => array(
                'status' => 1,
                'end_time >' => time(),
                'sort'=>1,
                'banner_img !='=>"",
                '(quantity-exchange_quantity) >' => 0,
                //'banner_img !='=>null,
                ),
            'fields' => array('id','product_name as title','banner_img as banner'),
            'order' => array('sort DESC','create_time DESC'),
            'contain' => array(),
            'limit' => $param['limit'],
            );

        $data = $this->Product->find('all',$condition);
        if(empty($data)){ 
            $data[] = array(
                'id' => 0,
                'title' => '默认图片',
                'banner' =>  $this->pic_url.'/images/default_banner.png',
                );
        }else{
            $data = Hash::extract($data, '{n}.Product');
            foreach ($data as $key => $value) {
                    $data[$key]['banner'] = $this->pic_url.$value['banner'];
            }
        }
        //print_r($data);
        //exit;
        echo json_encode(array('code'=>'0000','description'=>'获取成功','data'=>$data));

    }
    //调取接口方法
    private function curl_sgin($url,$param=array(),$method='get'){
        $curl = new RequestBase();
        $param = array_merge(array('appKey'=>$this->appKey,'timestamp'=>time()),$param);
        ksort($param);
        $str = '';
        foreach ($param as $key => $value) {
            $str.=$key.$value;
        }
        $param['sign'] = md5($str.$this->appSecret);
        $re = $curl->exec($url,$param,$method);
        return json_decode($re,true);
    }
}
