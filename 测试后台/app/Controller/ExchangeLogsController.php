<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2015/7/22
 * Time: 11:20
 */
// 商品和用户兑换类
App::uses('String', 'Utility');
class ExchangeLogsController extends AppController{
    var $name="ExchangeLogs";

    public $uses=array("ExchangeLog","Product");
    //搜索类型
    protected $type=array(1,2,3,4);
    //对应字段名
    protected $typeName=array(
        1=>"activity_name",
        2=>"user_name",
        3=>'mobile_phone',
        4=>'code',
    );
    //前台类型展示
    public $showName=array(
        1=>"活动名称",
        2=>"用户名",
        3=>'手机号码',
        4=>'兑换码',
    );

    public  $statusName=array(
        1=>"进行中",
        2=>"已结束",
        3=>'已兑完',
        4=>'已撤销',
        5=>'草稿箱',
    );

    public $exchangeState=array(
        1=>'未兑换',
        2=>"已兑换"
    );
    public  $sreachName=array(
        1=>'ExchangeLog.user_name',
        2=>"ExchangeLog.mobile_phone"
    );

    public  $Atype=array(
        1=>'APP活动',
        2=>"TV活动",
        3=>"现场活动"
    );
    //分页条件配置
    public $paginate = array(
        'limit'=>20,
        'paramType'=>'querystring',
        'order'=>array(
            'id'=>'DESC',
            'create_time'=>'DESC'
        ),
    );
    //商品兑换——展示商品活动兑换情况情况（以活动名称，用户名，手机，兑换码）
    public  function index(){
        $type=intval($this->request->query("type"));
        $sreach=$this->request->query("sreach")?trim($this->request->query("sreach")):"";
        $export=$this->request->query("export")?$this->request->query("export"):'';
        $activityForm=$this->request->query('activityForm')?$this->request->query('activityForm'):1;
         //搜索积分商城
        if($activityForm==1){
            //搜索活动名称
            if($type==1){
                $this->SearchByProduct($type,$sreach);

            }elseif(in_array($type,array(2,3,4))){
                $this->SearchFromLogs($type,2,$sreach);
            }
            else{
                $this->set("type",1);
                $this->set("showName","活动名称");
            }
        }
        //搜索摇一摇
        elseif($activityForm==2){
            //搜索活动名称
            if($type==1){
                $this->SearchByShark($type,$sreach);

            }elseif(in_array($type,array(2,3,4))){
                $this->SearchFromAlogs($type,2,$sreach);
            }
            else{
                $this->set("type",1);
                $this->set("showName","活动名称");
            }
        }

    }

    //通过摇一摇活动名搜索
    public  function  SearchByShark($type=1,$sreach=""){
        $name = $sreach;
        $result=$this->findActvity($name);
        if($result){
            $count=count($result);
           foreach($result as $k=>$v){
               $count=$this->ExchangeLog->find("count",array("conditions"=>array("activity_id"=>$v['id'],"exchange_type"=>2,"hit_status"=>2)));
               $result[$k]['nums']=$count;
           }
           $this->set("sreach", $result);
           $this->set("type",$type);
           $this->set("showName",$this->showName[$type]);
           $this->set("count",$count);
            $this->set("name",$name);
           $this->render("search_by_shark");
        } else{
           $this->redirect(array("action"=>"index"));
        }
    }


