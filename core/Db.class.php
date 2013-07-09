<?php
class Db{
	
	private $conn;
	private $config;
	private $isResrouce;

	public $result;
	public static $querys = array();
	public static $affectedRows;
	
	public function __construct($config = false)
	{
		if(ON_SAE == 1)
		{
			$this->config = C('SAE_DB');
		}
		else
		{
			$this->config = $config ? $config : C('DB');
		}
		$this->conn = mysql_connect($this->config['host'],$this->config['user'],$this->config['password']) or die('mysql connect failed : ' . mysql_error());
		mysql_select_db($this->config['dbname']) or die('mysql select db failed : ' . mysql_error());
		mysql_query("SET NAMES utf8;");
	}
	
	public function query($sql)
	{
		array_push(self::$querys, $sql);
		$this->result = mysql_query($sql,$this->conn);
		self::$affectedRows = mysql_affected_rows($this->conn);
		if($this->result === true)
		{
			$this->isResrouce = false;
		}
		else
		{
			$this->isResrouce = true;
		}
		if($this->result == true)
		{
			return true;
		}
		else
		{
			echo mysql_error();
			return false;
		}
	}
	
	public function getSingleResult()
	{
		$m = $this->getMultyResult();
		if($m === false)
		{
			return false;
		}
		$data = array_shift($m);
		if(empty($data))
		{
			return false;
		}
		return $data;
	}
	
	public function getMultyResult()
	{
		if($this->isResrouce !== true)
		{
			throw new Exception('can\'t get result from a none resource object!');
		}
		$data = array();
		while($v = mysql_fetch_assoc($this->result))
		{
			array_push($data,$v);
		}
		if(empty($data))
		{
			return false;
		}
		return $data;
	}
	
	public function getAffectedRows()
	{
		return mysql_affected_rows($this->conn);
	}
}
