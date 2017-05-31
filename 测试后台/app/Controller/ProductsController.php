<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2015/7/22
 * Time: 9:41
 */
/*处理商品管理类*/

App::import('Vendor', 'Phpexcel/PHPExcel');
App::import('Vendor', 'Phpexcel/PHPExcel/Writer/Excel2007');
App::import('Vendor', 'Templates');
class ProductsController extends AppController{
    var $name="Products";
    //使用助手
    public $helpers = array('Form','Page');
      //对应的模型
    public $uses = array('Product','ExchangeLog','ProductImg');
    public $components = array('Session',"RequestHandler");

    //public  $types=array(1,2,3);
    public  $status=array(1,2,3,4,5);
    public  $objTemplate;
    public  $ststusName=array(
        1=>"进行中",
        2=>"已结束",
        3=>'已兑完',
        4=>'已撤销',
        5=>'草稿箱',
    );
    public $paginate = array(
        'limit'=>20,
        'paramType'=>'querystring',
        'order'=>array(
            'id'=>'DESC',
            'create_time'=>'DESC'
        ),
    );

    //对操作用户的判断
    public  $uid="0";
    public function beforeFilter () {
        parent::beforeFilter();
        App::import('Vendor', 'Templates');
        $this->objTemplte = new Templates();
//        $this->Auth->allow();
        //todo登录权限判断并得到当前用户的信息

    }

    /*商品管理——搜索结果展示*/
        public function index(){
            $conditions=array();
            //是否导出
            $export=$this->request->query("export");
            //获取搜索条件
            $this->paginate['fields']=array("id","activity_name","product_name","quantity","status","create_by","sort","rank","exchange_quantity","start_time","end_time");
            in_array($this->request->query['status'],$this->status)?($this->paginate['conditions']['status']=$this->request->query['status']):($this->paginate['conditions']['status']=1);
            $this->paginate['conditions']['status']=!empty($this->request->query['status'])?$this->request->query['status']:1;
            $this->request->query['status']= !empty($this->request->query['status'])?$this->request->query['status']:1;
            //  if(!empty($this->request->query['status'])&&$this->request->query['status']!=6){
            //      $this->paginate['conditions']['status']=$this->request->query['status'];
            // }else{
            //      $this->paginate['conditions']['status']=1;
            // }
            if($this->request->query['start_time'] ? $this->request->query['start_time'] : ''){
                $this->paginate['conditions']['start_time >='] = strtotime($this->request->query['start_time']);
            }
            if($this->request->query['end_time'] ? $this->request->query['end_time'] : ''){
                $this->paginate['conditions']['end_time   <='] = strtotime($this->request->query['end_time'])+86400;
            }
            if(isset($this->request->query['sort'])){
                if($this->request->query['sort']==0){
                        $this->paginate['conditions']['sort']=0;
                }elseif($this->request->query['sort']==1){
                        $this->paginate['conditions']['sort']=1;
                }else{

                }
            } 
            if($this->request->query['activityName']){
                $this->paginate['conditions']['activity_name LIKE'] = '%'.$this->request->query['activityName'].'%';
            }
            $this->paginate['order']='rank DESC,create_time DESC';
            $username=!empty($this->Session->read("user_info")['username'])?$this->Session->read("user_info")['username']:'admin';
            if(isset($export) && !empty($export)){
                 $data=$this->Product->find("all",array("conditions"=>$this->paginate['conditions']));
                if(!empty($data)){
                    $newData=array();
                    foreach($data as $k=>$v){
                        $newData[$k]["Product"]['id']=$v["Product"]['id'];
                        $newData[$k]["Product"]['activity_name']=$v["Product"]['activity_name'];
                        $newData[$k]["Product"]['product_name']=$v["Product"]['product_name'];
                        $newData[$k]["Product"]['exchange_quantity']=$v["Product"]['quantity']-$v["Product"]['exchange_quantity'];
                        $newData[$k]["Product"]['quantity']=$v["Product"]['quantity'];
                        $newData[$k]["Product"]['start_time']=date("Y-m-d H:i:s",$v["Product"]['start_time']);
                        $newData[$k]["Product"]['end_time']=date("Y-m-d H:i:s",$v["Product"]['end_time']);
                        $newData[$k]["Product"]['modify_time']=date("Y-m-d H:i:s",$v["Product"]['modify_time']);
                        //缺少用户接口，不能获取操作人姓名
                        $newData[$k]["Product"]['create_by']=$username;
                        $newData[$k]["Product"]['status']=$this->ststusName[$v["Product"]['status']];
                    }
                    unset($data);
                    $names=array(1=>"id",2=>'活动名称',3=>"商品",4=>"商品数量",5=>"商品库存",6=>'兑换开始',7=>"兑换结束",8=>'最新操作时间',9=>'操作人',10=>"状态");
                    $title="活动商品列表";
                    $this->ExportExcel($newData,$names,$title);
                }
            }
            $activties = $this->paginate();
            foreach($activties as $k=>&$v){
                if($v['Product']['exchange_quantity']>=$v['Product']['quantity']){
                     $data = array('id'=>$v['Product']['id'],'status'=>3);
                     $this->Product->save($data);
                     $v['Product']['status']=3;
                }elseif(time()>$v['Product']['end_time']){
                     $data = array('id'=>$v['Product']['id'],'status'=>2);
                     $this->Product->save($data);
                     $v['Product']['status']=2;
                     // unset($activties[$k]);
                }              
                $v['Product']['create_by']=$username;
            }
            $this->set('ststusName',$this->ststusName);
            $this->set('activties',$activties);
            $this->objTemplte->makeIndex();
        }
        //处理上传图片冗余问题
        private function doUrldate($data){
            $urlData=array();
            if ($data['Product']["banner_img"]!==$data['Product']["banner_img1"]) {
              $urlData[]=$data['Product']["banner_img1"];
            }
            if ($data['Product']["thumb_img"]!==$data['Product']["thumb_img1"]) {
                 $urlData[]=$data['Product']["thumb_img1"];
            }
            $result=false;
            if(count($urlData)>0){
                $this->image=$this->Components->load('Image');
                $result=$this->image->delImg($urlData);
            }
           return $result;
        }

