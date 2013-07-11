<?php 
class RepoAction extends BaseAction
{
	public function _preCall()
	{
		
	}
	
	public function _lasCall()
	{
		
	}
	
	public function index()
    {
		$this->tpl->draw("index");
    }

    public function update()
    {
        $this->tpl->draw("update");
    }

	public function insert()
    {
    	$r = M("repo");
    	foreach ($_POST["post"] as $k => $v) {
    		$res = $r->insert($r->create($v));
    		if (!$res) {
    			echo 0;
    			break;
    		}
    	}
        
	}
}


?>
