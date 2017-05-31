<?php 
	class AjaxController extends AppController{
		public $uses = array(
				'Tv',
				'ShakeActivity',
				'Product'
		);
		public $types = array(2,3);
		public function getProgramme(){
			$tvid = $this->request->data('tvid');
			$return = array('data'=>array(0=>'请选择节目'));
			if($tvid && $tvid != 0){
				$childTvlist = $this->Tv->find('all',array('conditions'=>array('parent_id'=>$tvid,'is_show'=>1,'type'=>2)));
				foreach ($childTvlist as $tv){
					$return['data'][$tv['Tv']['id']] = $tv['Tv']['name'];
				}
			}
			$this->jsonRetrun($return);
		}
		
		public function getActivity(){
			$this->autoRender = false;
			$type = $this->request->data('type');
			$realtype = $this->formatType($type);
			$return = array('code'=>0,'message'=>'获取失败。请重试！');
			if(!in_array($realtype, $this->types)){
				if($realtype == 5){
					//获取商品兑换
					$data = array();
					APP::import('Vendor','Ccurl');
					$url = PRODUCT_API_URL.'/productData/getdataByProduct';
					$result = Ccurl::post($url,array());
					$result = json_decode($result,true);
					if($result['returnCode'] == 1){
						foreach ($result['data'] as $key => $product){
							$data[$key]['item'] = $product;
						}
						$return = array('code'=>200,'message'=>'获取成功','data'=>$data,'activityType'=>$type);
					}else{
						$return = array('code'=>0,'message'=>$result['returnDesc']);
					}
				}else{;
					$return = array('code'=>0,'message'=>'没有此类型的活动');
				}
			}else{
				$activityList = $this->ShakeActivity->find('all',array('conditions'=>array('type'=>$realtype,'status'=>array(1,2))));
				$data = array();
				foreach ($activityList as $key=>$activity){
					$dataItem = array();
					$dataItem['id'] = $activity['ShakeActivity']['id'];
					$dataItem['create_time'] = date('Y-m-d H:i:s',$activity['ShakeActivity']['create_time']);
					$dataItem['name'] = $activity['ShakeActivity']['name'];
					$data[$key]['item'] = $dataItem;
				}
				$return = array('code'=>200,'message'=>'获取成功','data'=>$data,'activityType'=>$type);
			}
			
			print json_encode($return);
		}
		
		public function formatType($type = 1){
			$array = array(
				2=>3,
				3=>2
			);
			if($array[$type]){
				return $array[$type];
			}else{
				return $type;
			}
		}
	}
?>