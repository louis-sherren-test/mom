<?php

class FormModel extends BaseModel
{
    protected $_keyMap = array();

    protected $_keys = array("id","code","buyer","phone","address","ctime","ps");

    protected $_table = "forms";
    
   	public function __construct()
   	{
   		parent::__construct();
   		$this->_default = array(
   			"ctime" => strtotime(date("Y-m-d H:i:s")),
   		);
   	}
}