    //摇一摇——用户兑换商品详情
    public  function ShowFromShake(){

        //搜索的活动名称。
        $productName=trim($this->request->query("name"));

        $id=intval($this->request->query("id"));
        $export=$this->request->query("export");
        $type=$this->request->query("type")?intval($this->request->query("type")) :2;
        $sreachType=$this->request->query("sreachType")?intval($this->request->query("sreachType")):1;
        $sreach=$this->request->query("sreach")?trim($this->request->query("sreach")):"";


        if($id){
            //调用摇一摇活动接口
           $result=$this->getActivityById($id);

         // print_r($result);
            //调用接口获取奖品列表（根据活动id）
             $result1= $this->getPrizes($id);
            //print_r($result1);
            if($sreach!="" && in_array($sreachType,array(1,2)) ) {
                $this->paginate['conditions'][$this->sreachName[$sreachType] . " LIKE"] = "%" . $sreach . "%";
                $conditions[$this->sreachName[$sreachType] . " LIKE"]="%" . $sreach . "%";
                $this->set("sreach", $sreach);
                $this->set("sreachType", $sreachType);
            }
            $this->paginate['conditions']["ExchangeLog.status"]=$type;
            $this->paginate['conditions']["ExchangeLog.activity_id"]=$id;
            $this->paginate['conditions']["ExchangeLog.exchange_type"]=2;
            $this->paginate['conditions']["ExchangeLog.hit_status"]=2;
            $conditions["ExchangeLog.status"]=$type;
            $conditions["ExchangeLog.product_id"]=$id;
            $conditions["ExchangeLog.exchange_type"]=2;
            $conditions["ExchangeLog.hit_status"]=2;

            //导出数据
            if(isset($export) && !empty($export)){
                $data=$this->ExchangeLog->find("all",array("conditions"=>$conditions));
                if(!empty($data)){
                    $title="兑换详情";
                    $names=array(1=>"兑换码",2=>'用户名',3=>"手机号码",4=>"活动名称",5=>'活动状态',6=>'商品',7=>"商品状态",8=>"是否过期");
                    foreach($data as $k =>$v){
                        if(strtotime($result['startTime'])<=time() &&  strtotime($result['endTime'])>=time() ){
                            $data[$k]['ExchangeLog']['ProductStatus']="进行中" ;
                        }
                        elseif(strtotime($result['endTime']) < time() ){
                            $data[$k]['ExchangeLog']['ProductStatus']="已结束" ;
                        }
                        if($data[$k]['ExchangeLog']['status']==1){
                            if($data[$k]['Product']['validity_date'] && $data[$k]['ExchangeLog']['create_time']+$data[$k]['Product']['validity_date']<time()){
                                $data[$k]['ExchangeLog']['is_overtime']="已过期";

                            }else{
                                $data[$k]['ExchangeLog']['is_overtime']=" ";
                            }
                        }
                        $data[$k]['ExchangeLog']['status']=$this->exchangeState[ $data[$k]['ExchangeLog']['status']];
                    }
                    $this->ExportExcel($data,$names,$title);
                }
            }
            //分页查询
            $ProductLogs= $this->paginate("ExchangeLog");
            foreach($ProductLogs as $k =>$v){
                //填充活动名，活动状态
                if(!empty($result)) {
                    if (strtotime($result['startTime']) <= time() && strtotime($result['endTime']) >= time()) {
                        $ProductLogs[$k]['ExchangeLog']['Astatus'] = "进行中";
                    } elseif (strtotime($result['endTime']) < time()) {
                        $ProductLogs[$k]['ExchangeLog']['Astatus'] = "已结束";
                    }
                    $ProductLogs[$k]['ExchangeLog']['Aname'] = $result['name'];
                    $ProductLogs[$k]['ExchangeLog']['validity_time'] = $result['validity_time'];
                }
                //填充商品名
                if(!empty($result1)) {
                    foreach ($result1 as $k1 => $v1) {
                        if ($v1['id'] == $v['ExchangeLog']['prize_id']) {
                            $ProductLogs[$k]['ExchangeLog']['prizename'] = $v1['name'];
                            $ProductLogs[$k]['ExchangeLog']['prizenum']=$v1['number'];
                        }
                    }
                }
                if($v['ExchangeLog']['status']==1){
                    if($ProductLogs[$k]['ExchangeLog']['validity_time'] && ($v['ExchangeLog']['create_time']+$ProductLogs[$k]['ExchangeLog']['validity_time'])<time()){
                        $ProductLogs[$k]['ExchangeLog']['is_overtime']="已过期";
                    }else{
                        $ProductLogs[$k]['ExchangeLog']['is_overtime']="";
                    }
                }else{
                    $ProductLogs[$k]['ExchangeLog']['is_overtime']="";
                }
                unset( $ProductLogs[$k]['Product']) ;
            }
            $this->set("ProductLogs", $ProductLogs);
            $this->set("sreach", $sreach);
            $this->set("id", $id);
            $this->set("sreachType", $sreachType);
            //商品兑换状态
            $this->set("type", $type);
            //搜索查看状态。
            $this->set("type1",1);
            $this->set("productName", $productName);
        }else{
           $this->redirect("/ExchangeLogs/SearchByShark");
        }
    }


