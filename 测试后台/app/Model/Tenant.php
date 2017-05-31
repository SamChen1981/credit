<?php
class Tenant
{
    public function getTenantEnv($tenantid = null){
        if(!$tenantid) return false;

        try {
            $return = $this->curlExec(TENANT_URL ,['tenantid'=>$tenantid,'secret'=>APP_SECRET,'appname'=>APP_NAME]);
            $return = json_decode($return,true);

        } catch (Exception $e) {
            return false;
        }
        if($return['returnCode'] == 0){
            return $return['returnData'];
        }else{
            return false;
        }
    }

/**
 * 利用CURL扩展POST数据
 * @param  string $uri  访问的URL
 * @param  mixed $data 传输的数据
 * @return mixed       返回的结果
 */
    private function curlExec($uri, $data){
        $postData = is_array($data)?http_build_query($data):$data;
        $postData=addslashes($postData);
        $uri .= '?'. $postData;
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