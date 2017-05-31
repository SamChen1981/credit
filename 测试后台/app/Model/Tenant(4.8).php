<?php


class Tenant
{
    public function getTenantEnv($tenantid = null){
        if(!$tenantid) return false;
     //TENANT_URL . DS . __FUNCTION__
        try {
            $return = $this->curlExecGet(TENANT_URL,['tenantid'=>$tenantid,'appname'=>APP_NAME]);
            $return = json_decode($return,true);
        } catch (Exception $e) {
            return false;
        }
        //print_r($return);
        if($return['returnCode'] == 0){
            return $return['returnData'];
        }else{
            return $return;
        }
    }

/**
 * 利用CURL扩展POST数据
 * @param  string $uri  访问的URL
 * @param  mixed $data 传输的数据
 * @return mixed       返回的结果
 */
    private function curlExec($uri, $data){
       // $uri=$uri."getTenantEnv/";
        $postData = is_array($data)?http_build_query($data):$data;
        $uri .= '?'. $postData;
        var_dump($uri);exit;
        $ch = curl_init();

        $timeout = 5; 
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

        $output = curl_exec($ch);

        curl_close($ch) ;

        return $output;
    }
    public function curlExecGet($uri, $data){
        $uri=$uri."interface/getTenantEnv/";
        if(is_array($data)){
            foreach ($data as $key => $var) {
                $tmp[]= $key.'='.$var;
            }
            $postData = implode('&', $tmp);
        }else{
            $postData = $data;
        }
        if(strpos($uri,"?")===false){
            $uri=$uri."?".$postData;
        }else{
            $uri=$uri."&".$postData;
        }
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch) ;
        return $output;
    }
}