    //摇一摇兑换详情
    public  function GetShakeDetails(){

        $id=$this->request->query("id");
        $type=intval($this->request->query("type"));
        //从活动名称搜索来详情页展示 拼接放回数据
        if($type==1){
            $sreachType=$this->request->query("sreachType")?intval($this->request->query("sreachType")):1;
            $sreach=$this->request->query("sreach")?trim($this->request->query("sreach"))  :"";
            $type1=$this->request->query("type1")?trim($this->request->query("type1")):1;
            $param="type=".$type1."&sreachType=".$sreachType."&sreach=".$sreach;
            $param=addslashes($param);
        }elseif(in_array($type,array(2,3,4))){
            $sreach=trim($this->request->query("sreach"));
            $status=intval($this->request->query("status"));
            $param="/".$type."/".$status."/".$sreach;
        }

        if($id){
            $result=$this->getActivityById($id);
            $result1=$this->getPrizes($id);
            $ProductLog=$this->ExchangeLog->findById($id);
            if($ProductLog){
                $ProductLog['ExchangeLog']['start_time']=$result['startTime'];
                $ProductLog['ExchangeLog']['end_time']=$result['endTime'];
                $ProductLog['ExchangeLog']['aname']=$result['name'];
                $ProductLog['ExchangeLog']['atype']=$result['type'];
                $ProductLog['ExchangeLog']['acompany']=$result['company'];
                $ProductLog['ExchangeLog']['aprogramme']=$result['programme'];
                if($ProductLog['ExchangeLog']['create_time']+$result['validity_time']<time()){
                    $ProductLog['ExchangeLog']['is_overtime']="已过期";
                }else{
                    $ProductLog['ExchangeLog']['is_overtime']="未过期";
                }
                if($result1){
                    foreach($result1 as $k=>$v){
                        if($v['id']==$ProductLog['ExchangeLog']['prize_id']){
                            $ProductLog['ExchangeLog']['quantity']=$v['number'];
                            $ProductLog['ExchangeLog']['pname']=$v['name'];
                        }
                    }
                }
                $this->set("ProductLog",$ProductLog);
                $this->set("param",$param);
                $this->set("type",$type);
                $this->set("Atype",$this->Atype);
            }
        }else{
            $this->redirect("/ExchangeLogs/SearchFromAlogs".$param);
        }
    }


    // 商品兑换——以活动名称展示
    //$type  ：搜索类型
    //$sreach ：匹配内容
    public  function   SearchByProduct($type=1,$sreach=""){
        if($sreach!="") {
            $this->paginate['conditions'][$this->typeName[$type] . " LIKE"] = "%" . $sreach . "%";
            $condition= array(
                "conditions" => array(
                    $this->typeName[$type] . " LIKE" => "%" . $sreach . "%"
                )
            );
        }
        $this->set("sreach", $sreach);
        $this->set("type",$type);
        $this->set("showName",$this->showName[$type]);
        $count=$this->Product->find("count",$condition);
        $Product = $this->paginate("Product");
        $this->set("products",$Product);
        $this->set("count",$count);
        $this->set("statusName",$this->statusName);
        $this->render("search_by_product");

    }


    // 商品兑换——搜索兑换日志表(商品活动)
    //$type :搜索字段
    //$status ： 兑换状态
    //$sreach ：匹配内容
    //$export ：是否是导出按钮
    public  function  SearchFromLogs($type,$status=2,$sreach="",$export=''){
        $name=$this->typeName[$type];
        $condition=array();
        if($sreach!="0") {
            $condition= array(
                "conditions" => array(
                    "ExchangeLog.".$name . " LIKE" => "%" . $sreach . "%",
                )
            );
        }

        $condition["conditions"]["ExchangeLog.status"]=$status;
        $condition["conditions"]["ExchangeLog.exchange_type"]=1;
        $this->paginate['conditions']=$condition["conditions"];
        $this->set("sreach", $sreach);
        //计算符合条件总数,导出数据
        $count=$this->ExchangeLog->find("count",$condition);
        if(isset($export) && !empty($export)){

            $data=$this->ExchangeLog->find("all",array("conditions"=>$condition["conditions"]));
            if(!empty($data)){
                $title="积分商城兑换详情";
                $names=array(1=>"兑换码",2=>'用户名',3=>"手机号码",4=>"活动名称",5=>'活动状态',6=>'商品',7=>"商品状态",8=>"是否过期");
                foreach($data as $k =>$v){
                    $resultExport=$this->Product->findById($v['ExchangeLog']['product_id'],array('status',"validity_date"));
                    $data[$k]['ExchangeLog']['ProductStatus']=$this->statusName[$resultExport['Product']["status"]] ;
                    if($data[$k]['ExchangeLog']['status']==1){
                        if($resultExport['Product']['validity_date'] && $data[$k]['ExchangeLog']['create_time']+$resultExport['Product']['validity_date']<time()){
                            $data[$k]['ExchangeLog']['is_overtime']="已过期";

                        }else{
                            $data[$k]['ExchangeLog']['is_overtime']=" ";
                        }
                    }
                    $data[$k]['ExchangeLog']['status']=$this->exchangeState[ $data[$k]['ExchangeLog']['status']];
                }
                $this->ExportExcel($data,$names,$title);
            }
        }

        $ProductLogs = $this->paginate("ExchangeLog");
        foreach($ProductLogs as $k=>$v){
            $result=$this->Product->findById($v['ExchangeLog']['create_time']['product_id'],array('status',"validity_date"));
            if( $result['Product']['validity_date'] && ($v['ExchangeLog']['create_time']+$result['Product']['validity_date']) <time()){
                $ProductLogs[$k]['ExchangeLog']['is_overtime']="已过期";
            }else{
                $ProductLogs[$k]['ExchangeLog']['is_overtime']="";
            }
            $ProductLogs[$k]['ExchangeLog']['Pstatus']=$result['Product']["status"];
        }
        $this->set("productLogs",$ProductLogs);
        $this->set("count",$count);
        $this->set("type",$type);
        $this->set("search",$sreach);
        $this->set("status",$status);
        $this->set("statusName",$this->statusName);
        $this->render("search_from_logs");
    }


