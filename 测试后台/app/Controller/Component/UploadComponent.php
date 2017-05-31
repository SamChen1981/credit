<?php
/**
 * Upload component
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

App::uses('Validation', 'Utility');
App::uses('Folder','Utility');
App::uses('File','Utility');

class UploadComponent extends Component{
    public $name = 'Upload';

    // Upload an image
    public function upload($data = null, $path = null) {
        if(!$data || !$path) return false;
        $date = date('Ymd');
        $uploadDir = new Folder($path . DS . $date, true, 0777);

        if(!$uploadDir) throw new InternalErrorException();

        $ext = $this->_validFile($data);
        if(!$ext) return false;
        $fileName = $this->_random(10) . '.' . $ext;
        $savePath = $uploadDir->pwd(). DS . $fileName;
        if(move_uploaded_file($data['tmp_name'], $savePath)){
            $savePath = str_replace('\\','/',$savePath);
            //保存到MPC能访问的地址\
           	return array('filePath'=>$savePath,'fileName'=>$fileName,'fileDir'=>$date);
        }else{
            return false;
        }
    }
    
    /**
     * 复制文件
     *
     * @param string $fileUrl
     * @param string $aimUrl
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    function copyFile($fileUrl, $aimUrl, $overWrite = true) {
    	if (!file_exists($fileUrl)) {
    		return false;
    	}
    	if (file_exists($aimUrl) && $overWrite == false) {
    		return false;
    	} elseif (file_exists($aimUrl) && $overWrite == true) {
    		$this->unlinkFile($aimUrl);
    	}
    	$aimDir = dirname($aimUrl);
    	$this->createDir($aimDir);
    	copy($fileUrl, $aimUrl);
    	return true;
    }
    
    /**
     * 删除文件
     *
     * @param string $aimUrl
     * @return boolean
     */
    function unlinkFile($aimUrl) {
    	if (file_exists($aimUrl)) {
    		unlink($aimUrl);
    		return true;
    	} else {
    		return false;
    	}
    }
    
    /**
     * 建立文件夹
     *
     * @param string $aimUrl
     * @return viod
     */
    function createDir($aimUrl) {
    	$aimUrl = str_replace('', '/', $aimUrl);
    	$aimDir = '';
    	$arr = explode('/', $aimUrl);
    	$result = true;
    	foreach ($arr as $str) {
    		$aimDir .= $str . '/';
    		if (!file_exists($aimDir)) {
    			$result = mkdir($aimDir);
    		}
    	}
    	return $result;
    }
    
    private function _random($length, $numeric = 0) {
        $numeric=1;
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return date('Y').$hash;
    } 
    
    private function _validFile($check, $settings = array()) {
        $_default = array(
            'required' => false,
            'extensions' => array('gif','jpg','jpeg','bmp','png')
        );

        $_settings = array_merge(
            $_default,is_array($settings)?$settings:array()            
        );

        // Remove first level of Array
        $_check = $check;

        if($_settings['required'] == false && $_check['size'] == 0) {
            return false;
        }

        // No file uploaded.
        if($_settings['required'] && $_check['size'] == 0) {
            return false;
        }

        // Check for Basic PHP file errors.
        if($_check['error'] !== 0) {
            return false;
        }

        // Use PHPs own file validation method.
        if(is_uploaded_file($_check['tmp_name']) == false) {
            return false;
        }

        // Valid extension
        if(Validation::extension($_check,$_settings['extensions'])){
            $filename = explode('.', $_check['name']);
            $extnsions = strtolower(array_pop($filename));
            return $extnsions;
        }else{
            return false;
        }
        
    }
}