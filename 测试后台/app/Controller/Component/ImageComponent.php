<?php 
/**
 * Image component
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
class ImageComponent extends Component{
	public $name = "Image";
	public function cut($image=null,$x=0,$y=0,$w=0,$h=0,$path=null){
		//设置新的图片保存路劲
		$date = date('Ymd');
		$thumbDir = new Folder($path . DS . $date, true, 777);
		if(!$thumbDir) throw new InternalErrorException();
		$ext = 'jpg';
		if(!$ext) return false;
		$fileName = $this->_random(10) . '.' . $ext;
		$savePath = $thumbDir->pwd(). DS . $fileName;
		//开始裁剪图片
		try {
            $imgInfo = getimagesize($image);
            $imgType = $imgInfo['mime'];
            $back = null;
            switch ($imgType){
                case 'image/png':

                    $back = imagecreatefrompng($image);
                    break;
                case 'image/jpg':

                    $back=imagecreatefromjpeg($image);
                    break;
                case 'image/jpeg':
                    $back=imagecreatefromjpeg($image);
                    break;
                case 'image/bmp':
                    $back=imagecreatefromwbmp($image);
                    break;
                case 'image/gif':
                    $back=imagecreatefromgif($image);
                    break;
            }
			$new=imagecreatetruecolor($w, $h);
			imagecopyresampled($new, $back, 0, 0, $x, $y, $w, $h,$w,$h);
			imagejpeg($new, $savePath);
			imagedestroy($new);
			imagedestroy($back);
			return $savePath;
		}catch (Exception $e){
			return false;
		}
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


    /*
    * $url  array  删除图片的相对地址
    * return  $flag
    */
	public function delImg($url){
		//获取图片物理路径
		$flag=true;
		if(!is_array($url)){
			$url=array($url);
		}
        $path=str_replace("\\","/",WWW_ROOT);
        foreach ($url as $k => $v) {
	        $realPath = $path.$v;
	        if(@!unlink($realPath)){
	        	$flag=false;
	        }
        }
         return $flag;      
	}
}
?>