     //商品管理——新增商品活动
    public  function  AddNewProduct(){
        if($this->request->is('POST')){
           $data=$this->request->data;
            $data['Product']["create_by"]=12;
            $data['Product']["create_time"]=time();
            $data['Product']["modify_time"]=time();
            $data['Product']['rank'] = $data['Product']['rank']?$data['Product']['rank']:0;
            //$data['Product']["accept_way"]=2;
            $data['Product']['start_time'] = $data['Product']['start_time']?strtotime($data['Product']['start_time']):time();
            $data['Product']['end_time'] = $data['Product']['end_time']?strtotime($data['Product']['end_time']):time()+30*24*3600;
            $data['Product']['order_start_time'] = $data['Product']['order_start_time']?strtotime($data['Product']['order_start_time']):0;
            $data['Product']['order_end_time'] = $data['Product']['order_end_time']?strtotime($data['Product']['order_end_time']):0;
            
            if($data['Product']['order_end_time'] < $data['Product']['start_time'] || $data['Product']['order_end_time'] ==0 || $data['Product']['order_start_time'] ==0 ){
               $data['Product']['order_end_time'] =0;
               $data['Product']['order_start_time'] =0;
            }
            //处理上传图片
            $res=$this->doUrldate($data);
             if($data['editType']==1){
                 unset($data['editType']);
                 $data['Product']['status']=1;          
                 $data['Product']["validity_date"]=intval($data['Product']["validity_date"])*86400;
             }elseif($data['editType']==2){
                 unset($data['editType']);
                 $data['Product']['status']=5;
                 $data['Product']["validity_date"]=intval( $data['Product']["validity_date"])*86400;
                 $data=$data["Product"];
                 //成功与否
                 if($pro = $this->Product->save($data)){
                    //添加商品详细图
                    if(isset($pro['Product']['img'])){ 
                            $dimg = array();
                            $dimg['ProductImg'] = array(
                                'id' => $pro['Product']['id'],
                                'count' => count($pro['Product']['img']),
                                'content' => serialize($pro['Product']['img']),
                            );
                            $this->ProductImg->save($dimg);
                    }
                     $return=array(code=>200,"message"=>"修改成功");
                     $this->jsonRetrun($return);
                     exit;
                 }else{
                     $return=array(code=>0,"message"=>"修改失败","data"=>$data);
                     $this->jsonRetrun($return);
                     exit;
                 }

             }
            $data=$data["Product"];
            $this->_saveData($data);
        }
    }

