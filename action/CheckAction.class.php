<?php 
class CheckAction extends BaseAction
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

    public function repo()
    {
        $sort = G("iSortCol_0") ? G("iSortCol_0") : "id";
        $pageItems = G("iDisplayLength");
        $start = G("iDisplayStart");
        $name = G("name");
        $code = G("code");
        $color = G("color");
        $size = G("size");
        $from = G("time_form");
        $to = G("time_to");
        if ($name || $code || $color || $size || $from || $to) {
            $where = true;
        } else {
            $where = false;
        }
        $whereTimeStart = $from ? "ctime>'{$from}'" : "";
        $whereTimeEnd = $to ? "ctime<'{$to}'" : "";
        $whereName = $name ? "name='{$name}'" : "";
        $whereCode = $code ? "name='{$code}'" : "";
        $whereColor = $color ? "name='{$color}'" : "";
        $whereSize = $size ? "name='{$size}'" : "";

        $whereSql = "";
        if ($where) {
            $whereSql.=($where ? " WHERE " : "").
                    ($whereTimeStart ? "r.".$whereTimeStart : "") .
                    ($whereTimeEnd ? "r.".$whereTimeEnd:"") .
                    ($whereName ? "r.".$whereName : "") .
                    ($whereCode ? "r.".$whereCode : "") .
                    ($whereColor ? "r.".$whereColor : "").
                    ($whereSize ? "r.".$whereSize : "");
        }
        $sortMap = array('r.`name`','r.`code`','r.`color`','r.`size`','`left`','`times`','total_count');
        $sql = "SELECT GROUP_CONCAT(r.`id`) as id,
            r.`name`,
            r.`code`,
            r.`color`,
            r.`size`,
            SUM(r.`count`) as `total_count`,
            FROM_UNIXTIME(MAX(r.`ctime`)) as `times`,
            IFNULL(
                SUM(r.`count`)-
                (SELECT SUM(o.`count`) 
                FROM `mom_out_products` o 
                WHERE r.`id`=o.`repo_id` ".
                ($whereTimeStart ? "o.".$whereTimeStart : "").
                ($whereTimeEnd ? "o.".$whereTimeEnd:"").
                "),
                SUM(r.`count`)
            ) as `left`
            FROM `mom_current_repo` r ".
            $whereSql.
            " GROUP BY 
            r.`name`,
            r.`code`,
            r.`color`,
            r.`size`
            ORDER BY {$sortMap[$sort]} DESC 
            LIMIT {$start},{$pageItems}";
        $mod = M();
        $mod->query($sql);
      
        $data = $mod->getMultiResult();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $data[$k]["options"] = "";
            }
        }
        
        $sql = "SELECT COUNT(*) as `count` FROM 
                    (SELECT count(*) 
                    FROM `mom_current_repo` {$whereSql}
                    GROUP BY `name`,`code`,`color`,`size` ) as r";
        $mod->query($sql);
        $count = $mod->getSingleResult();
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $count["count"],
            "iTotalDisplayRecords" => $count["count"],
            "aaData" => $data,
        );
        echo json_encode($output);
    }

    public function out()
    {
        $pageItems = G("iDisplayLength");
        $start = G("iDisplayStart");
        $o = M();
        $data = $o->table("current_repo r,mom_out_products o,mom_forms f")->where("o.repo_id=r.id and o.form_id=f.id")
            ->limit($start,$pageItems)->select(array("f.code as fcode","r.name","r.code","f.buyer","o.price","o.count","o.ctime"),true);
        $count = $o->table("current_repo r,mom_out_products o,mom_forms f")->where("o.repo_id=r.id and o.form_id=f.id")->select(array("count(*) as count"));
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $count["count"],
            "iTotalDisplayRecords" => $count["count"],
            "aaData" => drop_keys($data),
        );
        echo json_encode($output);
    }

    public function form()
    {
        $pageItems = G("iDisplayLength");
        $start = G("iDisplayStart");
        $f = M("form");
        $data = $f->limit($start,$pageItems)->select(array("code","buyer","phone","address","ctime"),true);
        $count = $f->limit($start,$pageItems)->select(array("count(*) as count"));
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $count["count"],
            "iTotalDisplayRecords" => $count["count"],
            "aaData" => drop_keys($data),
        );
        echo json_encode($output);
    }
}
?>
