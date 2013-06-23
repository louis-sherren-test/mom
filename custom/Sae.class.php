<?php
class Sae
{

	public static function request($url,$params=false,$post=false)
	{	
		$c = new Curl();
		$str = '';
		if($params != false && is_array($params))
		{
			$str = array();
			foreach($params as $k => $v)
				array_push($str,$k . '=' . $v);
			$str = implode('&',$str);
		}
		if($post === false)
		{
			$url = $url . '?' . $str;
			$c->execute('https',array($url));
		}
		else
		{
			$c->execute('https',array($url,$str));
		}
		$result = $c->getJson();
		return json_decode($result[0],true);
	}

	public function __construct()
	{
		$c = new Curl();
		$url = 'https://api.weibo.com/oauth2/access_token';
		$c->execute('https',array($url,C('WEIBO_CONF')));
		$result = $c->getJson();
		$jsonArr = json_decode($result[0],true);
	}
}


?>

