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
class InterfaceController extends InterfaceHeaderController
{

    /**
     * 接口控制器
     *
     */
    public $uses = array('Product', 'ProductLog', 'ExchangeLog', 'ProductImg');

    public $isInterfacePage = false;
    
    //会员模块地址
    public $member_url = MEMBER_URL;
    public $tenentid = '';
    //图片地址
//    public $pic_url = '';
    public $pic_url=PIC_URL;
    public $components=array("Session");
    //public $components = array( 'Upload' );

    //const upladAddress = 'interfaces/upload';

    //初始化
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->tenentid = $this->Session->read("tenantid");
        $url = "http://" . trim($this->tenentid) . '.' . str_ireplace("http://", "", Router::fullbaseUrl());
//        if (defined("SERVER_PORT")) {
//            $this->pic_url = $url . ':' . SERVER_PORT;
//        } else {
//            $this->pic_url = $url . ':80';
//        }
    }

    //获取商品列表
    public function goodsList()
    { //GET
        $time = time();
        $condition = array(
            'conditions' => array(
                'status' => 1,
                '(quantity-exchange_quantity) >' => 0,
            ),
            'fields' => array('id', 'activity_name as title', 'type', 'thumb_img as thumbnail', 'credits1 as redites', 'market_price as price'),
            'contain' => array(),
            'order' => 'create_time DESC',
        );
        $data = array();
        if (empty($this->request->query['pageSize'])) {
            $list = $this->Product->find('all', $condition);
            return json_encode(array('code' => '0000', 'description' => '获取成功', 'data' => array('list' => $list)));
        }
        $count = $this->Product->find('count', $condition);
        //调用组装分页条件
        $page = @$this->app_page($condition, $count, $this->request->query['pageSize'], $this->request->query['page'], 'rank DESC,create_time DESC');
        $data = $this->Product->find('all', $page['condition']);
        $list = Hash::extract($data, '{n}.Product');
        foreach ($list as $key => $value) {
            $list[$key]['thumbnail'] = $this->pic_url . $value['thumbnail'];
        }
        return json_encode(array('code' => '0000', 'description' => '获取成功', 'data' => array('page' => $page['page'], 'ismore' => $page['ismore'], 'list' => $list)));
    }

    //获取商品详细信息
    public function goodsDetail($gid = null)
    { //GET
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            $gid = $this->request->params['gid'];
        } else {
            if (empty($gid)) {
                echo "<script>alert('商品号不能为空');window.location.go(-1);</script>";//商品id
                exit;
            }
        }
        if (!is_numeric($gid)) {
            return json_encode(array('code' => '0001', 'description' => '商品不存在'));
            exit;
        }
        $time = time();
        $condition = array(
            'conditions' => array(
                'status' => 1,
                '(quantity-exchange_quantity) >' => 0,
                'id' => $gid,
            ),
            'fields' => array('id', 'exchange_quantity', 'quantity', 'type', 'activity_name as title', 'banner_img as thumbnail', 'credits1 as redites',
                'product_name as description', 'accept_addr as instruction', 'accept_way  as way', 'accept_addr as addr',
                'link_man  as  link', 'link_phone as phone ', 'accept_time as days', 'accept_time_desc as time', 'exchange_times as num',
                "market_price as price", 'start_time as start', 'end_time as end'),
            'contain' => array()
        );

        $data = $this->Product->find('first', $condition);
        if (empty($data)) {
            echo json_encode(array('code' => '0002', 'description' => '商品不存在或没有库存'));
            exit;
        }

        //判断活动是否在进行
        if ($data['Product']['start'] > time()) {
            $data['Product']['activityState'] = 1;//未开始
        } elseif ($data['Product']['start'] <= time() && $data['Product']['end'] >= time()) {

            $data['Product']['activityState'] = 2;//进行中
        } else {
            $data['Product']['activityState'] = 3;//已结束
        }
        $data['Product']['start'] = date('Y-m-d H:i:s', $data['Product']['start']);
        $data['Product']['end'] = date('Y-m-d H:i:s', $data['Product']['end']);
        $imgs = $this->ProductImg->find('first', array('conditions' => array('id' => $gid)));
        $data['Product']['thumbnail'] = null;//$this->pic_url.$data['Product']['thumbnail'];
        $data['Product']['stock'] = $data['Product']['quantity'] - $data['Product']['exchange_quantity'];
        $pics = unserialize($imgs['ProductImg']['content']);
        if (is_array($pics)) {
            $pic = array();
            foreach ($pics as $key => $value) {
                if (!$value) {
                    continue;
                }
                $pic[] = $this->pic_url . $value;
            }
        }
        $data['Product']['imgs'] = $pic;
        //print_r($data['Product']);
        //exit;
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            echo json_encode(array('code' => '0000', 'description' => '获取成功', 'data' => $data['Product']));
        } else {
            return json_encode(array('code' => '0000', 'description' => '获取成功', 'data' => $data['Product']));
        }
    }

    //获取用户兑换记录列表
    public function exchangeList()
    { //GET
        $uid = $this->request->params['uid'];
        if (!is_numeric($uid)) {
            echo json_encode(array('code' => '0001', 'description' => '用户不存在'));
            exit;
        }
        $condition = array(
            'conditions' => array(
                'ExchangeLog.userid' => $uid,
                'ExchangeLog.exchange_type' => 1,
            ),
            'order' => 'ExchangeLog.create_time DESC',
            'fields' => array('ExchangeLog.id as id', 'Product.activity_name as title', 'Product.thumb_img as thumbnail', 'Product.credits1 as redites', 'ExchangeLog.status as status', 'ExchangeLog.modify_time as datetime'),
        );

        $count = $this->ExchangeLog->find('count', $condition);
        //调用组装分页条件
        $page = @$this->app_page($condition, $count, $this->request->params['pageSize'], $this->request->params['page']);
        $data = $this->ExchangeLog->find('all', $page['condition']);
        $list = array();
        foreach ($data as $key => $value) {//去除模型名称
            $value['Product']['thumbnail'] = $this->pic_url . $value['Product']['thumbnail'];
            $list[] = array_merge($value['ExchangeLog'], $value['Product']);
        }
        echo json_encode(array('code' => '0000', 'description' => '获取成功', 'data' => array('page' => $page['page'], 'ismore' => $page['ismore'], 'list' => $list)));

    }

    //获取奖品详细信息
    public function exchangeDetail()
    { //GET
        $rid = $this->request->params['rid'];
        if (!is_numeric($rid)) {
            echo json_encode(array('code' => '0001', 'description' => '商品不存在'));
            exit;
        }
        $condition = array(
            'conditions' => array(
                'ExchangeLog.id' => $rid,
            ),
            'fields' => array('ExchangeLog.id as id', 'ExchangeLog.product_id as productid', 'Product.activity_name as title', 'Product.thumb_img as thumbnail', 'Product.credits1 as redites', 'ExchangeLog.status as status', 'ExchangeLog.create_time as datetime', 'ExchangeLog.code as code', 'Product.validity_date', 'Product.accept_addr as address', 'ExchangeLog.credits as payment', 'ExchangeLog.modify_time as paydate'),
        );
        $data = $this->ExchangeLog->find('first', $condition);
        $data['Product']['thumbnail'] = $this->pic_url . $data['Product']['thumbnail'];
        $data = array_merge($data['ExchangeLog'], $data['Product']);
        $data['paydate'] = date('Y-m-d', $data['paydate']);
        $data['expdate'] = date('Y-m-d', $data['datetime'] + $data['validity_date']);
        echo json_encode(array('code' => '0000', 'description' => '获取成功', 'data' => $data));
    }

    //兑换商品
    public function exchange()
    { //POST
        $data = $this->request->data;
        $gid = $this->request->params['gid'];
        $condition = array(
            'conditions' => array(
                'id' => $gid
            ),
            'contain' => array()
        );
        $Product = $this->Product->find('first', $condition)['Product'];
        $Product_exchange_times = $Product['exchange_times'];
        $condition = array(
            'conditions' => array(
                'product_id' => $gid,
                'userid' => $data['user_id']
            ),
            'contain' => array()
        );
        $Exchange_counts = $this->ExchangeLog->find('count', $condition);
        if ($Product_exchange_times <= $Exchange_counts && $Product_exchange_times != 0) {
            echo json_encode(array('code' => '0001', 'description' => '兑换次数已经用完'));
            exit;
        }
        if (!is_numeric($gid)) {
            echo json_encode(array('code' => '0001', 'description' => '商品不存在'));
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
            'contain' => array()
        );
        $product = $this->Product->find('first', $condition);
        if (empty($product)) {
            echo json_encode(array('code' => '0001', 'description' => '商品下架或者没有库存'));
            exit;
        }
        if($product['Product']['type']==1){
            $code=$this->rand_code($data['user_id']);
        }
        $virtualGoodsCode=isset($code)?$code:'';
        //拆分收货地址分割省市区街
        $address=explode(',',$data['address']);
        //请求用户模块生成订单
        $pdata = array(
            'goodsID' => $gid,
            'goodsInfo' => array(
                'name' => $product['Product']['activity_name'],
                'imgUrl' =>array($this->pic_url . $product['Product']['thumb_img']),
                'credits' => $product['Product']['credits1'],
                'introduction' => $product['Product']['product_name'],
                //互动添加返回字段
                //'validity' => $product['Product']['validity_date']/86400,//领取有效期,为0表示不限制领取时间
                'addr' => $product['Product']['accept_addr'],//自领地址
                'link' => $product['Product']['link_man'],//联系人
                'phone' => $product['Product']['link_phone'],//联系人电话
                'order_end_time' => $product['Product']['order_end_time'],//订单自取结束时间
                'order_end' => $product['Product']['order_end_time'] == 0 ? '0' : date('Y/m/d', $product['Product']['order_end_time']),
                'order_strat' => $product['Product']['order_start_time'] == 0 ? '0' : date('Y/m/d', $product['Product']['order_start_time']),
                'order_start_time' => $product['Product']['order_start_time'],//订单自取开始时间
            ),
            'goodsType' => $product['Product']['type'],
            'virtualGoodsCode' => $virtualGoodsCode,
            'sum' => $data['num'] ? $data['num'] : 1,
            'price' => $product['Product']['credits1'],
            'addresseeInfo' => array(
                'name' => $data['name'],
                'phoneNum' => $data['phone_num'],
                'province' =>$address[1],
                'city'=>$address[2],
                'county'=>$address[3],
                'street'=>$address[4],
                'zipCode' => $data['zip_code'],
            ),
            'shippingMethod' => $product['Product']['accept_way'] == 1 ? 2 : 1,//商品兑换领取方式。
        );
        $param = array(
            'uid' => $data['user_id'],
            'token' => $data['token'],
            'data' => json_encode($pdata),
            'tenantid' => $this->tenentid,
        );
        $datas = array();
        foreach ($param as $k => $v) {
            $datas[$k] = $v;
        }
        $authorization = $this->asort($datas);
        $rdata = $this->curl_sgin1($this->member_url . '/orders/createOrders/1', $datas, 'post', $authorization);
        $params = array(
            'uid' => $data['user_id'],
            'token' => $data['token'],
            'tenantid' => $this->tenentid,
        );
        $datas = array();
        foreach ($params as $k => $v) {
            $datas[$k] = $v;
        }
        $authorization = $this->asort($datas);
        $result = $this->curl_sgin1($this->member_url . '/user/detail', $datas, 'post', $authorization);
        if ($result['code'] != '0000') {
            echo json_encode(array('code' => $result['code'], 'description' => $result['description']));
        } else {
            $pcdata1['Product'] = array(
                'exchange_quantity' => $product['Product']['exchange_quantity'] + 1,
            );
            $this->Product->id = $gid;
            $this->Product->save($pcdata1);
            $this->saveExchange_logs($data, $result, $pdata);
            echo json_encode(array('code' => $result['code'], 'description' => $result['description'], 'ordersID' => $rdata['data']['ordersID'], 'ordersNo' => $rdata['data']['ordersNo']));
        }
    }

    //获取兑换码
    private function rand_code($uid = 0)
    {
        $code = time() . $uid . mt_rand(0, 9999999);
        return md5($code);
    }
    /*以下为其他模块调用接口*/
    //获取某用户对某摇一摇活动参与次数
    public function getShakeTimes()
    { //GET
        $param = $this->data;
        $time = time() - $param['interval_time'];
        $count = $this->ExchangeLog->find('count', array('conditions' => array('ExchangeLog.activity_id' => $param['aid'], 'userid' => $param['uid'], 'exchange_type' => 2, 'ExchangeLog.create_time >' => $time)));
        $limit = $this->ExchangeLog->find('first', array('fields' => array('id', 'create_time'), 'order' => array('create_time'), 'limit' => 1, 'conditions' => array('ExchangeLog.activity_id' => $param['aid'], 'userid' => $param['uid'], 'exchange_type' => 2, 'ExchangeLog.create_time >' => $time)));
        echo json_encode(array('count' => $count, 'limit' => $limit));
    }

    //保存摇一摇 摇奖记录
    public function saveShakeData()
    { //GET
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
    public function saveExchange_logs($data, $user, $pdata)
    { //GET
        $param = $data;
        $gid = $this->request->params['gid'];
        $data = array(
            'userid' => $param['user_id'],
            'user_name' => $user['data']['nickname'],
            'mobile_phone' => $user['data']['mobile'],
            'product_id' => $gid,
            'product_name' => $pdata['goodsInfo']['name'],
            'code'=>$pdata['virtualGoodsCode']?$pdata['virtualGoodsCode']:null,
            'create_time'=>time()
        );
        $re = $this->ExchangeLog->save($data);
        $condition = array(
            'conditions' => array(
                'status' => 1,
                'id' => $gid,
            ),
            'contain' => array()
        );
        $product=$this->Product->find('first',$condition);
        $member_nums=$product['Product']['member_nums']+1;
        $data=array('member_nums'=>$member_nums,'id' => $gid);
        $this->Product->save($data);
        //$re = Hash::extract($re, '{n}.ExchangeLog');
        return $re;
    }

    //绑定摇一摇用户和中奖记录
    public function bingdingShake()
    { //GET
        $param = $this->data;
        $time = time();
        $log = $this->ExchangeLog->find('first', array('conditions' => array('code' => $param['exchange'], 'exchange_type' => 2)));
        if (empty($log)) {
            echo json_encode(array('code' => '0003', 'description' => '该中奖信息已经被认领或未中奖'));
        } else {
            if (((int)$log['ExchangeLog']['create_time'] + 30 * 60) <= $time) {
                echo json_encode(array('code' => '0004', 'description' => '该中奖信息已过期'));
                exit;
            }
            $data = array('userid' => $param['user_id'], 'user_name' => $param['user_name'], 'mobile_phone' => $param['user_mobile_phone']);
            $this->ExchangeLog->id = $log['ExchangeLog']['id'];
            if ($re = $this->ExchangeLog->save($data)) {
                //给用户奖励对应积分(中奖后积分奖励)
                if ($data['type'] == 1) {
                    $limit_data = array('type' => 1, 'limit' => $log['ExchangeLog']['credits'], 'consumption' => 'credits1', 'logtype' => 8, 'tenantid' => $this->tenentid,);
                    $result = $this->curl_sgin($this->member_url . '/user/addCredit/' . $param['user_id'], $limit_data, 'post');

                    if ($result['code'] != '0000') {
                        echo json_encode(array('code' => '0004', 'description' => '加积分失败'));
                        exit;
                    }
                }
                echo json_encode(array('code' => '0000', 'description' => '绑定成功'));
            } else {
                echo json_encode(array('code' => '0001', 'description' => '绑定失败'));
            }
        }
    }

    //获取用户摇一摇中奖列表
    public function shakeLogList()
    {
        $param = $this->data;

        $condition = array(
            'conditions' => array('userid' => $param['uid'], 'exchange_type' => $param['type'], 'hit_status' => 2)
        );
        $count = $this->ExchangeLog->find('count', $condition);
        //调用组装分页条件
        $page = @$this->app_page($condition, $count, $param['pageSize'], $param['page'], array('ExchangeLog.create_time DESC'));
        $data = $this->ExchangeLog->find('all', $page['condition']);
        if ($param['type'] != 3) {
            $data = Hash::extract($data, '{n}.ExchangeLog');
        }
        echo json_encode(array('code' => '0000', 'description' => '获取成功', 'data' => array('page' => $page['page'], 'ismore' => $page['ismore'], 'list' => $data)));
    }

    //获取用户摇一摇中奖详情
    public function shakePrize()
    {
        $param = $this->data;
        $log = $this->ExchangeLog->find('first', array('conditions' => array('userid' => $param['uid'], 'ExchangeLog.id' => $param['logid'])));
        echo json_encode($log);
    }

    //接收第三方系统中奖信息(互动游戏)
    public function addPrize()
    {
        $param = $this->data;

        $uid = $this->request->params['uid'];
        $time = time();
        //获取用户信息(走接口)
        $user = $this->curl_sgin($this->member_url . 'user/detail?uid=' . $uid);
        if ($user['code'] == '0000') {
            $user = $user['data'];
        } else {
            echo json_encode(array('code' => '0003', 'description' => '获取用户信息失败'));
            exit;
        }
        if ($param['type'] == 1) {
            //添加积分
            $limit_data = array('type' => 1, 'limit' => $param['credit'], 'consumption' => 'credits1', 'logtype' => 10, 'tenantid' => $this->tenentid,);
            $result = $this->curl_sgin($this->member_url . '/user/addCredit/' . $uid, $limit_data, 'post');
            $code = '';
            if ($result['code'] != '0000') {
                echo json_encode(array('code' => '0001', 'description' => '积分不够或报错'));
                exit;
            }
        } else {
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
        if ($re) {
            echo json_encode(array('code' => '0000', 'description' => '接收成功'));
            exit;
        } else {
            echo json_encode(array('code' => '0001', 'description' => '接收失败'));
            exit;
        }
    }

    //获取积分商城首页置顶banner
    public function topBanners()
    {
        $param = $this->data;
        $param['limit'] = isset($param['limit']) ? $param['limit'] : 5;
        $condition = array(
            'conditions' => array(
                'status' => 1,
                'end_time >' => time(),
                'sort' => 1,
                'banner_img !=' => "",
                '(quantity-exchange_quantity) >' => 0,
                //'banner_img !='=>null,
            ),
            'fields' => array('id', 'product_name as title', 'banner_img as banner'),
            'order' => array('sort DESC', 'create_time DESC'),
            'contain' => array(),
            'limit' => $param['limit'],
        );

        $data = $this->Product->find('all', $condition);
        if (empty($data)) {
            $data[] = array(
                'id' => 0,
                'title' => '默认图片',
                'banner' => $this->pic_url . '/images/default_banner.png',
            );
        } else {
            $data = Hash::extract($data, '{n}.Product');
            foreach ($data as $key => $value) {
                $data[$key]['banner'] = $this->pic_url . $value['banner'];
            }
        }
        //print_r($data);
        //exit;
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            // ajax 请求的处理方式
            echo json_encode(array('code' => '0000', 'description' => '获取成功', 'data' => $data));
        } else {
            // 正常请求的处理方式
            return json_encode(array('code' => '0000', 'description' => '获取成功', 'data' => $data));
        };

    }

    public function selectReceivAddress()
    {
        $data = $this->request->data;
        $param = array(
            'id' => $data['id'],
            'uid' => $data['uid'],
            'token' => $data['token'],
            'tenantid' => $data['tenantid']
        );
//        $resdata = $this->selectAddress($this->member_url.'/userinfo/selectReceivAddress',$param,'post');
        $data = array();
        foreach ($param as $k => $v) {
            $data[$k] = $v;
        }
        $authorization = $this->asort($data);
        $resdata = $this->curl_sgin1($this->member_url . '/userinfo/selectReceivAddress', $data, 'post', $authorization);
        $jsons = array(
            'id' => $data['id'],
            'name' => $resdata['data']['name'],
            'phoneNum' => $resdata['data']['phoneNum'],
            'area' => $resdata['data']['area'],
            'street' => $resdata['data']['street'],
            'zipCode' => $resdata['data']['zipCode'],
            'isDefault' => 1
        );
        $json = json_encode($jsons);
        $param = array(
            'uid' => $data['uid'],
            'token' => $data['token'],
            'tenantid' => $data['tenantid'],
            'data' => $json
        );
        $data = array();
        foreach ($param as $k => $v) {
            $data[$k] = $v;
        }
        $authorization = $this->asort($data);
        $rdata = $this->curl_sgin1($this->member_url . '/userinfo/saveReceivAddress', $data, 'post', $authorization);
        echo json_encode($resdata);
    }

    public function gettopbanner()
    {
        $data = array(
            'appKey' => 1,
            'timestamp' => 1,
            'sign' => 1,
            'tenanid' => 'test'
        );
        $json = $this->topBanners();
        return json_decode($json);
        exit;
    }

    public function getgoodslist()
    {
        $json = $this->goodsList();
        return json_decode($json);
        exit;
    }

    public function huiYuan()
    {
        $param = $this->data;
        var_dump($param);

    }

    public function indextemp($topbanner = null, $goodslist = null)
    {
        if (isset($_GET['uid'])) {
            $uid = $_GET['uid'] + 0;//商品id
            $token = $_GET['token'];
        } else {

        }
        $title = '积分商城';
        if ($topbanner == null) {
            $topbanner = $this->gettopbanner();
            $topbanner = $this->object_to_array($topbanner);
        }
        if ($goodslist == null) {
            $goodslist = $this->getgoodslist();
            $goodslist = $this->object_to_array($goodslist);
        }
        $datas = array(
            'title' => $title,
            'uid' => $uid,
            'token' => $token,
            'topbanner' => $topbanner,
            'goodslist' => $goodslist
        );
        $index_statis_file = "./files/default/index_detail.html";//对应静态页文件
        $expr = 1;//静态文件有效期，十天
        if (file_exists($index_statis_file)) {
            $file_ctime = filectime($index_statis_file);//文件创建时间
            if ($file_ctime + $expr > time()) {//如果没过期
                echo file_get_contents($index_statis_file);//输出静态文件内容
                exit;
            } else {//如果已过期
                unlink($index_statis_file);//删除过期的静态页文件
                ob_start();                //从数据库读取数据，并赋值给相关变量
                include("./files/indexDetail.html");//加载对应的商品详情页模板
                $content = ob_get_contents();//把详情页内容赋值给$content变量
                file_put_contents($index_statis_file, $content);//写入内容到对应静态文件中
                ob_end_flush();//输出商品详情页信息
                exit;
            }
        } else {
            ob_start();     //从数据库读取数据，并赋值给相关变量
            include("./files/indexDetail.html");//加载对应的商品详情页模板
            $content = ob_get_contents();//把详情页内容赋值给$content变量
            file_put_contents($index_statis_file, $content);//写入内容到对应静态文件中
            ob_end_flush();//输出商品详情页信息
            exit;
        }
        exit;
    }

    function object_to_array($obj)
    {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($_arr as $key => $val) {
            $val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }

    public function asort($data = null)
    {
        if (empty($data)) {
            $message = '参数不能为空';
            return $message;
        }
        $arr = array();
        foreach ($data as $k => $v) {
            $arr[$k] = $v;
        }
        $key = '8w7jv4qb7eb5y';
        ksort($arr);
        $time = time();
        $str = '';
        foreach ($arr as $k => $v) {
            $str .= $k . $v;
        }
        $str = $time . $str . $key;
        $authorization = md5($str) . ';' . $time;
        return $authorization;
    }

    public function getMember($url = null, $datas = array(), $method = 'get')
    {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            $datas = $this->request->data;
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($this->member_url . '/user/detail', $data, 'post', $authorization);
            echo json_encode($rdata);
        } else {
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($url, $data, 'post', $authorization);
            return $rdata;
        }
    }

    public function getAddress($url = null, $datas = array(), $method = 'get')
    {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            $datas = $this->request->data;
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($this->member_url . '/userinfo/selectReceivAddress', $data, 'post', $authorization);
            echo json_encode($rdata);
        } else {
            $data = array();
            foreach ($data as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($url, $data, 'post', $authorization);
            return $rdata;
        }
    }

    public function getArea($url = null, $datas = array(), $method = 'get')
    {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            $datas = $this->request->data;
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($this->member_url . '/userinfo/getArea', $data, 'post', $authorization);
            echo json_encode($rdata);
        } else {
            $data = array();
            foreach ($data as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($url, $data, 'post', $authorization);
            return $rdata;
        }
    }

    public function saveAddress($url = null, $datas = array(), $method = 'get')
    {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            $datas = $this->request->data;
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $data['data'] = $data[0];
            unset($data[0]);
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($this->member_url . '/userinfo/saveReceivAddress', $data, 'post', $authorization);
//            var_dump($data);
//            var_dump($authorization);
//            exit;
            echo json_encode($rdata);
        } else {
            $data = array();
            foreach ($data as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($url, $data, 'post', $authorization);
            return $rdata;
        }
    }

    public function selectAddress($url = null, $datas = array(), $method = 'get')
    {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            $datas = $this->request->data;
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($this->member_url . '/userinfo/selectReceivAddress', $data, 'post', $authorization);
            echo json_encode($rdata);
        } else {
            $data = array();
            foreach ($data as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($url, $data, 'post', $authorization);
            return $rdata;
        }
    }

    public function delAddress($url = null, $datas = array(), $method = 'get')
    {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            $datas = $this->request->data;
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($this->member_url . '/userinfo/delReceivAddress', $data, 'post', $authorization);
            echo json_encode($rdata);
        } else {
            $data = array();
            foreach ($data as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($url, $data, 'post', $authorization);
            return $rdata;
        }
    }

    public function getorder($url = null, $datas = array(), $method = 'get')
    {
        if (!isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            $datas = $this->request->data;
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($this->member_url . '/orders/createOrders/1', $data, 'post', $authorization);
            echo json_encode($rdata);
        } else {
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($url, $data, 'post', $authorization);
            return $rdata;
        }
    }

    public function getOrdersInfo($url = null, $datas = array(), $method = 'get')
    {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            $datas = $this->request->data;
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($this->member_url . '/orders/getOrdersInfo', $data, 'post', $authorization);
            echo json_encode($rdata);
        } else {
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($url, $data, 'post', $authorization);
            return $rdata;
        }
    }

    public function getOrdersList($url = null, $datas = array(), $method = 'get')
    {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            $datas = $this->request->data;
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($this->member_url . '/orders/getOrdersList', $data, 'post', $authorization);
            echo json_encode($rdata);
        } else {
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($this->member_url . '/orders/getOrdersList', $data, 'post', $authorization);
            return $rdata;
        }
    }

    public function getUid()
    {
        $url = $this->request->data;
        $arr = parse_url($url);
        $arr = $arr['query'];
        $arr = explode('&', $arr);
        $params = array();
        foreach ($arr as $k => $v) {
            $k_v = explode('=', $v);
            $params[$k_v[0]] = $k_v[1];
        }
        $ToKen = $this->checkuser($url, $params);
        $ApiToKen = $params['SourceToken'];
        if ($ApiToKen == $ToKen) {
            $data = array(
                'openid' => $params['openid'],
                'tenantid' => $params['TenantID'],
            );
            $authorization = $this->asort($data);
            $rdata = $this->Usercheck($this->member_url . '/user/getUserInfo', $data, 'post', $authorization);
            $uid = $rdata['data']['uid'];
            $token = $rdata['data']['token'];
            if ($rdata) {
                $data = array(
                    'code' => '0000',
                    'message' => '获取成功',
                    'token' => $token,
                    'uid' => $uid
                );
                echo json_encode($data);
            } else {
                $data = array(
                    'code' => '0001',
                    'message' => '获取失败'
                );
                echo json_encode($data);
            }
        } else {
            $data = array(
                'code' => '0002',
                'message' => '不是合法用户请登录'
            );
            echo json_encode($data);
        }
    }

    public function checkuser($url = null, $params = null)
    {
        $SecretKey = 'okqb7vqm8y2I6Q7zeyyY9K1JfTzs';
        $TenantID = $params['TenantID'];
        $SourceID = $params['WeChatID'];
        $SecretKey = $TenantID . $SecretKey;
        $Timestamp = $params['Timestamp'];
        $OpenID = $params['openid'];
        $encryptData = array(
            'TenantID' => $TenantID, //租户ID
            'SourceID' => $SourceID, //渠道ID
            'SecretKey' => $SecretKey, //密钥，使用租户ID+渠道密钥
            'Timestamp' => $Timestamp,  //时间戳
            'OpenID' => $OpenID
        );
        ksort($encryptData, SORT_STRING);
        $encryptStr = implode(array_values($encryptData));
        $Token = sha1($encryptStr);
        return $Token;

    }

    public function Usercheck($url = null, $datas = array(), $method = 'get')
    {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
//            $datas=$this->request->data;
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($this->member_url . '/user/getUserInfo', $data, 'post', $authorization);
            return $rdata;
        } else {
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($url, $data, 'post', $authorization);
            return $rdata;
        }
    }
    public function getUserInfo($url = null, $datas = array(), $method = 'get')
    {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            $datas=$this->request->data;
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($this->member_url . '/user/getUserInfo', $data, 'post', $authorization);
            echo json_encode($rdata);
        } else {
            $data = array();
            foreach ($datas as $k => $v) {
                $data[$k] = $v;
            }
            $authorization = $this->asort($data);
            $rdata = $this->curl_sgin1($url, $data, 'post', $authorization);
            return $rdata;
        }
    }
    //调取接口方法
    private function curl_sgin($url, $param = array(), $method = 'get')
    {
        $curl = new RequestBase();
        $param = array_merge(array('appKey' => $this->appKey, 'timestamp' => time()), $param);
        ksort($param);
        $str = '';
        foreach ($param as $key => $value) {
            $str .= $key . $value;
        }
        $param['sign'] = md5($str . $this->appSecret);
        $re = $curl->exec($url, $param, $method);
        return json_decode($re, true);
    }

    private function curl_sgin1($url, $param = array(), $method = 'get', $authorization = null)
    {
        $curl = new RequestBase();
        $re = $curl->post($url, $param, NULL, $authorization);
        return json_decode($re, true);
    }

