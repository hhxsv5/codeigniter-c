<?php
final class Cacher
{
	const CACHE_EXPIRATION_NORMAL			= 21600;//6 hours
	
	private static $_Cache = NULL;
	private static function _Init()
	{
		if(!empty(self::$_Cache)){
			return;
		}

		$ci = & get_instance();
		$ci -> load -> driver('cache', array (
				'adapter' => 'apc',
				'backup' => 'file'
		));
		self::$_Cache = $ci -> cache;
	}
	
	public static function Get($key)
	{
		self::_Init();
		return self::$_Cache -> get($key);
	}
	
	public static function Set($key, $value, $expire = self::CACHE_EXPIRATION_NORMAL)
	{
		self::_Init();
		return self::$_Cache -> save($key, $value, $expire);
	}
	
	public static function Delete($key)
	{
		self::_Init();
		return self::$_Cache -> delete($key);
	}
	
	public static function Clean()
	{
		self::_Init();
		return self::$_Cache -> clean();
	}
	
	public static function Info()
	{
		self::_Init();
		return self::$_Cache -> cache_info();
	}
	
	public static function GetMetadata($key)
	{
		self::_Init();
		return self::$_Cache -> get_metadata($key);
	}
}

final class Response
{
	const RESPONSE_CODE_SUCCESS						= 0;
	
	public static $ResponseTemplate 				= array (
			'code' => self::RESPONSE_CODE_SUCCESS, 
			'msg' => '', 
			'data' => NULL
	);

	/**
	 * Format the response to template string
	 *
	 * @param string $data
	 *        	Response data
	 * @param string $msg
	 *        	Response message
	 * @param int $code
	 *        	Service code/HTTP code
	 * @param boolean $json_encoded
	 *        	If true, will json_encode() the response
	 * @param boolean $return_array
	 *        	If true, will return the data, otherwise echo the resposne string
 	 * @param boolean $json_numeric_check
	 *        	If true, will check the numeric string by JSON_NUMERIC_CHECK
	 * @return string|NULL
	 */
	public static function FormatResponse($data = NULL, $msg = '', $code = self::RESPONSE_CODE_SUCCESS, $json_encoded = TRUE, $return_array = FALSE, $json_numeric_check = FALSE)
	{
		$res = array (
				'code' => $code, 
				'msg' => $msg, 
				'data' => $data
		);
		if ($json_encoded) {
			//$ci = &get_instance();
			//$ci -> setContentType(CI_Controller::CONTENT_TYPE_JSON);
			header('Content-Type: application/json;charset=' . config_item('charset')); // Avoid to response text/html
			if($json_numeric_check){
				$res = json_encode($res, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
			}else{
				$res = json_encode($res, JSON_UNESCAPED_SLASHES);
			}
		}
		if ($return_array) {
			return $res;
		} else {
			echo $res;
		}
		unset($res);
	}
}

final class InvalidRequestMethodException extends Exception
{
	public function __construct($method)
	{
		parent::__construct(Response::RESPONSE_MSG_INVALID_REQUEST_METHOD . ' ' . $method, Response::RESPONSE_CODE_INVALID_REQUEST_METHOD);
	}
}

final class InvalidRequestProtocolException extends Exception
{
	public function __construct($protocol)
	{
		parent::__construct(Response::RESPONSE_MSG_INVALID_REQUEST_PROTOCOL . ' ' . $protocol, Response::RESPONSE_CODE_INVALID_REQUEST_PROTOCOL);
	}
}

final class InvalidRequestParameterException extends Exception
{
	public function __construct($parameter)
	{
		parent::__construct(Response::RESPONSE_MSG_INVALID_REQUEST_PARAMETER . ' ' . $parameter, Response::RESPONSE_CODE_INVALID_REQUEST_PARAMETER);
	}
}

final class MissingRequestParameterException extends Exception
{
	public function __construct($parameter)
	{
		parent::__construct(Response::RESPONSE_MSG_MISSING_REQUEST_PARAMETER . ' ' . $parameter, Response::RESPONSE_CODE_MISSING_REQUEST_PARAMETER);
	}
}

final class Request
{
	const HTTP_METHOD_GET			= 'GET';
	const HTTP_METHOD_POST			= 'POST';
	const HTTP_METHOD_PUT			= 'PUT';
	const HTTP_METHOD_DELETE		= 'DELETE';
	const HTTP_COOKIE 				= 'COOKIE';
	
