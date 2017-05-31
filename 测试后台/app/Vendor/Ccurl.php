<?php 
	class Ccurl{
		public static function post($url, $fields){
			$fields = Ccurl::array2Url($fields);

			//open connection
			$ch = curl_init() ;
			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $url) ;
			curl_setopt($ch, CURLOPT_POST, count($fields)) ; // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); // 在HTTP中的“POST”操作。如果要传送一个文件，需要一个@开头的文件名
		
			ob_start();
			curl_exec($ch);
			$result = ob_get_contents() ;
			ob_end_clean();
			//close connection
			curl_close($ch);
		
			return $result;
		}
		
		public static function get($url){
		
		}
		
		/**
		 * 数组参数转url字符串
		 */
		public static function array2Url($array = null) {
			$url = "";
			if (!empty($array)) {
				foreach ($array as $k => $v) {
					$url .= $k . '=' . $v . '&';
				}
				$url = substr($url, 0, -1);
			}
			return $url;
		}
	}
?>