    //商品管理——撤销商品。
    public  function   revok(){
      $this->judgeIda();
      $id=$this->request->data('id');
        $return=array('code'=>0,"message"=>"撤销失败");
        if($id){
            $data=$this->Product->findById($id);
            if($data){
                if($data['Product']['status']==1){
                    $data1['Product']['status']=4;
                    $data1['Product']['modify_time']=time();
                    $this->Product->id=$id;
                    if($this->Product->save($data1)){
                        $return['code']=200;
                        $return["message"]="撤销成功";
                    };
                }
                else{
                    $return['message']="已结束，已兑完，草稿箱不能进行撤销操作";
                }
            }
            else{
                $return['message']="活动不存在";
            }
        }
        $this->jsonRetrun($return);
    }
    //商品管理——删除商品活动 单个或者多个
    public  function DeleteProducts(){
        $this->judgeIda();
        $nums=$this->request->data("id");
        $return=array("code"=>0,"message"=>"删除失败");
        if($nums){
            if(!is_array($nums)){
             $nums=array($nums);
            }
            $this->image=$this->Components->load('Image');
            foreach($nums as $k=>$num){
                $produnct=$this->Product->find("first",array("conditions"=>array("id"=>$num)));
                if($produnct){
                    //未删除
                    $data=$produnct['Product'];
                    $ProductLog=$this->ExchangeLog->find("count",array("conditions"=>array("ExchangeLog.Product_id"=>$num)));
                    if($ProductLog  > 0){
                        $data["status"]=6;
                        $this->Product->id=$num;
                        if(file_exists(WWW_ROOT."h5jifen/files/default/goods_file_".$num.".html")){
                            unlink(WWW_ROOT."h5jifen/files/default/goods_file_".$num.".html");
                        }
                        $type=$this->Product->save($data);
                    }
                    else{
                        if(file_exists(WWW_ROOT."h5jifen/files/default/goods_file_".$num.".html")){
                            unlink(WWW_ROOT."h5jifen/files/default/goods_file_".$num.".html");
                        }
                        $url=array();
                        if(!empty($data['thumb_img'])){
                           $url[]= $data['thumb_img'];
                        }
                        if(!empty($data['banner_img'])){
                           $url[]= $data['banner_img'];
                        }
                        if(count($url)){
                            $flag=$this->image->delImg($url);
                        }
                        $type=$this->Product->delete($num);
                    }
                    if($type){
                        $return["code"]=200;
                        $return['message']="删除成功";
                    }
                }
            }
        }
      $this->jsonRetrun($return);
    }
     //商品管理——查看商品活动详情
    public function ShowProductDetails(){
        $arr = parse_url($_SERVER['REQUEST_URI']);
        $id=$this->request->query['id']?$this->request->query['id']:'';
        //预览活动详情
        if(isset($this->request->query['show']) && $this->request->query['show']==1){
            $this->set("show",1);
            $data=$this->request->data;
            $data['Product']['validity_date']=$data['Product']['validity_date']*86400;
            unset($data['editType']);
            $this->set("product",$this->request->data);
        }
        //查看商品活动
        elseif($id != ''){
            $product =$this->Product->findById(intval($id));
          if(!in_array($product['Product']['status'],array(2,3))){
              $this->Session->setFlash(__('积分活动状态为不可查看'),'alert');
              $this->redirect('/Products/index?'.$arr['query']);
          }else{
              $this->set("product",$product);
              $this->set("query",$arr['query']);
          }
        }
        else{
            $this->Session->setFlash(__('积分活动为找到'),'alert');
             $this->redirect("/Products/index?".$arr['query']);
        }
    }
     //商品管理——修改商品活动详情
    public function EditProduct(){
        $id=$this->request->query['id']?$this->request->query['id']:null;
        if(!$id){
            throw new NotFoundException(__("未指定活动"));
            return $this->redirect(array("action"=>'index'));
        }
        //更新数据
        if($this->request->is("POST")){
            $this->Product->id=$id;
            $data=$this->request->data;
            unset($data["x"]);
            unset($data["y"]);
            unset($data["w"]);
            unset($data["h"]);
            //如果点击发布。

            $res=$this->doUrldate($data);
            $data['Product']['validity_date']=$data['Product']['validity_date']*86400;
            //$data['Product']['accept_way']=2;
            $data['Product']['start_time']=$data['Product']['start_time']?strtotime($data['Product']['start_time']):time();
            $data['Product']['end_time']=$data['Product']['end_time']?strtotime($data['Product']['end_time']):time()+30*24*3600;
            $data['Product']['order_start_time'] = $data['Product']['order_start_time']?strtotime($data['Product']['order_start_time']):0;
            $data['Product']['order_end_time'] = $data['Product']['order_end_time']?strtotime($data['Product']['order_end_time']):0;
            if($data['Product']['order_end_time'] < $data['Product']['start_time'] || $data['Product']['order_end_time'] ==0 || $data['Product']['order_start_time'] ==0 ){
               $data['Product']['order_end_time'] =0;
               $data['Product']['order_start_time'] =0;
            }
            if($data['editType']==1){
                unset($data['editType']);
                $data['Product']['status']=1; 
                $data['Product']['modify_time']=time();
            }
            //如果点击存草稿。(ajax)
            elseif($data['editType']==2){
                unset($data['editType']);
                $data['Product']['status']=5;
                $data['Product']['start_time']=0;
                $data['Product']['modify_time']=time();
                //如果领取方式为快递
                if($data['Product']['accept_way']==2){
                    $data['Product']['accept_time']='';
                    $data['Product']['accept_time_desc']='';
                    $data['Product']['link_man']='';
                    $data['Product']['link_phone']='';
                    $data['Product']['accept_addr']='';
                }
                //成功与否
                if($this->Product->save($data)){
                    //添加商品详细图
                    if(isset($data['Product']['img'])){
                        $dimg = array();
                        $dimg['ProductImg'] = array(
                            'count' => count($data['Product']['img']),
                            'content' => serialize($data['Product']['img']),
                        );
                        if($this->ProductImg->findById($id)){
                            $this->ProductImg->id = $id;
                            $this->ProductImg->save($dimg);
                        }else{
                            $dimg['ProductImg']['id']=$id;
                            $this->ProductImg->save($dimg);
                        }

                    }
                    $return=array(code=>200,"message"=>"修改成功");
                    $this->jsonRetrun($return);
                    exit;

                }else{
                    $return=array(code=>0,"message"=>"修改失败","id"=>$id);
                    $this->jsonRetrun($return);
                    exit;
                }
            }
            //如果领取方式为快递
            if($data['Product']['accept_way']==2){
                $data['Product']['accept_time']='';
                $data['Product']['accept_time_desc']='';
                $data['Product']['link_man']='';
                $data['Product']['link_phone']='';
                $data['Product']['accept_addr']='';
            }
            //更新成功
            if($this->Product->save($data)){
                //添加商品详细图
                $productimg=$this->ProductImg->findById($id);
                if(isset($data['Product']['img'])){
                    $dimg = array();
                    $dimg['ProductImg'] = array(
                        'count' => count($data['Product']['img']),
                        'content' => serialize($data['Product']['img']),
                    );
                    if($productimg){
                        $this->ProductImg->id = $id;
                        $this->ProductImg->save($dimg);
                    }else{
                        $dimg['ProductImg']['id']=$id;
                        $this->ProductImg->save($dimg);
                    }
                }else{
                    if($productimg){
                        $dimg['ProductImg'] = array(
                            'count' => 0,
                            'content' => '',
                        );
                        $dimg['ProductImg']['id']=$id;
                        $this->ProductImg->save($dimg);
                    }
                }
                App::import('Vendor', 'Templates');
                $objTemplte = new Templates();
                $objTemplte->makeGoodsDetail($id);
                $this->Session->setFlash(__("修改成功"));
                return $this->redirect(array("action"=>'index'));
            }
            //更新失败
            else{
                $this->Session->setFlash(__("修改失败"));
                $this->redirect("/Product/EditProduct?id=".$id);
            }
        }
        //跳转到编辑页面
        if(!$this->request->data){
            $this->set("Productid",$id);
            $this->set("prodateDetails",$this->Product->findById($id));
            $imgs = $this->ProductImg->find('first',array('conditions'=>array('id'=>$id)));
            $this->set("proImgs",unserialize($imgs['ProductImg']['content']));
        }
    }
  //商品管理——发布商品活动
    public function ReleaseProduct(){
        $nums=$this->request->data("id");
        $return=array("code"=>0,"message"=>"删除失败");
        if($nums){
            if(!is_array($nums)){
                $nums=array($nums);
            }
            foreach($nums as $k=>$num){
                $product=$this->Product->find("first",array("conditions"=>array("id"=>$num)));
                if($product){
                    if($product["Product"]["status"]==4){
                        $this->Product->id=$num;
                        $product["Product"]["status"]=1;
                        $product["modify_time"]=time();
                        $type=$this->Product->save($product);
                        if($type){
                            $return["code"]=200;
                            $return["message"]="发布成功";
                        }
                    }else{
                        $return["message"]="已发布，已对完，已结束等不能发布";
                    }
                }else{
                    $return["message"]="数据不存在";
                }
            }
        }
        echo json_encode($return);
    }