	const PROTOCOL_HTTP				= 'HTTP';
	const PROTOCOL_HTTPS			= 'HTTPS';
	
	//const PATTERN_DEVICE_ID 		= '([a-zA-Z\d%:]{8})|(0{16})';
	const PATTERN_PHONE 			= '[\+]*[\d]{0,3}1[\d]{10}';
	const PATTERN_PLATFORM			= '(ios|android|wechat)';
	const PATTERN_GENDERS			= '(M|W|U)';
	const PATTERN_ORDER				= '(desc|asc|)';
	const PATTERN_NOT_EMPTY			= '.+';
	const PATTERN_NUMBER			= '[\d]+';
	const PATTERN_FLOAT				= '(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))';
	const PATTERN_VERSION			= '\d+\.\d+\.\d+';
	const PATTERN_PASSWD			= '[a-zA-Z0-9_\-~!@#\$%\^&\*\(\)\+\|]{6,18}';
	const PATTERN_DATE				= '[\d]{4}(-|\/)(0[1-9]|1[0-2])(-|\/)(0[1-9]|[1-2]\d|3[0-1])';
	const PATTERN_DATETIME			= '[\d]{4}(-|\/)(0[1-9]|1[0-2])(-|\/)(0[1-9]|[1-2]\d|3[0-1])\s([0]\d|[1-5]\d):([0]\d|[1-5]\d):([0]\d|[1-5]\d)';
	
	public static function GetRequestMethod()
	{
		$ci = &get_instance();
		return strtoupper($ci -> input -> server('REQUEST_METHOD'));
	}
	
	public static function GetRequestProtocol()
	{
		$ci = &get_instance();
		$https = $ci -> input -> server('HTTPS');
		if(empty($https)){
			return self::PROTOCOL_HTTP;
		}
		return self::PROTOCOL_HTTPS;
	}
	
	public static function IsHttp()
	{
		return self::PROTOCOL_HTTP == self::GetRequestMethod();
	}
	
	public static function IsHttps()
	{
		return self::PROTOCOL_HTTPS == self::GetRequestMethod();
	}
	
	public static function IsAjaxRequest()
	{
		$ci = &get_instance();
		return $ci -> input -> is_ajax_request();
	}

	public static function IsGet()
	{
		return self::HTTP_METHOD_GET == self::GetRequestMethod();
	}
	
	public static function IsPost()
	{
		return self::HTTP_METHOD_POST == self::GetRequestMethod();
	}
	
	public static function IsPut()
	{
		return self::HTTP_METHOD_PUT == self::GetRequestMethod();
	}
	
	public static function IsDelete()
	{
		return self::HTTP_METHOD_DELETE == self::GetRequestMethod();
	}
	
	public static function VerifyRequestMethod($method = self::HTTP_METHOD_GET)
	{
		$requestMethod = self::GetRequestMethod();
		if($method == $requestMethod){
			return TRUE;
		}
		throw new InvalidRequestMethodException($requestMethod);
	}
	
	public static function VerifyRequestProtocol($protocol = self::PROTOCOL_HTTP)
	{
		$requestProtocol = self::GetRequestProtocol();
		if($protocol == $requestProtocol){
			return TRUE;
		}
		throw new InvalidRequestProtocolException($requestProtocol);
	}
	
	public static function Get($key, $necessary = TRUE, $default = NULL)
	{
		if(!is_string($key) || empty($key)){
			return $default;
		}
		return self::GetMatchedPatternInput($key, self::HTTP_METHOD_GET, $necessary, $default);
	}
	
	public static function Post($key, $necessary = TRUE, $default = NULL)
	{
		if(!is_string($key) || empty($key)){
			return $default;
		}
		return self::GetMatchedPatternInput($key, self::HTTP_METHOD_POST, $necessary, $default);
	}
	
