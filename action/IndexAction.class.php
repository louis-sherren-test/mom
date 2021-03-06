<?php 
class IndexAction extends BaseAction
{
	public function _preCall()
	{
		
	}
	
	public function _lasCall()
	{
		
    }

    public function test()
    {
        $this->tpl->draw("test");
    }
	
	public function index()
    {
		$this->tpl->draw("index");
    }

    public function home()
    {
        $this->tpl->draw("home");
    }

    public function update()
    {
        $this->tpl->draw("update");
    }
    
    /* table 展示页面*/
    public function formtable()
    {
        $this->tpl->draw("form_table");
    }

    public function outtable()
    {
        $this->tpl->draw("out_table");
    }

    public function repotable()
    {
        $r = M("repo");
        $data = $r->select(array("distinct(`name`)"),true,"name");
        $this->tpl->assign("product_name",$data);
        $this->tpl->draw("repo_table");
    }
    
    /*出货界面*/
	public function out()
	{
		$form_id = strtotime(date("Y-m-d H:i:s")).rand(10000,99999);
        $r = M("repo");
        $f = M("form");
        $users = $f->select(array("distinct(`buyer`)","phone","address"),true);
        $id = 0;
        if ($users) {
            foreach ( $users as $k => $v ) {
                $users[$k]["id"] = $id;
                $users[$k]["text"] = $users[$k]["buyer"];
                unset($users[$k]["buyer"]);
                $id++;
            } 
        }
        $data = $r->select(array("distinct(`name`)"),true,"name");
        $this->tpl->assign("user_data",json_encode($users));
        $this->tpl->assign("product_name",$data);
		$this->tpl->assign("form_id",$form_id);
		$this->tpl->draw("form-out");
	}

    /*入库页面*/
    public function in()
    {
        $r = M("repo");
        $data = $r->select(array("distinct(`name`)"),true,"name");
        $this->tpl->assign("product_name",json_encode($data));
        $this->tpl->draw("form-in");
    }
    //test information
}


?>
