<?php
class ProxyAction
{
	private $target;
	private $pre_arguments;
	private $las_arguments;
	
	public function __construct($target,$pre_arguments = null,$bef_arguments = null)
	{
		if(!is_file(ACTION_PATH . $target . '.class.php'))
		{
			L($_SERVER['REMOTE_ADDR'] . ' tryied to get unknown action ' . $target);
			echo 'Oops!  error 404 , you got it.';
			exit;
		}
		$this->target = new $target();
		$this->pre_arguments = $pre_arguments;
		$this->bef_arguments = $bef_arguments;
	}
	
	public function __call($name , $arguments)
	{
		if(!method_exists($this->target,$name))
		{
			L($_SERVER['REMOTE_ADDR'] . ' called undefined function ' . $name . ' of ' . get_class($this->target));
			echo 'Oops!  error 404 , you got it.';
			exit;
		}
		if(method_exists($this->target, '_preCall'))
		{
			call_user_func(array($this->target,'_preCall'),$this->pre_arguments);
		}
		call_user_func(array($this->target,$name));
		if(method_exists($this->target, '_lasCall'))
		{
			call_user_func(array($this->target,'_lasCall'),$this->las_arguments);
		}
	}
}
