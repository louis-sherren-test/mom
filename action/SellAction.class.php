<?php 
class SellAction extends BaseAction
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

    public function showform()
    {
        $formid = strtotime(date("Y-m-d H:i:s")).rand(0,10).rand(0,10).rand(0,10);
        $this->tpl->assign("form_id",$formid);
        //todo:draw the form
    }

    public function getProductName()
    {
        $r = M("repo");
        $data = $r->select(array("distinct(`name`)"),true,"name");
        if (!$data) {
            echo 0;
            return;
        }
        echo json_encode($data);
    }

    public function getProductId()
    {
        $name = G("name");
        $r = M("repo");
        $data = $r->where("name='{$name}'")->select(array("distinct(`code`)"),true,"code");
        if(!$data) {
            echo 0;
            return;
        }
        echo json_encode($data);
    }

    public function getColorSize()
    {
        $name = G("name");
        $code = G("code");
        $where = "name='{$name}' and code='{$code}'";
        $r = M("repo");
        $color = $r->where($where)->select(array("distinct(`color`)"),true,"color");
        $size = $r->where($where)->select(array("distinct(`size`)"),true,"size");
        if (!$color || !$size) {
            echo 0;
            return;
        }
        $ret = array(
            "color" => $color,
            "size" => $size,
        );
        echo json_encode($ret);
    }

    public function echo1()
    {
        $a = M("repo");
        $data = $a->limit(0,20)->select(array("*"),true);
        echo $this->createDataTable(array(
                                    array("name","name_test"),
                                    array("code","code_test"),
                                    array("color","color_test"),
                                ),$data);

    }

    public function echo2()
    {
        json_encode("");
    }

    public function getCount()
    {
        $name = G("name");
        $code = G("code");
        $color = G("color");
        $size = G("size");
        $r = M("repo");
        $o = M("out");
        if ($name && !$code && !$color && !$size) {
            $ret_repo = $r->where("`name`='{$name}'")->select(array('sum(`count`) as `count`'));
            $o->query("select sum(`count`) as `count` from mom_out_products
                where repo_id in (select `id` from mom_current_repo where `name` = '{$name}')");
            $ret_out = $o->getSingleResult();
            $ret = array(
            "count" => $ret_repo["count"] - $ret_out["count"],
            );
            echo json_encode($ret);
            return;
        }

        $where = "name='{$name}' and code='{$code}'";
        $whereass = "";
        if ($color && $size) {
            $wherearr = array();
            array_push($wherearr,"color='{$color}'");
            array_push($wherearr,"size='{$size}'");
            $whereass = implode(" and ",$wherearr);
        }
        else if ($size) {
            $whereass = "size='{$size}'";
        }
        else if ($color) {
            $whereass = "color='{$color}'";
        }
        if ($whereass) {
            $where.= " and " . $whereass;
        }
        $ret_repo = $r->where($where)->group("`color`,`size`")->select(array('sum(`count`)
            as `count`,group_concat(`id`) as `id`,`size`,`color`'),true);
        $ret = $ret_repo;
        foreach ($ret_repo as $k => $v) {
            $o->query("select sum(`count`) as `count` from mom_out_products
                where repo_id in ({$v["id"]})");
            $r = $o->getSingleResult();
            $ret[$k]["count"] = $ret_repo[$k]["count"] - $r["count"];
            unset($ret[$k]["id"]);
        }
        if (!$ret) {
            echo 0;
            return;
        }
        echo json_encode($ret);
    }

    public function test()
    {
        $d = array(
            "code"=>"1111111",// 订单号
            "buyer"=>"小李", // 买家
            "phone"=>"222222222",
            "address"=>"墨西哥",
            "ps"=>"哈哈哈哈哈哈",
            "data"=>array(
                array(
                "name"=>"hahaha",
                "code"=>"33111",
                "color"=>"yellow",
                "size"=>"big",
                "count"=>"3",
                "ctime"=>"32124432",
                "ret"=>"1",
                "price"=>"34",
                ),
                array(
                "name"=>"hahaha",
                "code"=>"33111",
                "color"=>"blue",
                "size"=>"med",
                "count"=>"3",
                "ctime"=>"32124432",
                "ret"=>"1",
                "price"=>"31",
                ),
            ),
        );
        echo json_encode($d);
    }

    public function proback()
    {
        $id = G("id");
        $t = M("trace");
        $o = M("out");
        $r = M("repo");
        foreach ($id as $k => $v) {
            $data = $o->where("id='{$v}'")->select(array("repo_id","form_id","count","price"));
            $price["total"] = $data["price"] * $data["count"];
            $payed = $r->where("form_id='{$data["form_id"]}'")->order("edit_time","desc")->select(array("payed_price"));
            $price["payed"] = $payed["payed_price"];
            
            $repo = intval($data["repo_id"]);
            $type = $r->where("id='{$repo}'")->select(array("name","code","color","size"));
            $t->trace("delete",$data["form_id"],$price,$type);
            $o->where("id='{$v}'")->delete();
        }
    }

    public function submitform()
    {
        $status = 1;
        $r = M("repo");
        $o = M("out");
        $d = $_POST;
        $now = strtotime(date("Y-m-d H:i:s"));
        $total = array();
        foreach ($d["data"] as $k=>$v) {
            $where = "`name`='{$v["name"]}' and `code`='{$v["code"]}' and `color`='{$v["color"]}' and `size`='{$v["size"]}'";
            $ret_repo = $r->where($where)->select(array("sum(`count`) as `count`,group_concat(`count`) as `counts`,group_concat(`id`) as `id`"));
            $ret_out = $o->where("id in ({$ret_repo["id"]})")->select(array("sum(`count`) as `count`"));
            if ($ret_repo["count"] - $v["count"] - $ret_out["count"] <= 0) {
                echo json_encode(array(
                    "error"=>$v["name"] . $v["code"] . $v["color"] . $v["size"] . "货不足，请返回订单重新输入!",
                ));
                return;
            } else {
                $d["data"]["$k"]["real_count"] = $ret_repo["count"];
                $d["data"]["$k"]["repo_id"] = $ret_repo["id"];
                $d["data"]["$k"]["counts"] = $ret_repo["counts"];
            }
        }
        $f = M("form");
        $in = $f->create($d);
        $exists = $f->where("`code`='{$in["code"]}'")->select(array("id"));
        if ($exists) {
            $data = $f->where("`code`='{$in["code"]}'")->update($in);
            $formid = $f->where("`code`='{$in["code"]}'")->select(array("id"));
            $formid = $formid["id"];
            $type = "create";
        } else {
            $f->insert($in);
            $formid = $f->lastInsertId();
            $type = "modify";
        }
        $id = array();
        foreach ($d["data"] as $k=>$v) {
            $repo_ids = explode(",",$v["repo_id"]);
            $counts = explode(",",$v["counts"]);
            $nowCount = 0;
            $repo_id_res = array();
            for ($i=0;$i<count($repo_ids);$i++) {
                $nowCount += $counts[$i];
                if ($nowCount > $v["count"]) {
                    $repo_id_res[] = $repo_ids[$i];
                    break;
                } else {
                    $repo_id_res[] = $repo_ids[$i];
                }
            }
            $in = array(
            "count"=>$v["count"],
            "repo_id"=> implode(",",$repo_id_res),
            "price" => $v["price"],
            "form_id" => $formid,
            "ctime" => $now,
            );
            $o->insert($in);
            $id[] = $o->lastInsertId();
        }
        $t = M("trace");
        $t->trace("add",$formid,array(
        "total" => $_POST["total_price"],
        "payed" => $_POST["total_price"],
        ),$d["data"],"",$now);
        echo implode(",",$id);
    }
}


?>