    // 摇一摇——搜索兑换日志表(摇一摇)
    //$type :搜索字段
    //$status ： 兑换状态
    //$sreach ：匹配内容
    //$export ：是否是导出按钮
    public  function  SearchFromAlogs($type,$status=2,$sreach="",$export=''){
        $name=$this->typeName[$type];
        $condition=array();
        //拼接查询条件
        if($sreach!="0") {
            $condition= array(
                "conditions" => array(
                    "ExchangeLog.".$name . " LIKE" => "%" . $sreach . "%",
                )
            );
        }
        $condition["conditions"]["ExchangeLog.status"]=$status;
        $condition["conditions"]["ExchangeLog.exchange_type"]=2;
        $condition["conditions"]["ExchangeLog.hit_status"]=2;

        $this->paginate['conditions']=$condition["conditions"];
        $this->set("sreach", $sreach);
        //计算符合条件总数,导出数据
        $count=$this->ExchangeLog->find("count",$condition);
        if(isset($export) && !empty($export)){

            $data=$this->ExchangeLog->find("all",array("conditions"=>$condition["conditions"]));
            if(!empty($data)){
                $title="摇一摇兑换详情";
                $names=array(1=>"兑换码",2=>'用户名',3=>"手机号码",4=>"活动名称",5=>'活动状态',6=>'商品',7=>"商品状态",8=>"是否过期");
                foreach($data as $k =>$v){
                    //调用摇一摇活动接口
                    $result=$this->getActivityById($v['ExchangeLog']['activity_id']);
                    //调用接口获取奖品列表（根据活动id）
                    $result1= $this->getPrizes($v['ExchangeLog']['activity_id']);
                    //print_r($result1);
                    if(strtotime($result['startTime']) <=time() && strtotime($result["endTime"])>=time()){
                        $data[$k]['ExchangeLog']['ProductStatus']="进行中" ;
                    }elseif(strtotime($result["endTime"])>=time()){
                        $data[$k]['ExchangeLog']['ProductStatus']="已结束" ;
                    }
                    if($data[$k]['ExchangeLog']['status']==1){
                        if( $result['validity_time'] && $data[$k]['ExchangeLog']['create_time']+$result['validity_time']<time()){
                            $data[$k]['ExchangeLog']['is_overtime']="已过期";

                        }else{
                            $data[$k]['ExchangeLog']['is_overtime']=" ";
                        }
                    }
                    $data[$k]['ExchangeLog']['status']=$this->exchangeState[ $data[$k]['ExchangeLog']['status']];
                }
                $this->ExportExcel($data,$names,$title);
            }
        }
        //查询数据
        $productLogs = $this->paginate("ExchangeLog");
        //组装数据
         if($productLogs){
            foreach($productLogs as $k=>$v){
                //调用摇一摇活动接口
                $result=$this->getActivityById($v['ExchangeLog']['activity_id']);
                //调用接口获取奖品列表（根据活动id）
                $result1= $this->getPrizes($v['ExchangeLog']['activity_id']);
                //print_r($result1);

                if($result['validity_time'] && ($v['ExchangeLog']['create_time']+$result['validity_time']) <time()){
                    $productLogs[$k]['ExchangeLog']['is_overtime']="已过期";
                }else{
                    $productLogs[$k]['ExchangeLog']['is_overtime']="";
                }
                if (strtotime($result['startTime']) <= time() && strtotime($result['endTime']) >= time()) {
                    $productLogs[$k]['ExchangeLog']['Astatus'] = "进行中";
                } elseif (strtotime($result['endTime']) < time()) {
                    $productLogs[$k]['ExchangeLog']['Astatus'] = "已结束";
                }
                $productLogs[$k]['ExchangeLog']['Aname'] = $result['name'];
                $productLogs[$k]['ExchangeLog']['validity_time'] = $result['validity_time'];
                if(!empty($result1)) {
                    foreach ($result1 as $k1 => $v1) {
                        if ($v1['id'] == $v['ExchangeLog']['prize_id']) {
                            $productLogs[$k]['ExchangeLog']['prizename'] = $v1['name'];
                            $productLogs[$k]['ExchangeLog']['prizenum']=$v1['number'];
                        }
                    }
                }

            }
         }
        $this->set("productLogs",$productLogs);
        $this->set("count",$count);
        $this->set("type",$type);
        $this->set("status",$status);
        $this->set("statusName",$this->statusName);
        $this->render("search_from_alogs");
    }
    //摇一摇——用户兑换商品
    public  function UserGetShake(){
        $id=$this->request->data('id');
        $return=array('code'=>0,"message"=>"兑换失败");
        /*
         * 检测操作用户信息是否无误。
         * */
        if($id){
            $data=$this->ExchangeLog->findById($id);

            if($data){
                $Adata=$this->getActivityById($data['ExchangeLog']['id']);
                if($data['ExchangeLog']['status']==1 && $data["ExchangeLog"]['exchange_type']==2){
                    if($Adata['validity_time']==0 || ($data['ExchangeLog']['create_time']+$Adata['validity_time']) >= time()){
                        $data1['ExchangeLog']['status']=2;
                        $data1['ExchangeLog']['modify_time']=time();
                        /*
                         * 存储操作人信息。
                         * */
                        $this->ExchangeLog->id=$id;
                        if($this->ExchangeLog->save($data1)){
                            $return['code']=200;
                            $return["message"]="兑换成功";
                        };
                    }else{
                        $return['message']="未兑换商品已过期";
                    }

                }
                else{
                    $return['message']="已兑换商品不能再次兑换";
                }
            }
            else{
                $return['message']="兑换记录不存在";
            }
        }

        $this->jsonRetrun($return);

    }
    // 商品兑换——用户兑换商品
    public function UserGetProduct(){
        $id=$this->request->data('id');
        $return=array('code'=>0,"message"=>"兑换失败");

        /*
         * 检测操作用户信息是否无误。
         * */

        if($id){
            $data=$this->ExchangeLog->findById($id);
            if($data){
                $data1=$this->Product->findById($data['ExchangeLog']['product_id']);
                if($data['ExchangeLog']['status']==1 && $data["ExchangeLog"]['exchange_type']==1){
                    if($data1["Product"]['validity_date']==0 || (($data['ExchangeLog']['create_time']+$data1["Product"]['validity_date']) >= time())){
                        $data1['ExchangeLog']['status']=2;
                        $data1['ExchangeLog']['modify_time']=time();
                        /*
                         * 存储操作人信息。
                         * */
                        $this->ExchangeLog->id=$id;
                        if($this->ExchangeLog->save($data1)){
                            $return['code']=200;
                            $return["message"]="兑换成功";
                        };
                    }else{
                        $return['message']="未兑换商品已过期";
                    }

                }
                else{
                    $return['message']="已兑换商品不能再次兑换";
                }
            }
            else{
                $return['message']="兑换记录不存在";
            }
        }

        $this->jsonRetrun($return);

    }

