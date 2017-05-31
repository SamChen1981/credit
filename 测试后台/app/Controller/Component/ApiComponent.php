<?php
/**
 * NmcAPI component
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Controller.Component
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Component', 'Controller');

class APIComponent extends Component{

	public $appKey = APPKEY;
	public $appSecret = APPSECRET;

	public function exec($url = '', $postData = array()){

		return json_decode($this->curlExec($url, $postData),true);
		
	}

/**
 * 利用CURL扩展POST数据
 * @param  string $uri  访问的URL
 * @param  mixed $data 传输的数据
 * @return mixed       返回的结果
 */
	public function curlExec($uri, $data){
		if( is_array($data) && $data ){
			foreach ($data as $key => $var) {
				$tmp[]= $key.'='.$var;
			}
			$postData = implode('&', $tmp);
		}else{
			$postData = $data;
		}

		$ch = curl_init();

	    $timeout = 5; 
	    curl_setopt($ch, CURLOPT_URL, $uri);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

		$output = curl_exec($ch);

		curl_close($ch) ;

		return $output;
	}
}