    //导出excel表格
    /*
     * $data : 展示数据。
     * $title: 文件薄名称。
     * $names:表格头部名字。
     * */
    public  function  ExportExcel($data,$names,$title="Sheet1"){
        $this->judgeIda();
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
            $objWorksheet->getCell($row[1].($k+2))->setValueExplicit($v['Product']['id'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objWorksheet->setCellValue($row[2].($k+2),$v['Product']['activity_name']);
            $objWorksheet->setCellValue($row[3].($k+2),$v['Product']['product_name']);
            $objWorksheet->getCell($row[4].($k+2))->setValueExplicit($v['Product']['quantity'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objWorksheet->getCell($row[5].($k+2))->setValueExplicit($v['Product']['exchange_quantity'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objWorksheet->setCellValue($row[6].($k+2), $v['Product']['start_time']);
            $objWorksheet->getStyle($row[6].($k+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $objWorksheet->setCellValue($row[7].($k+2), $v['Product']['end_time']);
            $objWorksheet->getStyle($row[7].($k+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $objWorksheet->setCellValue($row[8].($k+2), $v['Product']['modify_time']);
            $objWorksheet->getStyle($row[8].($k+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $objWorksheet->setCellValue($row[9].($k+2),$v['Product']['create_by']);
            $objWorksheet->setCellValue($row[10].($k+2),$v['Product']['status']);
        }
        //设置头部信息
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       //设置文件名
        header('Content-Disposition: attachment;filename="'.$title.'.xls"');
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

    //保存活动内容
    public function _saveData($data){
        if($pro = $this->Product->save($data)){
            //添加商品详细图
            if(isset($pro['Product']['img'])){ 
                    $dimg = array();
                    $dimg['ProductImg'] = array(
                        'id' => $pro['Product']['id'],
                        'count' => count($pro['Product']['img']),
                        'content' => serialize($pro['Product']['img']),
                    );
                    $this->ProductImg->save($dimg);
            }
            $this->Session->setFlash(__('添加成功'),'alert');
            $gid=$this->Product->getInsertID();
            App::import('Vendor', 'Templates');
            $objTemplte = new Templates();
            $objTemplte->makeGoodsDetail($gid);
            $this->redirect(array("action"=>"index"));
        }else{
            $this->Session->setFlash(__('添加失败'),'alert');
            $data['Product']['validity_date']=$data['Product']['validity_date']/86400;
            $this->set("prodateDetails",$data);
            $this->render("AddNewProduct.ctp");
        }
    }

    private  function convertUTF8($str)
    {
       if(empty($str)) return '';
       return  iconv('utf-8', 'gb2312', $str);
    }
     //导出数据时，判断数据是否大于1000条。
    public  function  JudgeNum(){
        $return=array('code'=>0,"message"=>"撤销失败");
        $conditions=array();
        $data="";
        $status=$_POST["status"]?intval($_POST["status"]):1;
        $data.="status=".$status;
        $start_time=$_POST["start_time"];
        $end_time=$_POST['end_time'];
        $activityName=trim($_POST['activityName']);
        $conditions["status"]=$status;
        $sort=$_POST['sort'];
        if(isset($sort)){
            $conditions['sort >=']=$sort;
            $data.="&sort=".$sort;
        }
        if(isset($start_time)){
            $conditions['start_time >=']=strtotime($start_time);
            $data.="&start_time=".$start_time;
        }
        if(isset($end_time)){
            $conditions['end_time   <=']=strtotime($end_time);
            $data.="&end_time=".$end_time;
        }
        if(isset($activityName) && !empty($activityName)){
            $conditions['activityName  LIKE']="%".$activityName."%";
            $data.="&activityName=".$activityName;
        }
        $count=$this->Product->find("count",array("conditions"=>$conditions));
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
    }

    //上传图片
    public function  upload(){
        $this->judgeIda();
        if (!$_FILES['upload_file']) {
            die ( 'Image data not detected!' );
        }
        if ($_FILES['upload_file']['error'] > 0) {
            switch ($_FILES ['upload_file'] ['error']) {
                case 1 :
                    $error_log = 'The file is bigger than this PHP installation allows';
                    break;
                case 2 :
                    $error_log = 'The file is bigger than this form allows';
                    break;
                case 3 :
                    $error_log = 'Only part of the file was uploaded';
                    break;
                case 4 :
                    $error_log = 'No file was uploaded';
                    break;
                default :
                    break;
            }
            die ( 'upload error:' . $error_log );
        } else {
            $img_data = $_FILES['upload_file']['tmp_name'];
            $size = getimagesize($img_data);
            $file_type = $size['mime'];
            if (!in_array($file_type, array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'))) {
                $error_log = 'only allow jpg,png,gif';
                die ( 'upload error:' . $error_log );
            }
            switch($file_type) {
                case 'image/jpg' :
                case 'image/jpeg' :
                case 'image/pjpeg' :
                    $extension = 'jpg';
                    break;
                case 'image/png' :
                    $extension = 'png';
                    break;
                case 'image/gif' :
                    $extension = 'gif';
                    break;
            }
        }
        if (!is_file($img_data)) {
            die ( 'Image upload error!' );
        }
        //图片保存路径,默认保存在该代码所在目录(可根据实际需求修改保存路径)
        $save_path = dirname( __FILE__ );
        $uinqid = uniqid();
        $filename = $save_path . '/' . $uinqid . '.' . $extension;
        $result = move_uploaded_file( $img_data, $filename );
        if ( ! $result || ! is_file( $filename ) ) {
            die ( 'Image upload error!' );
        }
        echo json_encode('Image data save successed,file:' . $filename);
        exit ();
    }


    public  function judgeIda(){
        $params=$this->request->params;
        $tenantid=$this->get_tenantid();
        $user_info=$this->Session->read("user_info");
        $uid_nmc=Cache::read($tenantid."_".$user_info['uid'].$user_info['token'],"_creditshop_");
        //验证模块权限
        if(@!in_array(strtolower($params['action']),$this->array_lower($uid_nmc['appPermission']))){
            die("用户没有权限,请联系管理员");
        }
    }

}