    // 商品兑换——用户兑换商品详情
    public  function GetProductDetails(){
        $id=$this->request->query("id");
        $type=intval($this->request->query("type"));
        //从活动名称搜索来详情页展示
        if($type==1){
            $sreachType=$this->request->query("sreachType")?intval($this->request->query("sreachType")):1;
            $sreach=$this->request->query("sreach")?trim($this->request->query("sreach"))  :"";
            $type1=$this->request->query("type1")?trim($this->request->query("type1")):1;
            $param="type=".$type1."&sreachType=".$sreachType."&sreach=".$sreach;
            $param=addslashes($param);
        }elseif(in_array($type,array(2,3,4))){
            $sreach=trim($this->request->query("sreach"));
            $status=intval($this->request->query("status"));
            $param="/".$type."/".$sreach."/".$status;
        }

        if($id){
            $ProductLog=$this->ExchangeLog->findById($id);
            if($ProductLog){
                $data=$this->Product->findById($ProductLog['ExchangeLog']['product_id']);
                $ProductLog['ExchangeLog']['start_time']=$data['Product']['start_time'];
                if($data['Product']['start_time'] && $ProductLog['ExchangeLog']['create_time']+$data['Product']['validity_date']<time()){
                    $ProductLog['ExchangeLog']['is_overtime']="已过期";
                }else{
                    $ProductLog['ExchangeLog']['is_overtime']="未过期";
                }
                $ProductLog['ExchangeLog']['quantity']= $ProductLog['Product']['quantity'];
                unset($ProductLog['Product']);
                $this->set("ProductLog",$ProductLog);
                $this->set("param",$param);
                $this->set("type",$type);
            }
        }else{
            $this->redirect("/ExchangeLogs/SearchFromLogs".$param);
        }

    }