	public static function Cookie($key, $necessary = TRUE, $default = NULL)
	{
		if(!is_string($key) || empty($key)){
			return $default;
		}
		return self::GetMatchedPatternInput($key, self::HTTP_COOKIE, $necessary, $default);
	}
	
	public static function GetMatchedPatternInput($key, $method = self::HTTP_METHOD_GET, $necessary = TRUE, $default = NULL)
	{
		$data = '';
		$ci = &get_instance();
		if($method == self::HTTP_METHOD_GET){
			$data = $ci -> input -> get($key);
		}elseif($method == self::HTTP_METHOD_POST){
			$data = $ci -> input -> post($key);
		}elseif($method == self::HTTP_COOKIE){
			$data = $ci -> input -> cookie($key);
		}else{
			throw new InvalidRequestMethodException($method);
		}
		
		if($data === FALSE){
			if($necessary){
				throw new MissingRequestParameterException($key);
			}else{
				return $default;
			}
		}
		
		//!!!Fixed: array to string conversion
		if(is_array($data) or is_object($data)){
			return $data;
		}
		
		$data = strval($data);
		
		$pattern = '';
		switch ($key) {
			/* case 'order':
				$pattern = self::PATTERN_ORDER;
				break; */
			default:
				break;
		}
		
		if($pattern != ''){
			if($pattern == self::PATTERN_NOT_EMPTY){
				$data = trim($data);
			}
			$pattern = '/^' . $pattern . '$/';
			if(preg_match($pattern, $data)){
				return $data;
			}else{
				throw new InvalidRequestParameterException($key . ': ' . strval($data));
			}
		}
		return $data;
	}
}

if (!function_exists('FormatResponse')) {

	/**
	 * Alias to Response::FormatResponse()
	 *
	 * @see Response::FormatResponse()
	 */
	function FormatResponse($data = NULL, $msg = '', $code = Response::RESPONSE_CODE_SUCCESS, $json_encoded = TRUE, $return_array = FALSE)
	{
		return Response::FormatResponse($data, $msg, $code, $json_encoded, $return_array);
	}
}

if (!function_exists('FormatResponseToJSON')) {

	/**
	 * Alias to Response::FormatResponse() & $json_encoded = TRUE
	 *
	 * @see Response::FormatResponse()
	 */
	function FormatResponseToJSON($data = NULL, $msg = '', $code = Response::RESPONSE_CODE_SUCCESS, $return_array = FALSE, $json_numeric_check = FALSE)
	{
		return Response::FormatResponse($data, $msg, $code, TRUE, $return_array, $json_numeric_check);
	}
}

if (!function_exists('FormatResponseWithoutChange')) {

	/**
	 * Alias to Response::FormatResponse() & $json_encoded = FALSE
	 *
	 * @see Response::FormatResponse()
	 */
	function FormatResponseWithoutChange($data = NULL, $msg = '', $code = Response::RESPONSE_CODE_SUCCESS, $return_array = FALSE)
	{
		return Response::FormatResponse($data, $msg, $code, FALSE, $return_array, FALSE);
	}
}

if (!function_exists('GetBlowfishSalt')) {

	/**
	 * Get salt for blowfish
	 * 
	 * @param string $str        	
	 * @return string
	 */
	function GetBlowfishSalt($str)
	{
		$len = strlen($str);
		$salt = '';
		for ($i = 0; $i < $len; ++$i) {
			if (preg_match('/^[a-z\d\.]$/i', $str{$i})) {//remove the "/"
				$salt .= $str{$i};
			}
		}
		$len = strlen($salt);
		if ($len > 32) {
			$salt = substr($salt, 0, 32);
		} elseif ($len < 32) {
			$salt = str_pad($salt, 32, '0');
		}
		return $salt;
	}
}

if (!function_exists('CryptByBlowfish')) {

	/**
	 * Get the crypted string by blowfish
	 * 
	 * @param string $str        	
	 * @param string $salt        	
	 * @return string
	 */
	function CryptByBlowfish($str, $salt)
	{
		$prefix = '$2a$07$' . GetBlowfishSalt($salt);
		$res = crypt($str, $prefix . '$');
		return substr($res, 28);
	}
}

if (!function_exists('XmlToArray')) {
	/**
	 * 将xml转为array
	 *
	 * @param string $xml
	 * @return array
	 */
	function XmlToArray($xml)
	{
		// 将XML转为array
		return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), TRUE);
	}
}

