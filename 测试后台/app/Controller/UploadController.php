<?php 
	class UploadController extends AppController{
		public $name = 'upload';
		public $layout = "";
		
		public function upload(){
			$data = $this->request->params['form'];
			if(!$data) return $this->jsonRetrun(array('message'=>__('BadRequest')),300);
			$this->Upload = $this->Components->load('Upload');
			// $file = $this->Upload->upload($data['file'], WWW_ROOT . 'upload');
			$file = $this->Upload->upload($data['file'], UP_DIR .DS.'upload');
			$fileUrl = array('theUrl'=>'/upload/' . $file['fileDir'] . '/' . $file['fileName']);
			return $this->jsonRetrun($fileUrl, 200);
		}
	}