    //通过活动名称搜索 查看商品兑换详情
    public function ShowFromProduct(){
        $conditions=array();
        //搜索活动名称。
        $aname=$this->request->query("sreach1")?trim($this->request->query("sreach1")):'';
        $id=intval($this->request->query("id"));
        $export=$this->request->query("export");
        $type=$this->request->query("type")?intval($this->request->query("type")) :2;
        $sreachType=$this->request->query("sreachType")?intval($this->request->query("sreachType")):1;
        $sreach=$this->request->query("sreach")?trim($this->request->query("sreach")):"";

        if($id){
            if($sreach!="" && in_array($sreachType,array(1,2)) ) {
                $this->paginate['conditions'][$this->sreachName[$sreachType] . " LIKE"] = "%" . $sreach . "%";
                $conditions[$this->sreachName[$sreachType] . " LIKE"]="%" . $sreach . "%";
                $this->set("sreach", $sreach);
                $this->set("sreachType", $sreachType);
            }
            $this->paginate['conditions']["ExchangeLog.status"]=$type;
            $this->paginate['conditions']["ExchangeLog.product_id"]=$id;
            $this->paginate['conditions']["ExchangeLog.exchange_type"]=1;
            $conditions["ExchangeLog.status"]=$type;
            $conditions["ExchangeLog.product_id"]=$id;
            $conditions["ExchangeLog.exchange_type"]=1;

            if(isset($export) && !empty($export)){
                $data=$this->ExchangeLog->find("all",array("conditions"=>$conditions));
                $resultExport=$this->Product->findById($id,array('status',"validity_date"));
                if(!empty($data)){
                    $title="积分商城兑换详情";
                    $names=array(1=>"兑换码",2=>'用户名',3=>"手机号码",4=>"活动名称",5=>'活动状态',6=>'商品',7=>"商品状态",8=>"是否过期");
                    foreach($data as $k =>$v){
                        $data[$k]['ExchangeLog']['ProductStatus']=$this->statusName[$resultExport['Product']["status"]] ;
                        if($data[$k]['ExchangeLog']['status']==1){
                            if($resultExport['Product']['validity_date'] && $data[$k]['ExchangeLog']['create_time']+$resultExport['Product']['validity_date']<time()){
                                $data[$k]['ExchangeLog']['is_overtime']="已过期";

                            }else{
                                $data[$k]['ExchangeLog']['is_overtime']=" ";
                            }
                        }
                        $data[$k]['ExchangeLog']['status']=$this->exchangeState[ $data[$k]['ExchangeLog']['status']];
                    }
                    $this->ExportExcel($data,$names,$title);
                }
            }
            $ProductLogs= $this->paginate("ExchangeLog");
            if($ProductLogs){
                $result1=$this->Product->findById($id,array('status',"validity_date","activity_name"));
                foreach($ProductLogs as $k =>$v){
                    $ProductLogs[$k]['ExchangeLog']['ProductStatus']=$this->statusName[$result1['Product']["status"]] ;
                    if($ProductLogs[$k]['ExchangeLog']['status']==1){
                        if( $result1['Product']['validity_date'] && $ProductLogs[$k]['ExchangeLog']['create_time']+$result1['Product']['validity_date']<time()){
                            $ProductLogs[$k]['ExchangeLog']['is_overtime']="已过期";
                        }else{
                            $ProductLogs[$k]['ExchangeLog']['is_overtime']="";
                        }
                    }
                    $ProductLogs[$k]['ExchangeLog']['status']=$this->exchangeState[ $ProductLogs[$k]['ExchangeLog']['status']];
                    unset( $ProductLogs[$k]['Product']) ;
                }
            }

            $this->set("ProductLogs", $ProductLogs);
            $this->set("sreach", $sreach);
            $this->set("sreachType", $sreachType);
            //商品兑换状态
            $this->set("type", $type);
            $this->set("aname",$aname);
            $this->set("id",$id);
            //搜索查看状态。
            $this->set("type1",1);
            $this->set("productName", $result1["Product"]['activity_name']);
        }else{
            $this->redirect("/ExchangeLogs/SearchByProduct");
        }

    }