if (!function_exists('ArrayToXml')) {
	/**
	 * 将array转为xml
	 *
	 * @param array $arr
	 * @return string
	 */
	function ArrayToXml($arr)
	{
		$xml = '<xml>';
		foreach ($arr as $key => $val) {
			if (is_numeric($val)) {
				$xml .= '<' . $key . '>' . $val . '</' . $key . '>';
			} else
				$xml .= '<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>';
		}
		$xml .= '</xml>';
		return $xml;
	}
}

final class CURLException
{
}

final class HTTPTool
{
	private static $DefaultCurlOptions = array (
			CURLOPT_HEADER => FALSE,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_TIMEOUT_MS => 20000,
			CURLOPT_CONNECTTIMEOUT_MS => 20000
	);
	
	public static function HttpGET($url, $data = NULL, array $options = array())
	{
		$curl = curl_init();
		if (is_array($data) && count($data) > 0) {
			$url .= '?' . http_build_query($data, NULL, '&', PHP_QUERY_RFC3986);
		}
		curl_setopt($curl, CURLOPT_URL, $url);
		$options = $options + self::$DefaultCurlOptions;
		if(isset($options[CURLOPT_CAINFO])){
			$options[CURLOPT_SSL_VERIFYPEER] = TRUE;//SSL证书认证
			$options[CURLOPT_SSL_VERIFYHOST] = 2;//严格认证
		}
		curl_setopt_array($curl, $options);
		$res = curl_exec($curl);
		$errCode = curl_errno($curl);
		if ($errCode !== 0) {
			$error = curl_error($curl);
			curl_close($curl);
			throw new \CURLException($error, $errCode);
		}
		curl_close($curl);
		return $res;
	}
}

final class IPTool
{
	const IP_QUERY_API_URL_SINA			= 'http://int.dpool.sina.com.cn/iplookup/iplookup.php';
	const IP_QUERY_API_URL_TAOBAO		= 'http://ip.taobao.com/service/getIpInfo.php';
	const IP_QUERY_API_URL_SHANSING		= 'http://api.shansing.net/ip_cn.php';
	const IP_QUERY_API_URL_TELIZE		= 'http://www.telize.com/geoip';
	const IP_QUERY_API_URL_IPAPI		= 'http://ip-api.com/json';
	const IP_QUERY_API_URL_FREEGEOIP	= 'http://freegeoip.net/json';
	
	public static function GetIPLocation($ip)
	{
		$location = self::GetIPLocationFromSina($ip);
		if($location == ''){
			$location = self::GetIPLocationFromTaoBao($ip);
		}
		return $location;
	}
	
	public static function GetIPLocationFromSina($ip)
	{
		try
		{
			if (preg_match("/^(10|127|172\.16|192\.168)\./i", $ip) > 0 || $ip === 'localhost') {
				$ip = self::GetServerPublicNetworkIP();
			}
			
			$info = HTTPTool::HttpGET(self::IP_QUERY_API_URL_SINA, 
					array(
							'format' => 'json',
							'ip' => $ip
					)
			);
			$info = json_decode($info, TRUE);
			if (empty($info) || (isset($info['ret']) && $info['ret'] != 1)) {
				return '';
			}
			$province = isset($info['province']) ? $info['province'] : '';
			if(mb_strlen($province, 'UTF-8') > 2){
				$province = rtrim($province, '省');
				//!!!Fixed: 北京市北京（中国移动IP查询错误）
				if(mb_strlen($province, 'UTF-8') > 2){
					$province = rtrim($province, '市');
				}
			}
			$city = isset($info['city']) ? $info['city'] : '';
			if(mb_strlen($city, 'UTF-8') > 2){
				$city = rtrim($city, '市');
				//!!!Fixed: 北京市北京（中国移动IP查询错误）
				if(mb_strlen($city, 'UTF-8') > 2){
					$city = rtrim($city, '省');
				}
			}
			if($province == $city){
				return $province;
			}
			return $province . $city;
		}catch (\CURLException $e)
		{
			return '';
		}
	}
	
