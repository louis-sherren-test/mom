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
        if ($r->insert($r->create())) {
            echo 1;
        } else {
            echo 0;
        }
	}
}


?>
