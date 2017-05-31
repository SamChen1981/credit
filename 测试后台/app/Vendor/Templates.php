<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2016/7/8
 * Time: 16:28
 */
App::uses('Controller', 'Products');
App::import('Controller','Interface');
class Templates extends InterfaceController{
    public $uses = array('Product','ProductLog','ExchangeLog','ProductImg');
    public function beforeFilter(){
        $this->autoLayout = false;
    }
    public function index(){
        echo 'template';
    }
    public function makeGoodsDetail($gid=null){
        if(!empty($gid)){
            $gid=$gid;
        }elseif(isset($_GET['gid'])){
            $gid = $_GET['gid']+0;//商品id
        }else{
            echo "<script>alert('商品号不能为空');window.location.go(-1);</script>";//商品id
            exit;
        }
        $title='积分商城'.rand(1000,9999);
        $goodsDetail=$this->goodsDetail($gid);
        $goodsDetail=$this->object_to_array(json_decode($goodsDetail));
        $datas=array(
            'title'=>$title,
            'goodsId'=>$gid,
            'goodsdetail'=>$goodsDetail
        );
        $goods_statis_file = UP_DIR."/h5jifen/files/default/goods_file_".$gid.".html";//对应静态页文件
        $expr = 1;//静态文件有效期，十天
        if(file_exists($goods_statis_file)){
            $file_ctime =filectime($goods_statis_file);//文件创建时间
            if($file_ctime+$expr>time()){//如果没过期
//                ob_end_clean();
//                echo file_get_contents($goods_statis_file);//输出静态文件内容
                exit;
            }
            else{//如果已过期
                    unlink($goods_statis_file);//删除过期的静态页文件
                    ob_start();                //从数据库读取数据，并赋值给相关变量
                    include (WWW_ROOT."/h5jifen/files/goodDetail.html");//加载对应的商品详情页模板
                    $content = ob_get_contents();//把详情页内容赋值给$content变量
                    file_put_contents($goods_statis_file,$content);//写入内容到对应静态文件中
                    ob_end_clean();
                    return true;
//                    ob_end_flush();//输出商品详情页信息
                    }
             }
        else{
              ob_start();     //从数据库读取数据，并赋值给相关变量
              include (WWW_ROOT."/h5jifen/files/goodDetail.html");//加载对应的商品详情页模板
              $content = ob_get_contents();//把详情页内容赋值给$content变量
              file_put_contents($goods_statis_file,$content);//写入内容到对应静态文件中
              ob_end_clean();
              return true;
//              ob_end_flush();//输出商品详情页信息
         }
    }
    public function makeIndex($topbanner=null,$goodslist=null){        
        if(isset($_GET['uid'])){
            $uid = $_GET['uid']+0;//商品id
            $token=$_GET['token'];
        }else{

        }
        $title='积分商城';
        if($topbanner==null){
            $topbanner=$this->gettopbanner();
            $topbanner=$this->object_to_array($topbanner);
        }
        if($goodslist==null){
            $goodslist=$this->getgoodslist();
            $goodslist=$this->object_to_array($goodslist);
        }
        $datas=array(
            'title'=>$title,
            'uid'=>$uid,
            'token'=>$token,
            'topbanner'=>$topbanner,
            'goodslist'=>$goodslist
        );
        $index_statis_file=UP_DIR."/h5jifen/files/default/index.html";//对应静态页文件
        $expr = 1;//静态文件有效期，十天
        if(file_exists($index_statis_file)){
            $file_ctime =filectime($index_statis_file);//文件创建时间
            if($file_ctime+$expr>time()){//如果没过期
                return true;
                exit;
            }
            else{//如果已过期
                unlink($index_statis_file);//删除过期的静态页文件
                ob_start();                //从数据库读取数据，并赋值给相关变量
                include (WWW_ROOT.'/h5jifen/files/indexDetail.html');//加载对应的商品详情页模板
                $content = ob_get_contents();//把详情页内容赋值给$content变量
                file_put_contents($index_statis_file,$content);//写入内容到对应静态文件中
                ob_end_clean();//输出商品详情页信息
                return true;
//                ob_get_contents();
            }
        }
        else{
            ob_start();     //从数据库读取数据，并赋值给相关变量
            include (WWW_ROOT.'/h5jifen/files/indexDetail.html');//加载对应的商品详情页模板
            $content = ob_get_contents();//把详情页内容赋值给$content变量
            file_put_contents($index_statis_file,$content);//写入内容到对应静态文件中
            ob_end_clean();//输出商品详情页信息
            return true;
//            ob_get_contents();
            exit;
        }
        exit;
    }
}