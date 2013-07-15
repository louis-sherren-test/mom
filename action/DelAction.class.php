<?php 
class DelAction extends BaseAction
{
	public function _preCall()
	{
		
	}
	
	public function _lasCall()
	{
		
	}

    public function repo()
    {
        $ids = explode(",",G("id"));
        $m = M("repo");
        foreach ($ids as $id) {
            $m->where("id='{$id}'")->delete();
        }
        echo 1;
    }
}
?>
