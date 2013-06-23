<?php
class ErrorHandler
{	
	public static $error = array();
	public static $warning = array();
	public static $notice = array();
	public static $exception = array();
	public static $left = array();
	
	public static function captureError( $number, $message, $file, $line )
    {
    	$e = array(
    	'line' => $line,
    	'file' => $file,
    	'message' => $message,
    	);
    	
    	if($number == 2)
    	{
    		array_push(self::$warning , $e);	
    	}
    	elseif($number == 8)
    	{
    		array_push(self::$warning , $e);
    	}
   		else 
   		{
   			array_push(self::$left , $e);
   		}
    }
    
    public static function captureException($exception)
    {
    	array_push(self::$exception, $exception->getMessage());
    }
    
    public static function captureShutdown()
    {
		$e = error_get_last();
	    array_push(self::$error,$e);
	}
	
	public static function hasError()
	{
		if(empty(self::$error) 
		&& empty(self::$exception) 
		&& empty(self::$left)
		&& empty(self::$notice)
		&& empty(self::$warning))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}