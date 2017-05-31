<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2016/7/6
 * Time: 10:56
 */
App::import('Model', 'Read');
class TestsController extends AppController{
    public function file($counts=10000,$type='read'){
        $type =$_GET['type'];
        $counts=$_GET['counts'];
        echo $counts."次--";
        echo "开始时间:".time();
        $starttime=time();
        $str='测试';
        file_put_contents('./files/test.txt','');
        if($type==='write'){
            for($i=0;$i<$counts;$i++){
                file_put_contents('./files/test.txt',$str, FILE_APPEND);
            }
        }
        if($type==='read'){
            for($i=0;$i<$counts;$i++){
                $myfile = fopen("./files/read.txt", "r") or die("Unable to open file!");
                fgets($myfile);
                fclose($myfile);
            }
        }
        echo "结束时间".time()."--";
        $endtime=time();
        echo "时间差:".($endtime-$starttime);
        $logs= $type."   文件 ".$counts."次--"."开始时间:".time()."结束时间".time()."--"."时间差:".($endtime-$starttime)."     ".date('Y-m-d H:i:s')."\r\n";
        file_put_contents('./files/logs.txt',$logs, FILE_APPEND);
        exit;
    }
    public function redis($counts=10000,$type='read'){
        set_time_limit(0);
        $type =$_GET['type'];
        $counts=$_GET['counts'];
        $redis=new redis();
        $redis->connect('127.0.0.1');
        echo $counts."次--";
        echo "开始时间:".time();
        $starttime=time();
        if($type==='write'){
            for($k=0;$k<100;$k++){
                for($i=0;$i<$counts;$i++){
//                    if($i==0){
//                        $redis->set("key","测试");
//                    }
                    $redis->append('key', '测试');
                }
            }
        }
        $str="读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读读";
        if($type==='read'){
            $redis->set("read",$str);
            for($i=0;$i<$counts;$i++){
                $redis->get("key");
            }
        }
//        $redis->del("read");
//        $redis->del("key");
        $redis->close();
        echo "结束时间".time()."--";
        $endtime=time();
        echo "时间差:".($endtime-$starttime);
        $logs= $type."  redis ".$counts."次--"."开始时间:".time()."结束时间".time()."--"."时间差:".($endtime-$starttime)."     ".date('Y-m-d H:i:s')."\r\n";
        file_put_contents('./files/logs.txt',$logs, FILE_APPEND);
        exit;
    }

    public function mysql($content=null) {
        ini_set('memory_limit','256M');
        set_time_limit(0);//永不超时
        $type =$_GET['type'];
        $counts=$_GET['counts'];
        $str='测试';
        echo $counts."次--";
        echo "开始时间:".time();
        $starttime=time();
        $this->Test->create();
        if($type==='write'){
            $values='';
//            for($k=0;$k<12;$k++){
                for($i=0;$i<$counts;$i++){
                    $values.="('$str'),";
                }
                $sql="insert into operation_reads(content)values".$values;
                $sql=substr($sql,0,strlen($sql)-1);
                unset ($values);
                $this->Test->query("$sql");
//            }
        }
        if($type==='read'){
            for($i=0;$i<$counts;$i++){
                  $this->Test->query("select id,content from operation_reads");
            }
        }
        echo "结束时间".time()."--";
        $endtime=time();
        echo "时间差:".($endtime-$starttime);
        $logs= $type."  mysql ".$counts."次--"."开始时间:".time()."结束时间".time()."--"."时间差:".($endtime-$starttime)."     ".date('Y-m-d H:i:s')."\r\n";
        file_put_contents('./files/logs.txt',$logs, FILE_APPEND);
        exit;
    }
    public function show(){
        $result = file_get_contents("http://www.kuaidi100.com/query?type=yuantong&postid=881443775034378914&id=1&valicode=&temp=0.19689508604579842");
        $data = json_decode($result);
        echo $result;
        exit;
    }
    public function redis(){
        echo 1;
    }
}