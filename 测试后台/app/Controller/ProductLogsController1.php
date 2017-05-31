<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2015/7/22
 * Time: 11:20
 */
// 商品和用户兑换类
class ProductLogsController extends AppController{
    var $name="ProductLogs";

    public $uses=array("ProductLog","Product");
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
    1=>'ProductLog.user_name',
    2=>"ProductLog.mobile_phone"
    );
     //分页条件配置
    public $paginate = array(
        'limit'=>3,
        'paramType'=>'querystring',
        'order'=>array(
            'id'=>'ASC',
            'create_time'=>'DESC'
        ),
    );


  //商品兑换——展示商品活动兑换情况情况（以活动名称，用户名，手机，兑换码）
    public  function index(){
        $type=intval($this->request->query("type"));
        $sreach=$this->request->query("sreach")?trim($this->request->query("sreach")):"";
        $export=$this->request->query("export")?$this->request->query("export"):'';
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

 // 商品兑换——以活动名称展示
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
    // 商品兑换——搜索兑换日志表
    public  function  SearchFromLogs($type,$status=2,$sreach="",$export=''){
        $name=$this->typeName[$type];
        $condition=array();
        if($sreach!="") {
            $condition= array(
                "conditions" => array(
                    "ProductLog.".$name . " LIKE" => "%" . $sreach . "%",
                )
            );
        }
        $condition["conditions"]["ProductLog.status"]=$status;
        $this->paginate['conditions']=$condition["conditions"];
        $this->set("sreach", $sreach);
        //计算符合条件总数
        $count=$this->ProductLog->find("count",$condition);
        if(isset($export) && !empty($export)){

        }
        $ProductLogs = $this->paginate("ProductLog");
        foreach($ProductLogs as $k=>$v){
            if(($v['ProductLog']['create_time']+$v['Product']['validity_date']) <time()){
                $ProductLogs[$k]['ProductLog']['is_overtime']="已过期";
            }else{
                $ProductLogs[$k]['ProductLog']['is_overtime']="";
            }
            $ProductLogs[$k]['ProductLog']['Pstatus']=$v['Product']["status"];
            unset($ProductLogs[$k]['Product']);
        }
        $this->set("productLogs",$ProductLogs);
        $this->set("count",$count);
        $this->set("type",$type);
        $this->set("status",$status);
        $this->set("statusName",$this->statusName);
        $this->render("search_from_logs");
    }
    // 商品兑换——用户兑换商品
    public function UserGetProduct(){
        $id=$this->request->data('id');

       $return=array('code'=>0,"message"=>"兑换失败");

        /*
         * 检测操作用户信息是否无误。
         * */
        if($id){
            $data=$this->ProductLog->findById($id);
            if($data){
                if($data['ProductLog']['status']==1){
                    if(($data['ProductLog']['create_time']+$data['Product']['validity_date']) >= time()){
                        $data1['ProductLog']['status']=2;
                        $data1['ProductLog']['modify_time']=time();
                        /*
                         * 存储操作人信息。
                         * */
                        $this->ProductLog->id=$id;
                        if($this->ProductLog->save($data1)){
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
           $ProductLog=$this->ProductLog->findById($id);
           if($ProductLog){
               $ProductLog['ProductLog']['start_time']=$ProductLog['Product']['start_time'];
               if($ProductLog['ProductLog']['create_time']+$ProductLog['Product']['validity_date']<time()){
                   $ProductLog['ProductLog']['is_overtime']="已过期";
               }else{
                   $ProductLog['ProductLog']['is_overtime']="未过期";
               }
               $ProductLog['ProductLog']['quantity']= $ProductLog['Product']['quantity'];
               unset($ProductLog['Product']);
               $this->set("ProductLog",$ProductLog);
               $this->set("param",$param);
               $this->set("type",$type);
           }
       }else{
           $this->redirect("/ProductLogs/SearchFromLogs".$param);
       }

   }


    //通过活动名称搜索 查看商品兑换详情
    public function ShowFromProduct(){
        $conditions=array();
        //搜索的活动名称。
      $productName=trim($this->request->query("name"));
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
          $this->paginate['conditions']["ProductLog.status"]=$type;
          $this->paginate['conditions']["ProductLog.product_id"]=$id;
          $conditions["ProductLog.status"]=$type;
          $conditions["ProductLog.product_id"]=$id;
          if(isset($export) && !empty($export)){
              $data=$this->ProductLog->find("all",array("conditions"=>$conditions));
              if(!empty($data)){
                  $title="兑换详情";
                  $names=array(1=>"兑换码",2=>'用户名',3=>"手机号码",4=>"活动名称",5=>'活动状态',6=>'商品',7=>"商品状态",8=>"是否过期");
                  foreach($data as $k =>$v){
                      $data[$k]['ProductLog']['ProductStatus']=$this->statusName[$data[$k]['Product']["status"]] ;
                      if($data[$k]['ProductLog']['status']==1){
                          if($data[$k]['ProductLog']['create_time']+$data[$k]['Product']['validity_date']<time()){
                              $data[$k]['ProductLog']['is_overtime']="已过期";

                          }else{
                              $data[$k]['ProductLog']['is_overtime']=" ";
                          }
                      }
                      $data[$k]['ProductLog']['status']=$this->exchangeState[ $data[$k]['ProductLog']['status']];
                      unset( $data[$k]['Product']) ;
                  }
               $this->ExportExcel($data,$names,$title);
              }
          }
          $ProductLogs= $this->paginate();
          foreach($ProductLogs as $k =>$v){
              $ProductLogs[$k]['ProductLog']['ProductStatus']=$this->statusName[$ProductLogs[$k]['Product']["status"]] ;
             if($ProductLogs[$k]['ProductLog']['status']==1){
                  if($ProductLogs[$k]['ProductLog']['create_time']+$ProductLogs[$k]['Product']['validity_date']<time()){
                      $ProductLogs[$k]['ProductLog']['is_overtime']="已过期";
                  }else{
                      $ProductLogs[$k]['ProductLog']['is_overtime']="";
                  }
             }
           $ProductLogs[$k]['ProductLog']['status']=$this->exchangeState[ $ProductLogs[$k]['ProductLog']['status']];
             unset( $ProductLogs[$k]['Product']) ;
          }
          $this->set("ProductLogs", $ProductLogs);
          $this->set("sreach", $sreach);
          $this->set("sreachType", $sreachType);
          //商品兑换状态
          $this->set("type", $type);
          //搜索查看状态。
          $this->set("type1",1);
          $this->set("productName", $productName);
      }else{
       $this->redirect("/ProductLogs/SearchByProduct");
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
            $objWorksheet->getCell($row[1].($k+2))->setValueExplicit($v['ProductLog']['code'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objWorksheet->setCellValue($row[2].($k+2),$v['ProductLog']['user_name']);
            $objWorksheet->getCell($row[3].($k+2))->setValueExplicit($v['ProductLog']['mobile_phone'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objWorksheet->setCellValue($row[4].($k+2),$v['ProductLog']['activity_name']);
            $objWorksheet->setCellValue($row[5].($k+2), $v['ProductLog']['ProductStatus']);
            $objWorksheet->setCellValue($row[6].($k+2), $v['ProductLog']['product_name']);
            $objWorksheet->setCellValue($row[7].($k+2),$v['ProductLog']['status']);
            $objWorksheet->setCellValue($row[8].($k+2),$v['ProductLog']['is_overtime']);
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
        $conditions["ProductLog.product_id"]=$id;
        $type=$_POST["type"]?intval($_POST["type"]):2;
        $sreachType=$_POST["sreachType"]?intval($_POST["sreachType"]):1;
        $sreach=$_POST["sreach"]?trim($_POST["sreach"]):'';
        $data.="&type=".$type;
        $conditions["ProductLog.status"]=$type;
        if(!empty($sreach)){
            $conditions[$this->sreachName[$sreachType] . " LIKE"]="%" . $sreach . "%";
            $data.="&sreachType=".$sreachType;
            $data.="&sreach=".$sreach;
        }
        $count=$this->ProductLog->find("count",array("conditions"=>$conditions));
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
        //return json_encode($return);
        exit;


    }



}