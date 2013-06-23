<?php
abstract class BaseAction extends UI
{
	abstract function _preCall();
	abstract function _lasCall();
    protected $tpl;
    protected $mod;
	
	public function __construct()
    {
        $this->tpl = new libTpl();
        $this->mod = new BaseModel();
        $this->tpl->assign("ROOT_PATH",WEB_ROOT);
    }

    public function alert()
    {
        
    }
}