    //导出excel表格
    /*
     * $data : 展示数据。
     * $title: 文件薄名称。
     * $names:表格头部名字。
     * */
    public  function  ExportExcel($data,$names,$title="Sheet1"){
        $title=$title.date("Ymdhis",time());
        //设置列名
        $row=array(
            1=>"A", 2=>"B", 3=>"C", 4=>"D", 5=>"E", 6=>"F", 7=>"G", 8=>"H", 9=>"I", 10=>"J", 11=>"K", 12=>"L", 13=>"M", 14=>"N",
        );
        //引入phpexcel三方类库
        App::import("vendor","Phpexcel/PHPExcel");
        App::import("vendor",'Phpexcel/PHPExcel/Writer/Excel5.php');//用于输出.xls的
        //实例化phpexcel。
        $objPHPExcel = new PHPExcel();
        //选择工作薄。
        $objPHPExcel->setActiveSheetIndex(0);
        $objWorksheet =$objPHPExcel->getActiveSheet();
        //设置文件薄名字
        $objWorksheet->setTitle($title);
        $objWorksheet->getDefaultRowDimension()->setRowHeight(20);
        //设置文件头部信息
        foreach($names as $k =>$v){
            $objWorksheet->setCellValue($row[$k]."1", $v);
            $objStyle=$objWorksheet->getStyle($row[$k]."1");
            $objStyle->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//整体垂直居中。
            $objStyle->applyFromArray(
                array(
                    'font' => array (
                        'bold' => true
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                    )
                )
            );
            $objStyle->getFont()->setName("黑体");
            $objStyle->getFont()->setSize(10);
            $objStyle->getFont()->setBold(true);
            $objWorksheet->getColumnDimension($row[$k])->setWidth(30);

        }
        //装载数据
        foreach($data as $k =>$v){
            //$objWorksheet->setCellValue($row[$k+1].($k+2), $v['Product']['id']);
            $objWorksheet->getCell($row[1].($k+2))->setValueExplicit($v['ExchangeLog']['code'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objWorksheet->setCellValue($row[2].($k+2),$v['ExchangeLog']['user_name']);
            $objWorksheet->getCell($row[3].($k+2))->setValueExplicit($v['ExchangeLog']['mobile_phone'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objWorksheet->setCellValue($row[4].($k+2),$v['ExchangeLog']['activity_name']);
            $objWorksheet->setCellValue($row[5].($k+2), $v['ExchangeLog']['ProductStatus']);
            $objWorksheet->setCellValue($row[6].($k+2), $v['ExchangeLog']['product_name']);
            $objWorksheet->setCellValue($row[7].($k+2),$v['ExchangeLog']['status']);
            $objWorksheet->setCellValue($row[8].($k+2),$v['ExchangeLog']['is_overtime']);
        }
        //设置头部信息
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //设置文件名
        header('Content-Disposition: attachment;filename="'.$title.'lsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        //建立输出通道，保存文件格式。
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    //导出数据时，判断数据是否大于1000条。
    public  function  JudgeNum(){
        $return=array('code'=>0,"message"=>"撤销失败");
        $conditions=array();
        $data="";
        $id=intval($_POST["id"]);
        $data.="id=".$id;
        if($_POST['form']==2){
            $conditions["ExchangeLog.activity_id"]=$id;
        }elseif($_POST['form']==1){
            $conditions["ExchangeLog.product_id"]=$id;
        }
        $type=$_POST["type"]?intval($_POST["type"]):2;
        $sreachType=$_POST["sreachType"]?intval($_POST["sreachType"]):1;
        $sreach=$_POST["sreach"]?trim($_POST["sreach"]):'';
        $data.="&type=".$type;
        $conditions["ExchangeLog.status"]=$type;
        if(!empty($sreach)){
            $conditions[$this->sreachName[$sreachType] . " LIKE"]="%" . $sreach . "%";
            $data.="&sreachType=".$sreachType;
            $data.="&sreach=".$sreach;
        }
        $count=$this->ExchangeLog->find("count",array("conditions"=>$conditions));
        if($count){
            if($count<=1000){
                $return['code']=200;
                $return["message"]=1;
                $return["data"]=$data;
            }else{
                $return['code']=200;
                $return["message"]=2;
                $return["data"]=$data;

            }
        }else{
            $return["message"]="没有数据";
        }
        $this->jsonRetrun($return);
        exit;
    }
    //导出数据时，判断数据是否大于1000条。
    //$type,$status=2,$sreach="",$export=''
    public  function  JudgeNumByLog(){
        $return=array('code'=>0,"message"=>"撤销失败");
        $conditions=array();
        $data="";
        //兑换状态
        $status=$_POST["status"]?intval($_POST["status"]):2;
        $type=$_POST["type"]?intval($_POST["type"]):2;
        $search=$_POST["search"]?intval($_POST["search"]):'0';
        $data.="/".$type."/".$status."/".$search."/1";
        $conditions["ExchangeLog.status"]=$status;
        if($_POST['form']==1){
            $conditions["ExchangeLog.exchange_type"]=1;
        }elseif($_POST['form']==2){
            $conditions["ExchangeLog.exchange_type"]=2;
        }
        if(!empty($sreach)){
            $conditions[$this->sreachName[$type] . " LIKE"]="%" . $sreach . "%";
        }
        $count=$this->ExchangeLog->find("count",array("conditions"=>$conditions));
        if($count){
            if($count<=1000){
                $return['code']=200;
                $return["message"]=1;
                $return["data"]=$data;
            }else{
                $return['code']=200;
                $return["message"]=2;
                $return["data"]=$data;

            }
        }else{
            $return["message"]="没有数据";
        }
        $this->jsonRetrun($return);
        exit;
    }




     //获取活动详情
    // $id : 活动id
    protected  function getActivityById($id){
        $searchUrl = SHAKE_API_URL."/dataService/getActivityById";
        APP::import('Vendor','Ccurl');
        $params = array('activityId'=>$id);
        $result = Ccurl::post($searchUrl,$params);
        $result=array(json_decode($result));
        $result=$this->ObjectToArray($result);
        if($result[0]['returnCode']==1){
            $result=$result[0]['data'];
        }
        else{
            $result=array();
        }
       return $result ;
    }
    //获取奖品列表
    //$id :活动id
    protected  function getPrizes($id){
        $searchUrl = SHAKE_API_URL."/dataService/getPrizes";
        APP::import('Vendor','Ccurl');
        $params = array('activityId'=>$id);
        $result = Ccurl::post($searchUrl,$params);
        $result=array(json_decode($result));
        $result=$this->ObjectToArray($result);
        if($result[0]['returnCode']==1){
            $result=$result[0]['data'];
        }
        else{
            $result=array();
        }
        return $result ;
    }
    //根据名称搜索活动列表

    protected  function  findActvity($activityName){
        $searchUrl = SHAKE_API_URL."/dataService/findActvity";
        APP::import('Vendor','Ccurl');
        $params = array('activityName'=>$activityName);
        $result = Ccurl::post($searchUrl,$params);
        $result=array(json_decode($result));
        $result=$this->ObjectToArray($result);
        if($result[0]['returnCode']==1){
            $result=$result[0]['data'];
        }
        else{
            $result=array();
        }
        return $result ;
    }



    //数组转换为对象
    protected  function arrayToObject($e){
        if( gettype($e)!='array' ) return;
        foreach($e as $k=>$v){
            if( gettype($v)=='array' || getType($v)=='object' )
                $e[$k]=(object)$this->arrayToObject($v);
        }
        return (object)$e;
    }


    //对象转换为数据
    protected  function ObjectToArray($e){
        $e=(array)$e;
        foreach($e as $k=>$v){
            if( gettype($v)=='resource' ) return;
            if( gettype($v)=='object' || gettype($v)=='array' )
                $e[$k]=(array)$this->ObjectToArray($v);
        }
        return $e;
    }
}