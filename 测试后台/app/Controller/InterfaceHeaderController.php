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

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package     app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class InterfaceHeaderController extends Controller {

    //appSecrit
    public $appSecret = '123456789';
    public $components=array("Session");
    /*public $components = array('Session',
            'Auth'=>array(
                    'authenticate' => array(
                            'Form' => array(
                                    'scope'=>array('User.ISVALID'=>1)
                            )
                    ),
                    'authError' => false,
                    'loginRedirect' => array('controller' => 'orders', 'action' => 'index'),
                    'loginAction' => array('controller' => 'users','action' => 'login',)
            )
    );*/
    public $pagesize = 10;
    public $data = array();
    public function beforeFilter(){
       $this->Session->write("tenantid",$this->get_tenantid());
       // $this->do_request();
        $this->autoLayout = false;
        $this->autoRender = false;
        //$this->checkSign();
    }
    //sign验证
    protected function checkSign(){
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
            echo json_encode(array('code'=>9999,'description'=>'未知错误'));
            exit;
        }
        if(!$data){
            echo json_encode(array('code'=>9999,'description'=>'参数为空'));
            exit;
        }
        if(!isset($data['sign'])&&!$data['sign']){ 
            echo json_encode(array('code'=>9999,'description'=>'sign不能为空'));
            exit;
        }
        $sign = $data['sign'];
        $this->data = $data;
        return true;
        unset($data['sign']);
        unset($data['url']);
        ksort($data);
        $str = '';
        foreach ($data as $key => $value) {
            $str.=$key.$value;
        }
        if($sign!=md5($str.$this->appSecret)){ 
            echo json_encode(array('code'=>1111,'description'=>'sign不匹配'));
            exit;
        }
        return true;
    }
    /*
     app使用分页插件
    @param size 每页记录条数
    @param condition 条件
    @param cpage 当前页
    @param count 记录总条数
    @param order 排序
    */
    protected function app_page($condition,$count,$size,$cpage,$order=''){
        $condition['limit'] = $size?$size:$this->pagesize;
        $condition['page'] = $cpage?$cpage:1;
        $condition['offset'] = ($cpage-1)*$size;
        $condition['order'] = $order;
        $more = $count>($condition['page']*$condition['limit'])?true:false;
        return array('condition'=>$condition,'ismore'=>$more,'page'=>$condition['page']);
    }
    //获取租户id
   public function   get_tenantid(){
        if($_GET['tenantid']){
            $tenantid = [addslashes($_GET['tenantid'])];
        }else{
            preg_match('@^(?:http://)?([^/]+)@i', FULL_BASE_URL, $matches);
            $tenantid = explode('.', $matches[1]);
        }
        return trim($tenantid[0]);
    }
    //转意字符串
    public  function do_string($data){
        if(is_array($data)){
            foreach($data as $k=>$v){
                if(is_array($v)){
                    $data[$k]=do_string($v);
                }else{
                    $data[$k]=trim((!get_magic_quotes_gpc())?addslashes($v):$v);
                }
            }
        }else{
            $data=trim((!get_magic_quotes_gpc())?addslashes($data):$data);
        }
        return $data;
    }
    public  function  do_request(){
        if($this->request->is('get')){
            $this->request->query=$this->do_string($this->request->query);
        }elseif($this->request->is('post')){
            $this->request->data=$this->do_string($this->request->data);
        }elseif($this->request->is('put')){
            $this->request->input=$this->do_string($this->request->input());
        }elseif($this->request->is('delete')){
           $this->request->input=$this->do_string($this->request->input());
        }
   }

}