	public static function GetIPLocationFromTaoBao($ip)
	{
		try
		{
			if (preg_match("/^(10|127|172\.16|192\.168)\./i", $ip) > 0 || $ip === 'localhost') {
				$ip = self::GetServerPublicNetworkIP();
			}
				
			$info = HTTPTool::HttpGET(self::IP_QUERY_API_URL_TAOBAO,
					array(
							'ip' => $ip
					)
			);
			$info = json_decode($info, TRUE);
			if (empty($info) || (isset($info['code']) && $info['code'] != 0) || empty($info['data'])) {
				return '';
			}
			$info = $info['data'];
			$province = isset($info['region']) ? $info['region'] : '';
			if(mb_strlen($province, 'UTF-8') > 2){
				$province = rtrim($province, '省');
				//!!!Fixed: 北京市北京（中国移动IP查询错误）
				if(mb_strlen($province, 'UTF-8') > 2){
					$province = rtrim($province, '市');
				}
			}
			$city = isset($info['city']) ? $info['city'] : '';
			if(mb_strlen($city, 'UTF-8') > 2){
				$city = rtrim($city, '市');
				//!!!Fixed: 北京市北京（中国移动IP查询错误）
				if(mb_strlen($city, 'UTF-8') > 2){
					$city = rtrim($city, '省');
				}
			}
			if($province == $city){
				return $province;
			}
			return $province . $city;
		}catch (\CURLException $e)
		{
			return '';
		}
	}
	
	public static function GetServerPublicNetworkIP()
	{
		try
		{
			$info = HTTPTool::HttpGET(self::IP_QUERY_API_URL_IPAPI);
			$info = json_decode($info, TRUE);
			if (empty($info)) {
				return '0.0.0.0';
			}
			return (isset($info['query']) ? $info['query'] : '');
		}catch (\CURLException $e)
		{
			return '0.0.0.0';
		}
	}
}

final class LBSTool
{
	const ALIYUN_API_REGEOCODING	= 'http://gc.ditu.aliyun.com/regeocoding';
	const ALIYUN_API_GEOCODING		= 'http://gc.ditu.aliyun.com/geocoding';
	const ALIYUN_API_DIST_QUERY		= 'http://recode.ditu.aliyun.com/dist_query';
	
	public static function AvgLongLat()
	{
		$args = func_get_args();
		$i = 0;
		$avgLong = 0;
		$avgLat = 0;
		foreach ($args as $pos){
			if(isset($pos['lon'], $pos['lat']) && $pos['lon'] > 0 && $pos['lat'] > 0){
				$avgLong += $pos['lon'];
				$avgLat += $pos['lat'];
				++$i;
			}
		}
		if($i > 0){
			$avgLong /= $i;
			$avgLat /= $i;
		}
		return array(
				'lon' => $avgLong,
				'lat' => $avgLat
		);
	}
	
	public static function GetLongLat($address)
	{
		$ci = &get_instance();
		$ci -> load -> driver('cache', array (
				'adapter' => 'apc',
				'backup' => 'file'
		));
		$cacheKey = Cacher::CACHE_KEY_LBS_ADDRESS_PREFIX . md5($address);
		$location = $ci -> cache -> get($cacheKey);
		if(!empty($location)){
			return $location;
		}
		
		$location = HTTPTool::HttpGET(self::ALIYUN_API_GEOCODING, array(
				'a' => $address
		));
		
		$location = @json_decode($location, TRUE);
		$data = array(
				'lon' => 0,
				'lat' => 0
		);
		if($location['level'] != -1 and isset($location['lat']) and isset($location['lon'])){
			$data['lon'] = floatval($location['lon']);
			$data['lat'] = floatval($location['lat']);
		}
		$ci -> cache -> save($cacheKey, $data, Cacher::CACHE_EXPIRATION_NORMAL);
		return $data;
	}
	
