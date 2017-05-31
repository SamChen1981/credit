<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2015/7/29
 * Time: 16:52
 */


/*效果统计类*/
class ResultStatisticsController extends AppController{
    public  $uses=array("Product",'ExchangeLog');

    public  $status=array(1,2,3);
    public  $statusName=array(
        1=>"进行中",
        2=>"已结束",
        3=>'已兑完',
    );

    public $paginate = array(
        'limit'=>20,
        'paramType'=>'querystring',
        'order'=>array(
            'id'=>'DESC',
            'create_time'=>'DESC'
        ),
    );

    //效果统计——活动列表页展示
    public function index(){
        $status = $this->request->query['status'] ? $this->request->query['status'] : 1;
        $sreach = $this->request->query('sreach')?trim($this->request->query('sreach')):'';
        if($status != 0 ){
            if(in_array($status,$this->status)){
                $this->paginate['conditions']['Product.status'] = $status;
                $this->set('status',$status);
                $this->set('statusName1',$this->statusName[$status]);
            }
        }
        if($sreach){
            $this->paginate['conditions']['Product.activity_name LIKE'] = '%'.$sreach.'%';
            $this->set('sreach',$sreach);
        }
        $activties = $this->paginate("Product");
        $this->set('activties',$activties);
        $this->set("statusName",$this->statusName);
    }


    //效果统计——展示活动会员参与详情
    public  function ShowParticipationDetails(){
        $arr = parse_url($_SERVER['REQUEST_URI']);
        $id=intval($this->request->query("id"));
        $export=$this->request->query("export");
        $sreach=$this->request->query("sreach")?trim($this->request->query("sreach")):"";
        if($id){
            if($sreach!=""){
                $this->paginate['conditions']['OR']['ExchangeLog.user_name LIKE'] ="%".$sreach."%";
                $this->paginate['conditions']['OR']['ExchangeLog.mobile_phone LIKE'] ="%".$sreach."%";
                $this->set("sreach",$sreach);
                $conditions=array("OR"=>array(
                    "ExchangeLog.user_name LIKE"=>"%".$sreach."%",
                    "ExchangeLog.mobile_phone LIKE"=>"%".$sreach."%"
                ));
            }
            $this->paginate['conditions']['AND']['ExchangeLog.product_id'] =$id;
            $this->paginate['conditions']['AND']['ExchangeLog.exchange_type'] =1;
            $conditions['AND']=array("ExchangeLog.product_id"=>$id);
            $conditions['AND']=array("ExchangeLog.exchange_type"=>1);
            if(isset($export) && !empty($export)){
                $data=$this->ExchangeLog->find("all",array("conditions"=>$conditions));
                if(!empty($data)){
                    foreach($data as $k=>$v){
                        $data[$k]['ExchangeLog']['create_time']=date("Y-m-d H:i:s",$v['ExchangeLog']['create_time']);
                        unset($data[$k]['Product']);
                    }
                    $names=array(1=>"id",2=>'用户名',3=>"手机号码",4=>"参与时间",5=>'兑换商品',6=>'花费积分');
                    $title="活动明细";
                    $this->ExportExcel($data,$names,$title);
                }
            }
            $users = $this->paginate("ExchangeLog" );

            $Product=$this->Product->findById($id,array('member_nums','participation_nums'));
            $this->set("member_nums",$Product['Product']['member_nums']);
            $this->set("participation_nums",$Product['Product']['participation_nums']);
            if($users['0']['Product']['member_nums']!=0){
                $this->set("num",floor($users['0']['Product']['participation_nums']/$users['0']['Product']['member_nums']));
            }else{
                $this->set("num",0);
            }
            $this->set("activity_name",$users['0']['ExchangeLog']['activity_name']);
            $this->set("id",$id);
            foreach($users as $k=>$v){
               unset($users[$k]['Product']);
            }
            $this->set("query",$arr['query']);
            $this->set('users',$users);
        }
        else{
            $this->redirect(array("action"=>"index"));
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
            $objWorksheet->getColumnDimension($row[$k])->setWidth(25);

        }
        //装载数据
        foreach($data as $k =>$v){
            //$objWorksheet->setCellValue($row[$k+1].($k+2), $v['Product']['id']);
            $objWorksheet->getCell($row[1].($k+2))->setValueExplicit($v['ExchangeLog']['id'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objWorksheet->setCellValue($row[2].($k+2),$v['ExchangeLog']['user_name']);
            $objWorksheet->getCell($row[3].($k+2))->setValueExplicit($v['ExchangeLog']['mobile_phone'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objWorksheet->setCellValue($row[4].($k+2), $v['ExchangeLog']['create_time']);
            $objWorksheet->getStyle($row[4].($k+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $objWorksheet->setCellValue($row[5].($k+2),$v['ExchangeLog']['product_name']);
            $objWorksheet->getCell($row[6].($k+2))->setValueExplicit($v['ExchangeLog']['credits'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
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
        $sreach=$_POST["sreach"];
        $data.="id=".$id;
        $conditions["AND"]["ExchangeLog.product_id"]=$id;
        if(isset($sreach)){
            $conditions['OR']['ExchangeLog.user_name LIKE']="%".$sreach."%";
            $conditions['OR']['ExchangeLog.mobile_phone LIKE']="%".$sreach."%";
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
        //return json_encode($return);
        exit;


    }



}