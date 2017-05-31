<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2015/8/7
 * Time: 14:40
 */
class ProductDataController extends AppController{
    public $uses = array('Product','ExchangeLog');

    public  function getdataByProduct(){
        $data=$this->Product->find("all",array("conditions"=>array("Product.status"=>1)));
        $return=array('returnCode'=>0,'returnDesc'=>'没有活动数据');
        if($data){
            $arr=array();
            foreach($data as $k => $v){
              $arr[$k]["id"]=$v["Product"]['id'];
              $arr["$k"]["name"]=$v['Product']['activity_name'];
              $arr["$k"]["create_time"]=date("Y-m-d H:i:s",$v['Product']['create_time']);
            }
            $return=array('returnCode'=>1,'returnDesc'=>'获取进行中的商品活动',"data"=>$arr);
        }
        print json_encode($return);
        die;
    }
    
    public function activityLogs(){
    	$params = $this->request->data('parameter');
    	if(!$params){
    		$return = array('returnCode'=>0,'returnDesc'=>'缺少参数');
    		print json_encode($return);
    		die;
    	}
    	$params = json_decode($params,true);
    	if(!$params['activityId']){
    		$return = array('returnCode'=>0,'returnDesc'=>'缺少参数');
    		print json_encode($return);
    		die;
    	}
    	$activityId = $params['activityId'];
    	$pageSize = isset($params['pageSize']) ? $params['pageSize'] : 7;
    	$pageIndex = isset($params['pageIndex']) ? $params['pageIndex'] : 0;
    	$status = isset($params['status']) ? $params['status'] : 0;
    	$prizeId = isset($params['prize']) ? $params['prize'] : 0;
    	$keywords = isset($params['keywords']) ? $params['keywords'] : '';
    	
    	//获取参与用户数
    	$userCount = $this->ExchangeLog->find('count',array('conditions'=>array('activity_id'=>$activityId),'group'=>array('userid')));
    	//获取参与总数
    	$logCount = $this->ExchangeLog->find('count',array('conditions'=>array('activity_id'=>$activityId)));
    	//人均参与数
    	$average = 0;
    	if($userCount){
    		$average = $logCount / $userCount;
    	}
    	//通过分组获得奖品中奖信息
    	$prizeList = $this->ExchangeLog->query('SELECT logs.activity_id,logs.prize_id,count(1) AS count FROM operation_exchange_logs as logs WHERE activity_id = '.$activityId.' GROUP BY logs.prize_id');
    	$prizes = array();
    	foreach ($prizeList as $key=>$prize){
    		$prizes[$key]['activity_id'] = $prize['logs']['activity_id'];
    		$prizes[$key]['prize_id'] = $prize['logs']['prize_id'];
    		$prizes[$key]['count'] = $prize[0]['count'];
    	}
    	
    	//分页查询日志数据
    	$conditions = array('activity_id'=>$activityId);
    	if($status != 0){
    		$conditions['hit_status'] = $status;
    	}
    	if($prizeId != 0){
    		$conditions['prize_id'] = $prizeId;
    	}
    	if($keywords != ''){
    		$conditions['or']=array('user_name LIKE' => '%'.$keywords.'%','mobile_phone LIKE'=>'%'.$keywords.'%');
    	}
    	$exchangeLogCount = $this->ExchangeLog->find('count',array('conditions'=>$conditions));
    	$exchangeLogs = $this->ExchangeLog->find('all',array('conditions'=>$conditions,'order'=>'id DESC','limit'=>$pageSize,'page'=>$pageIndex));
    	$exchangeLogList = array();
    	foreach ($exchangeLogs as $log){
    		array_push($exchangeLogList, $log['ExchangeLog']);
    	}
    	//开始组装数据
    	$data = array(
    		'returnCode'=>1,
    		'returnDesc'=>'获取日志成功',
    		'data'=>array(
    			'activityId'=>$activityId,
    			'userCount'=>$userCount,
    			'logCount'=>$logCount,
    			'average'=>$average,
    			'pageIndex'=>$pageIndex,
    			'pageSize'=>$pageSize,
    			'pageCount'=>$exchangeLogCount % $pageSize == 0 ? intval($exchangeLogCount / $pageSize) : intval($exchangeLogCount / $pageSize)+1,
    			'prizes'=>$prizes,
    			'exchangeLogCount'=>$exchangeLogCount,
    			'exchangeLogList'=>$exchangeLogs,
    		),
    	);
    	print json_encode($data);
    	die;
    }
    
