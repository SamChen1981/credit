<?php
class RequestBase{

	//get请求方式
	const METHOD_GET  = 'get';
	//post请求方式
	const METHOD_POST = 'post';
	//接口地址
	/**
	 * 发起一个get或post请求
	 * @param $url 请求的url
	 * @param int $method 请求方式
	 * @param array $params 请求参数
	 * @param array $extra_conf curl配置, 高级需求可以用, 如
	 * $extra_conf = array(
	 *    CURLOPT_HEADER => true,
	 *    CURLOPT_RETURNTRANSFER = false
	 * )
	 * @return bool|mixed 成功返回数据，失败返回false
	 * @throws Exception
	 */
	public static function getCat($code){ 
		return self::exec($code);
	}
	public static function exec($url,$params = array(), $method = self::METHOD_GET, $extra_conf = array())
	{
		if(!empty($params['authorization'])){
			$authorization=$params['authorization'];
			unset($params['authorization']);
		}
		$params = is_array($params)? http_build_query($params): $params;
		//如果是get请求，直接将参数附在url后面
		if($method == self::METHOD_GET)
		{
			$url .= (strpos($url, '?') === false ? '?':'&') . $params;
		}
		//默认配置
		$curl_conf = array(
				CURLOPT_URL => $url,  //请求url
				CURLOPT_HEADER => ture,  //不输出头信息
				CURLOPT_RETURNTRANSFER => true, //不输出返回数据
				CURLOPT_CONNECTTIMEOUT => 10// 连接超时时间
		);
		//配置post请求额外需要的配置项
		if($method == self::METHOD_POST)
		{
			//使用post方式
			$curl_conf[CURLOPT_POST] = true;
			//post参数
			$curl_conf[CURLOPT_POSTFIELDS] = $params;
		}
		//添加额外的配置
		foreach($extra_conf as $k => $v)
		{
			$curl_conf[$k] = $v;
		}
		$data = false;
		try
		{
			//初始化一个curl句柄
			$curl_handle = curl_init();
			//设置curl的配置项
			curl_setopt_array($curl_handle, $curl_conf);
			if(!empty($authorization)){
				$header = array (
				'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0',
				'authorization:'.$authorization
				);
			}else{
				$header = array (
				'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0',
				);
			}
			curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $header); 
			//发起请求
			$data = curl_exec($curl_handle);
			if($data === false)
			{
				throw new Exception('CURL ERROR: ' . curl_error($curl_handle));
			}
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		curl_close($curl_handle);
//        var_dump($params);
//		var_dump($authorization);
//		exit;
		return $data;
	}
	public static function post($url, $fields, $timeout = NULL, $authorization=array()){
		// 开启curl_init()
		$ch = curl_init() ;
		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url) ;
		if($authorization){
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("authorization:".$authorization));
		}
		curl_setopt($ch, CURLOPT_POST, count($fields)) ; // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); // 在HTTP中的“POST”操作。如果要传送一个文件，需要一个@开头的文件名
		if ($timeout !== NULL) {
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		}

		ob_start();
		curl_exec($ch);
		$result = ob_get_contents() ;
		ob_end_clean();  //注释，可看到发送的参数

		//关闭链接
		curl_close($ch) ;
		return $result;
	}

	//下载图片
	public static function download_remote_file_with_curl($file_url, $save_to)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 0); 
		curl_setopt($ch,CURLOPT_URL,$file_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$file_content = curl_exec($ch);
		curl_close($ch);
 
		$downloaded_file = fopen($save_to, 'w');
		$re = fwrite($downloaded_file, $file_content);
		fclose($downloaded_file);
 		return $re;
	}
}
?>