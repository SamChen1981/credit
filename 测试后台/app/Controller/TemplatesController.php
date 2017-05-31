<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2016/7/8
 * Time: 16:28
 */
class TemplatesController extends AppController{
    public $uses = array('Product');
    public function index(){
        echo 'template';
        exit;
    }
    public function temp(){
        if(isset($_GET['gid'])){
            $gid = $_GET['gid']+0;//商品id
        }else{
            $gid = rand(1000,9999);//商品id
        }
        $title='积分商城'.rand(1000,9999);
        $datas=array(
            'title'=>$title,
            'goodsId'=>$gid
        );
        $goods_statis_file = "./files/default/goods_file_".$gid.".html";//对应静态页文件
        $expr = 3600*24*10;//静态文件有效期，十天
        if(file_exists($goods_statis_file)){
            $file_ctime =filectime($goods_statis_file);//文件创建时间
            if($file_ctime+$expr>time()){//如果没过期
                echo file_get_contents($goods_statis_file);//输出静态文件内容
                exit;
            }
            else{//如果已过期
                    unlink($goods_statis_file);//删除过期的静态页文件
                    ob_start();                //从数据库读取数据，并赋值给相关变量
                    include ("./files/goodDetail.html");//加载对应的商品详情页模板
                    $content = ob_get_contents();//把详情页内容赋值给$content变量
                    file_put_contents($goods_statis_file,$content);//写入内容到对应静态文件中
                    ob_end_flush();//输出商品详情页信息
                    exit;
                    }
             }
        else{
              ob_start();     //从数据库读取数据，并赋值给相关变量
              include ("./files/goodDetail.html");//加载对应的商品详情页模板
              $content = ob_get_contents();//把详情页内容赋值给$content变量
              file_put_contents($goods_statis_file,$content);//写入内容到对应静态文件中
              ob_end_flush();//输出商品详情页信息
              exit;
         }
        exit;
    }
    public function indextemp($topbanner=null,$goodslist=null){
        var_dump(1);exit;
        $data=array(
            'appKey'=>1,
            'timestamp'=>1,
            'sign'=>1,
            'tenantid'=>'test'
        );
        $data=json_encode($data);
//        App::import('controller','Interface');
//        $Interface = new Interface();
//        var_dump($Interface->curl_sgin('test.yang_jifen.com/creditshop/topBanners',$data,'get'));
        exit;
        if(isset($_GET['uid'])){
            $uid = $_GET['uid']+0;//商品id
            $token=$_GET['token'];
        }else{

        }
        $title='积分商城';
        if($topbanner==null){
            $topbanner=array();
            $data=array(
                'appKey'=>1,
                'timestamp'=>1,
                'sign'=>1
            );
            $json=json_encode($data);

        }
        if($goodslist==null){
            $goodslist=array();
        }


        $datas=array(
            'title'=>$title,
            'uid'=>$uid,
            'token'=>$token,
            'topbanner'=>$topbanner,
            'goodslist'=>$goodslist
        );
        $index_statis_file = "./files/default/index_detail.html";//对应静态页文件
        $expr = 3600*24*10;//静态文件有效期，十天
        if(file_exists($index_statis_file)){
            $file_ctime =filectime($index_statis_file);//文件创建时间
            if($file_ctime+$expr>time()){//如果没过期
                echo file_get_contents($index_statis_file);//输出静态文件内容
                exit;
            }
            else{//如果已过期
                unlink($index_statis_file);//删除过期的静态页文件
                ob_start();                //从数据库读取数据，并赋值给相关变量
                include ("./files/indexDetail.html");//加载对应的商品详情页模板
                $content = ob_get_contents();//把详情页内容赋值给$content变量
                file_put_contents($index_statis_file,$content);//写入内容到对应静态文件中
                ob_end_flush();//输出商品详情页信息
                exit;
            }
        }
        else{
            ob_start();     //从数据库读取数据，并赋值给相关变量
            include ("./files/indexDetail.html");//加载对应的商品详情页模板
            $content = ob_get_contents();//把详情页内容赋值给$content变量
            file_put_contents($index_statis_file,$content);//写入内容到对应静态文件中
            ob_end_flush();//输出商品详情页信息
            exit;
        }
        exit;
    }
}