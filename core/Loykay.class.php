<?php
class Loykay
{
	public static function autoload($classname)
	{
		if(substr($classname, -6) == 'Action')
		{
			if(is_file(ACTION_PATH . $classname . '.class.php'))
			{
				require_once ACTION_PATH . $classname . '.class.php';
				return true;
			}
		}
		elseif(substr($classname,-5) == 'Model')
		{
			if(is_file(MODEL_PATH . $classname . '.class.php'))
			{
				require_once MODEL_PATH . $classname . '.class.php';
				return true;
			}
		}
		else return false;
	}
	
	public static function init()
	{
		$list = array('Loykay','Db','ErrorHandler',
		'Tpl','UI','BaseAction','BaseModel',
		'ProxyAction','Tpl','Curl','Upload');
		/*
		 * load core files.
		 */
		foreach ($list as $file)
		{
			require_once CORE_PATH . $file . '.class.php';
		}
		
		date_default_timezone_set('Asia/Shanghai');
		C(null,null,CONF_PATH . 'core.php');
		if(ON_SAE == 1)
		{
			C(null,null,CONF_PATH . 'sae.php');
		}
		/*
		 * define error and exception handlers.
		 */
	//	set_error_handler(array('ErrorHandler','captureError'));
	//	set_exception_handler(array('ErrorHandler','captureException'));
	//	register_shutdown_function(array('ErrorHandler','captureShutdown'));
		set_include_path(get_include_path() . CUSTOM_PATH);
		spl_autoload_register(array('self','autoload'));
	}
	public static function run()
	{
        global $_T;
		foreach ($_GET as $key => $value)
		{
			preg_match('/[a-zA-Z0-9_]+/', $value,$matches);
			$_T[$key] = isset($matches[0]) ? $matches[0] : "";
		}
		$mod = isset($_T['mod']) ? $_T['mod'] : 'Index';
		$act = isset($_T['act']) ? $_T['act'] : 'Index';
		$mod = ucfirst($mod);
		//$act = ucfirst($act);
		$a = new ProxyAction($mod . 'Action');
		$a->$act();
	}
}



