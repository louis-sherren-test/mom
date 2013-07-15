<?php

class RepoModel extends BaseModel
{
    protected $_keys = array("id","name","code","color","size","count","ctime","ret","base_price","wholesale_price");

    protected $_table = "current_repo";

    public function __construct()
    {
        parent::__construct();
        $this->_default = array(
            "ctime" => strtotime(date("Y-m-d H:i:s")),
            "ret" => 1,
        	"wholesale_price" => "0",
        );
    }
}