    public function exportActvityLogExcel(){
    	$activityId = $this->request->query('activityId');
    	$prizeItem = $this->request->query('prize');
    	$ststus = $this->request->query('status');
    	$keywords = $this->request->query('keywords');
    	$conditions = array('activity_id'=>$activityId);
    		
    	if(isset($prizeItem) && $prizeItem != 0){
    		$conditions['prize_id'] = $prizeItem;
    	}
    	if(isset($ststus) && $ststus != 0){
    		$conditions['hit_status'] = $ststus;
    	}
    	if(isset($keywords) && $keywords != ''){
    		$conditions['or']=array('user_name LIKE' => '%'.$keywords.'%','mobile_phone LIKE'=>'%'.$keywords.'%');
    	}
        //获取奖品信息
        APP::import('Vendor','Ccurl');
        $url = SHAKE_API_URL.'/dataService/getPrizes';
        $params = array('activityId'=>$activityId);
        $prizeData = Ccurl::post($url,$params);
        $prizeData = json_decode($prizeData,true);
        $prizes = $prizeData['data'];
        $prizeOption = array();
        $prizeCount = array();
        foreach($prizes as $prize){
            $prizeOption[$prize['id']] = $prize['name'];
            $prizeCount[$prize['id']] = $prize['number'];
        }
    	$shakeActivityLogs = $this->ExchangeLog->find('all',array('fields'=>array('id','user_name','mobile_phone','create_time','prize_id','hit_status'),'conditions'=>$conditions,'limit'=>1000,'order'=>'create_time DESC'));
    	$excelData = array();
    	$excelTitle = '摇一摇统计'.date('Ymd',time());
    	foreach ($shakeActivityLogs as $log){
    		$logArr = array();
    		$logArr['id'] = $log['ExchangeLog']['id'];
    		$logArr['user_name'] = $log['ExchangeLog']['user_name'];
    		$logArr['user_mobile_phone'] = $log['ExchangeLog']['mobile_phone'];
    		$logArr['create_time'] = date('Y-m-d H:i:s',$log['ExchangeLog']['create_time']);
    		$logArr['hit_status'] = $log['ExchangeLog']['hit_status'] == 2 ? '中奖' : '未中奖';
    		$logArr['prizeName'] = $log['ExchangeLog']['prize_id'] ? $prizeOption[$log['ExchangeLog']['prize_id']] : '无';
    		$logArr['prizeSize'] = $log['ExchangeLog']['prize_id'] ? $prizeCount[$log['ExchangeLog']['prize_id']] : 0;

    		array_push($excelData, $logArr);
    	}
    	App::import('vendors', 'ExcelTools');
    	$excelTools = new ExcelTools();
    	$excelTools->exportExcel($excelData,$excelTitle);
    }

    public function checkActvityLogCount(){
        $activityId = $this->request->query('activityId');
        $prizeItem = $this->request->query('prize');
        $ststus = $this->request->query('status');
        $keywords = $this->request->query('keywords');
        $callback = $this->request->query('callback');
        $conditions = array('activity_id'=>$activityId);

        if(isset($prizeItem) && $prizeItem != 0){
            $conditions['prize_id'] = $prizeItem;
        }
        if(isset($ststus) && $ststus != 0){
            $conditions['hit_status'] = $ststus;
        }
        if(isset($keywords) && $keywords != ''){
            $conditions['or']=array('user_name LIKE' => '%'.$keywords.'%','mobile_phone LIKE'=>'%'.$keywords.'%');
        }
        $count = $this->ExchangeLog->find('count',array('conditions'=>$conditions));
        if($count > 1000){
            $return = array('code'=>0);
        }else{
            $return = array('code'=>200);
        }

        echo $callback."(".json_encode($return).")";
        die;
    }

    public function getActivityPeopleNumber(){
        //查询所有摇一摇活动的统计数据
        $logType = 2;
        $activityList = $this->ExchangeLog->query('SELECT logs.activity_id,count(1) AS count FROM operation_exchange_logs as logs WHERE logs.exchange_type = '.$logType.' GROUP BY logs.activity_id');
        $activityCount = array();
        foreach ($activityList as $key=>$logCount){
            $activityCount[$key]['activityId'] = $logCount['logs']['activity_id'];
            $activityCount[$key]['count'] = $logCount[0]['count'];
        }
        $return = array('returnCode'=>1,'returnDesc'=>'获取成功','data'=>$activityCount);
        echo json_encode($return);
        die;
    }
}