<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');
App::import('Vendor','RequestBase');
App::uses('File', 'Utility');
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller{
//	public $components = array('Session',
//			'Auth'=>array(
//					'authenticate' => array(
//							'Form' => array(
//									'scope'=>array('User.ISVALID'=>1)
//							)
//					),
//					'authError' => false,
//					'loginRedirect' => array('controller' => 'orders', 'action' => 'index'),
//					'loginAction' => array('controller' => 'users','action' => 'login',)
//			)
//	);
    public $components=array("Session");
    public function beforeFilter(){
//        $this->beforeRender();
    }
    public function beforeRender(){
       //获取路由信息；
      $params=$this->request->params;
        //判断会话是否保持
        if($this->request->is('get')){
            $data = $this->request->query;
        }elseif($this->request->is('post')){
            $data = $this->request->data;
        }elseif($this->request->is('put')){
            $data = $this->request->input();
            $data = json_decode((string)$data,true);
        }elseif($this->request->is('delete')){
            $data = $this->request->input();
            $data = json_decode((string)$data,true);
        }else{
            die("数据传输有误");
        }
      $tenantid=$this->get_tenantid();
      if(isset($data['uid']) && isset($data['token'])){
           $this->Session->write("user_info",$data);
           $user_info=$data;
      }else{
          $user_info=$this->Session->read("user_info");
      }
      if(isset($user_info['uid']) &&  isset($user_info['token'])){
        if(!$uid_nmc=Cache::read($tenantid."_".$user_info['uid'].$user_info['token'],"_creditshop_")){
          if($tenant_config=Cache::read($tenantid,'_cake_config_')){
            $data1['appKey']=$tenant_config['appkey'];
            $data1['action']='getPermission';
            $data1['timestamp']=date('Y-m-d H:i:s');
            $data1['uid']=$user_info['uid'];
            $data1['token']=$user_info['token'];
            $data1['type']=1;
            $data1['tenantid']=$tenantid;
            $sign=$this->do_sign($data1,$tenant_config['appsecret']);
            $data1['sign']=$sign;
            $parameter['parameter']=json_encode($data1);
            $reslut=$this->curl_sgin(NMC_URL,$parameter,'post');
             if($reslut['returnCode']==0){
              //缓存
              Cache::write($tenantid."_".$user_info['uid'].$user_info['token'],$reslut['returnData'],"_creditshop_");
              $uid_nmc=$reslut['returnData'];
             }else{
                die($reslut['returnDesc']);
             }
           }
          }
      }
//      //验证模块权限
      // if(@!in_array(strtolower($params['action']),$this->array_lower($uid_nmc['appPermission']))){
      //     die("用户没有权限");
      // }
    }

    public function array_lower($array){
       $a_str=implode(',',$array);
       $a_lowercase=strtolower($a_str);
       $b=explode(',',$a_lowercase);
       return $b;
    }

    protected function jsonRetrun($params = array(), $statusCode = '200', $recode = false){
        $this->autoRender = false;

        $params = array_merge($params,array('statusCode'=>$statusCode));

        echo new CakeResponse(
            array(
                'body' => json_encode($params),
                'type' => "application/json"
            )
        );

        return false;
    }
   //调取接口方法
    private function curl_sgin($url,$parameter=array(),$method='get'){
        $curl = new RequestBase();
        ksort($parameter);

        $re = $curl->exec($url,$parameter,$method);
        return json_decode($re,true);
    }
    //判断用户操作权限
    private  function detect_auth($rote,$permission=''){
      if(!$permission || !is_array($permission)){
          return false;
      }
      $flag=false;
      foreach($permission as $k=>$v){
         if(strtolower($v)==strtolower($rote['controller'])){
            if($this->detect_auth_action($rote,$v)){
              $flag=true;
              break;
            }
         }
      }
      return $flag;
    }
    //判断方法
    private  function detect_auth_action($rote,$permission=''){
      if(!$permission || !is_array($permission)){
          return false;
      }
      $flag=false;
      foreach($permission as $k=>$v){
         if(strtolower($v)==strtolower($rote['action'])){
            $flag=true;
            break;
         }
      }
      return $flag;
    }
    //生成租户sign
    private  function do_sign($prams,$appSecret){
        unset($prams['appKey'],$prams['action'],$prams['timestamp'],$prams['tenantid']);
        ksort($prams);
        $str='';
          foreach($prams as $k=>$v){
              $str.=$k.$v;
          }
          $sign=md5($str.$appSecret);
          return $sign;
    }


    protected function get_tenantid(){
       if($_GET['tenantid']){
            $tenantid = [addslashes($_GET['tenantid'])];
        }else{
            preg_match('@^(?:http://)?([^/]+)@i', FULL_BASE_URL, $matches);
            $tenantid = explode('.', $matches[1]);
        }
        return trim($tenantid[0]);
    }
}