	public static function GetLocation($longitude, $latitude)
	{
		$ci = &get_instance();
		$ci -> load -> driver('cache', array (
				'adapter' => 'apc', 
				'backup' => 'file'
		));
		$cacheKey = Cacher::CACHE_KEY_LBS_LOCATION_PREFIX . $longitude . '_' . $latitude;
		$location = $ci -> cache -> get($cacheKey);
		if(!empty($location)){
			return $location;
		}
		
		$location = HTTPTool::HttpGET(self::ALIYUN_API_REGEOCODING, array(
			'l' => $latitude . ',' . $longitude,
			'type' => '111' //001 (100代表道路，010代表POI，001代表门址，111可以同时显示前三项)
		));
		$locationInfo = array(
				'name' => array(),
				'city' => array(
						'province' => '',
						'name' => '',
						'code' => 0
				)
		);
		$location = @json_decode($location, TRUE);
		if(!empty($location)){
			if(isset($location['addrList']) && is_array($location['addrList'])){
				foreach ($location['addrList'] as $loc){
					if($loc['status'] == 1){
						$loc['name'] != '' && $locationInfo['name'][] = $loc['name'];
						$loc['addr'] != '' && $locationInfo['name'][] = $loc['addr'];
						
						if($locationInfo['city']['code'] == 0 && $loc['admName'] != ''){
							$tmp = explode(',', trim(trim($loc['admName']), ','));
							
							foreach ($tmp as &$val) {
								if(mb_strlen($val, 'UTF-8') > 2){
									$val = rtrim($val, '省');
								}
								if(mb_strlen($val, 'UTF-8') > 2){
									$val = rtrim($val, '市');
								}
							}
							unset($val);
							
							switch (count($tmp)) {
								case 1:
								case 2:
									$locationInfo['city']['province'] = $tmp[0];
									$locationInfo['city']['name'] = $tmp[0];
									break;
								case 3:
								case 4:
									$locationInfo['city']['province'] = $tmp[0];
									$locationInfo['city']['name'] = $tmp[1];
									break;
								default:
									break;
							}
							//行政区划编码
							$locationInfo['city']['code'] = intval(substr((string)$loc['admCode'], 0, strlen($loc['admCode']) - 3) . '100');
						}
					}
				}
			}
		}
		$ci -> cache -> save($cacheKey, $locationInfo, Cacher::CACHE_EXPIRATION_NORMAL);
		return $locationInfo;
	}
}

final class FileTool
{
	public static function CreateDir($dir, $mode = 0755)
	{
		if (!file_exists($dir)) {
			return mkdir($dir, $mode, TRUE);
		}
		return TRUE;
	}
	
	public static function Upload($srcPath, $desPath)
	{
		self::CreateDir(dirname($desPath));
		if (is_uploaded_file($srcPath)) {
			return move_uploaded_file($srcPath, $desPath) !== FALSE;
		} else {
			return rename($srcPath, $desPath);
		}
	}
	
	public static function FileExists($filename)
	{
		return file_exists($filename);
	}
	
	public static function Write($desPath, $data = '')
	{
		self::CreateDir(dirname($desPath));
		return file_put_contents($desPath, $data) !== FALSE;
	}
	
	public static function Read($filePath)
	{
		if (!self::FileExists($filePath)) {
			return FALSE;
		}
		return file_get_contents($filePath);
	}
	
	// 获取修改时间
	public static function GetMTime($filePath)
	{
		if (!self::FileExists($filePath)) {
			return FALSE;
		}
		return filemtime($filePath);
	}
	
	// 获取创建时间
	public static function GetCTime($filePath)
	{
		if (!self::FileExists($filePath)) {
			return FALSE;
		}
		return filectime($filePath);
	}
	
	public static function Delete($filename)
	{
		if (!self::FileExists($filename)) {
			return TRUE;
		}
		return @unlink($filename);
	}
}