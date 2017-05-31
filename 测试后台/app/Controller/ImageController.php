<?php 
	class ImageController extends AppController{
		public $autoRender = false;
		public function imageCut(){
			//获取裁剪信息
			//原图地址
			$imagePath = $this->request->data('imgPath');
            $imageName= $this->request->data('imageName');
            //剪切图地址
            $bannerPath=$this->request->data('imageUrl');
			$x = $this->request->data('x');
			$y = $this->request->data('y');
			$w = $this->request->data('w');
			$h = $this->request->data('h');
;			if(!isset($imagePath) || !isset($x) || !isset($y) || !isset($w) || !isset($h)){
				$return = array('returnCode'=>0,'returnDesc'=>'参数不正确');
				print_r(json_encode($return));
				die;
			}
			//获取图片物理路径
            $path=str_replace("\\","/",WWW_ROOT);

			$realPath = $path.$imagePath;
            //获取前一张剪切图片的物理路径
            $realbannerPath=$path.$bannerPath;

            //echo $realbannerPath;
			//设置新的图片路径
			$newPath = WWW_ROOT.'upload'.DS.'thumb';
			$this->Image = $this->Components->load('Image');
			$newImage = $this->Image->cut($realPath,$x,$y,$w,$h,$newPath);
			if($newImage){
                if(strpos($realbannerPath,"thumb",1) && $imagePath!==$bannerPath){
                    unlink($realbannerPath);
                }
                $newImage = str_replace($newPath, '/upload/thumb', $newImage);
                $newImage = str_replace('\\', '/', $newImage);
                $return = array('returnCode'=>1,'returnDesc'=>'编辑成功','thumb'=>$newImage);

			}else{
				$return = array('returnCode'=>0,'returnDesc'=>'编辑失败，请重试');
			}
            $return["imageName"]=$imageName;
			print json_encode($return);
			die;
		}
	}
?>