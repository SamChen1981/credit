<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2015/7/22
 * Time: 9:30
 */

App::uses('Model', 'Model');
App::uses('AppModel', 'Model');

class product extends AppModel{
   var $name= "product";

   var  $valitate=array();
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
      return json_encode(array('code'=>'0000','description'=>'获取成功','data'=>$data));
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
