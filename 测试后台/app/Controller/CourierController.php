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

// APP::import('Controller','InterfaceHeader');
// App::uses('String', 'Utility');
// App::import('Vendor','RequestBase');
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class CourierController
{
  public function show()
    {
        $num=$this->request->data;
        if(!$num){
            $return=array('code'=>'0003','message'=>'请检查参数');
            echo json_encode($array);exit;
        }
        $num=$num['logisticsInfoNum'];
        if($num==''||$num=='undefined'||$num==null){
            $return=array('code'=>'0001','message'=>'请提供物流单号');
            echo json_encode($array);exit;
        }
        //参数设置
        $post_data = array();
        $post_data["customer"] = 'B0334F73C6182429F2B75BF03B8AE613';
        $key = 'lHcxEfKR9740';
        if(!empty($num)){
        $com=$this->getnum($num,$key);
        if(!$com){
           $return=array('code'=>'0002','message'=>'查找物流供应商编码失败');
           echo json_encode($array);exit;
        }
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
        $data = json_decode($data, true);
        if($data){
           $return=array('code'=>'0000','message'=>'获取物流信息成功','data'=>$data);
           echo json_encode($array);exit;
        }
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
}