//    public function show()
//    {
//        $result = file_get_contents("http://www.kuaidi100.com/query?type=yuantong&postid=881443775034378914&id=1&valicode=&temp=0.19689508604579842");
//        $data = json_decode($result);
//        echo $result;
//    }
    public function show()
    {
        $num=$this->request->data;
        $num=$num['logisticsInfoNum'];
        if($num==''||$num=='undefined'||$num==null){
            return false;
        }
//        //参数设置
        $post_data = array();
        $post_data["customer"] = 'B0334F73C6182429F2B75BF03B8AE613';
        $key = 'lHcxEfKR9740';
        if(!empty($num)){
        $com=$this->getnum($num,$key);
        $com=json_decode($com);
        $com=$com[0]->comCode;
        $post_data["param"] = '{"com":"'.$com.'","num":"'.$num.'"}';
        $url = 'http://poll.kuaidi100.com/poll/query.do';
        $post_data["sign"] = md5($post_data["param"] . $key . $post_data["customer"]);
        $post_data["sign"] = strtoupper($post_data["sign"]);
        $o = "";
        foreach ($post_data as $k => $v) {
            $o .= "$k=" . urlencode($v) . "&";        //默认UTF-8编码格式
        }
        $post_data = substr($o, 0, -1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($ch);
        $data = str_replace("\&quot;", '"', $result);
//        $data = json_decode($data, true);
        }
    }
    public function getnum($num=null,$key=null){
        if(!empty($num)){
            $url='http://www.kuaidi100.com/autonumber/auto?num='.$num.'&key='.$key;
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch,CURLOPT_HEADER, 0);
            $output = curl_exec($ch);
            curl_close($ch);
            return $output;
        }
    }
    public function getSessionID(){
        $data=$this->request->data;
        $sessionID=$data['session_id'];
        $url=$data['url'];
        if($sessionID==Cache::read('sessionID',"_creditshop_")&&!empty(Cache::read('sessionID',"_creditshop_"))){
            echo json_encode(Cache::read('userInfo',"_creditshop_"));
        }else{
            if($this->Session->check('sessionID')&&$this->Session->check('userInfo')){
                 $this->Session->delete('sessionID');
                 $this->Session->delete('userInfo');
            }
            $url=$data['url'];
            $data = $this->curl_sgin($url.'/auth/getuser/',array('session_id'=>$sessionID),'get');
            $this->Session->write('sessionID',$sessionID);
            $this->Session->write('userInfo',$data);
            Cache::write('sessionID',$this->Session->read('sessionID'),"_creditshop_");
            Cache::write('userInfo',$this->Session->read('userInfo'),"_creditshop_");
            echo json_encode($data);
        }
    }
    public function confirmOrders(){
        $data=$this->request->data;
        $data=array(
            'ordersNo'=>$data['ordersNo'],
            'uid'=>$data['uid'],
            'token'=>$data['token'],
            'tenantid'=>$data['tenantid']
        );
        $authorization = $this->asort($data);
        $data = $this->curl_sgin1($this->member_url .'/orders/confirmOrders',$data,'post',$authorization);
        if($data['code']=='0000'){
            return json_encode(array('code'=>$data['code'],'description'=>$data['description']));
        }else{
            return json_encode(array('code'=>$data['code'],'description'=>'确认收货失败'));
        }
    }



    //分享参数
    public function getSignPackage() {
        $data=$this->request->data;
        $tid=$data['tenantid'];
        $wid=$data['weid'];
        $keys=app_keys;
        $str = $this->getTicket($tid,$wid,$keys);
        //$jsapiTicket = $this->getJsApiTicket();
        $as=explode('#',$str);
        $app=$as[0];
        $jsapiTicket=$as[1];
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        //$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $url = $_SERVER['HTTP_REFERER'];
        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $app,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        echo json_encode($signPackage);
    }
    public function getTicket($tid=null,$wid=null,$key=null) {

//          $sh=Session::get('share','');
        $sh=$_SESSION['share'];
        if(empty($sh) || $sh['expires_time']<time())
        {
            $tt=time();
            $encryptData = array(
                'TenantID' => $tid,
                'SourceID' => $wid,
                'SecretKey' => $tid.$key,
                'Timestamp' => $tt,
            );
            ksort($encryptData, SORT_STRING);
            $encryptStr = implode(array_values($encryptData));
            $Tokenas = sha1($encryptStr);
            $hostre="http://".$tid.'.'.wx_url."/WeChatMatrixs/getJSsdkInfo/?SourceToken=$Tokenas&TenantID=".$tid."&WeChatID=".$wid."&Timestamp=$tt";
            $resultre =$this->curlhttp($hostre);
            $result= json_decode($resultre,true);
//          Session::put('share',$result['result']);
            $_SESSION['share']=$result['result'];
//          Session::save();
            return $result['result']['appId'].'#'.$result['result']['jsapi_ticket'];
        }else{
            return $sh['appId'].'#'.$sh['jsapi_ticket'];
        }

    }
    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode($this->get_php_file("jsapi_ticket.php"));
        if ($data->expire_time < time()) {
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $data->expire_time = time() + 7000;
                $data->jsapi_ticket = $ticket;
                $this->set_php_file("jsapi_ticket.php", json_encode($data));
            }
        } else {
            $ticket = $data->jsapi_ticket;
        }

        return $ticket;
    }

    private function getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode($this->get_php_file("access_token.php"));
        if ($data->expire_time < time()) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $this->set_php_file("access_token.php", json_encode($data));
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
    }
    public function curlhttp($url)
    {
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,0);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,'');
        $return=curl_exec($ch);
        curl_close($ch);
        return $return;

    }
    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    private function get_php_file($filename) {
        return trim(substr(file_get_contents("../jssdk/".$filename), 15));
    }
    private function set_php_file($filename, $content) {
        $fp = fopen("../jssdk/".$filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }

    public function checkgoodsId(){
        $data=$this->request->data;
        $goodsId=$data['goodsId'];
        if(!empty($goodsId)){
            $condition = array(
                'conditions' => array(
                    'id' => $goodsId
                ),
                'contain' => array()
            );
            $Product = $this->Product->find('first', $condition)['Product'];
            if(!empty($Product)){
                echo json_encode(array('code'=>'0000','message'=>'获取成功','goodsId'=>$goodsId));
            }else{
                echo json_encode(array('code'=>'0001','message'=>'该商品已下架'));
            }
        }else{
            echo json_encode(array('code'=>'0005','message'=>'商品号为空'));
        }
    }
}
