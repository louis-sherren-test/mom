<?php
class Curl
{
	private $ch;
	public $result;
	private $options;
	public static $response;
	
	/*
	 * 通用设置
	 */
	private function https($url,$post=false)
	{
		curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
		//curl_setopt($this->ch, CURLOPT_USERPWD, "username:password"); 
		curl_setopt($this->ch, CURLOPT_SSLVERSION,3); 
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 2); 
		curl_setopt($this->ch, CURLOPT_HEADER, true); 
		if($post !== false)
		{
			curl_setopt($this->ch, CURLOPT_POST, true);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
		}
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($this->ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)"); 
		curl_setopt($this->ch, CURLOPT_URL, $url);
	}

	private function http($url,$post=false)
	{
		$tmpInfo = '';   
		$cookiepath = getcwd().'./'.$cookiejar;   
		$curl = curl_init();   
		curl_setopt($curl, CURLOPT_URL, $url);   
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);   
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		if($post) {   
			$str = array();
			foreach($post as $k=>$v)
			{
				array_push($str,$k.'='.$v);
			}
			$str = implode('&',$str);
			curl_setopt($curl, CURLOPT_POST, 1);    
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post);   
		}   
		curl_setopt($curl, CURLOPT_TIMEOUT, 100);   
		curl_setopt($curl, CURLOPT_HEADER, 0);   
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   
	}
	
	private function init()
	{
		$this->ch = curl_init();
	}

	public function execute($type = false,$params=array())
	{
		$this->init();
		if(method_exists($this,$type))
		{
			call_user_func_array(array($this,$type),$params);
		}
		$this->result = curl_exec($this->ch);
		self::$response = $this->result;
		return true;
	}

	public function getJson()
	{
		$pattern = "/\{.*\}/";
		$r = preg_match($pattern,$this->result,$matches);
		if($r === false)
		{
			return false;
		}
		return $matches;
	}
	
	public function addOptions($opts)
	{
		if(!is_array($opts))
		{
			return false;
		}
		foreach($opts as $k=>$v)
		{
			curl_setopt($this->ch, $k,$v);
		}
		return true;
	}	
}


?>
