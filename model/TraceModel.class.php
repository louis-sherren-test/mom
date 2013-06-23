<?php

class TraceModel extends BaseModel
{
    protected $_keys = array("id","form_id","edit_time","total_price","payed_price","last_price","options");

    protected $_table = "form_trace";

    protected $_default;
    
    public function __construct($db = "") 
    {
    	parent::__construct($db);
    	$this->_default = array(
    	"edit_time" => strtotime(date("Y-m-d H:i:s")),
    	"payed_price" => 0,
    	);
    }
    
	public function trace($type,$form_id,$prices,$products,$message = '',$time = '')
    {
        $sep = "<br>";
    	if ($type == "add") {
    		$options = "增加商品：{$sep}";
    	}
        if ($type == "delete") {
            $options = "删除商品：{$sep}";
        }
        foreach ($products as $k => $v) {
            $single = $v["name"] . " " . $v["code"] . " " . $v["color"] . " " . $v["size"] . " " . "*" . $v["count"] . $sep;
            $options .= $single;
        }
    	$options = $options . $sep . $message;
    	$data = array(
    	"options" => $message,
    	"form_id" => $form_id,
    	"total_price" => $prices["total"],
    	"payed_price" => $prices["payed"],
        "last_price" => $prices["total"] - $prices["payed"],
        "edit_time" => $time,
    	);
    	$data = $this->create($data);
    	if($this->insert($data)) {
    		return true;
    	} else {
    		return false;
    	}
    }
}
