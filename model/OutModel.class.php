<?php

class OutModel extends BaseModel
{
    protected $_keyMap = array();

    protected $_keys = array("id","repo_id","form_id","price","count","ctime");

    protected $_table = "out_products";

    protected $_default = array();
}
