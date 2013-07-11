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
        $name = $this->filter(G("name"));
        $code = $this->filter(G("code"));
        $color = $this->filter(G("color"));
        $size = $this->filter(G("size"));
        
        $from = $this->filter(G("time_form"));
        $to = $this->filter(G("time_to"));

        if ($name || $code || $color || $size || $from || $to) {
            $where = true;
        } else {
            $where = false;
        }
        if ($from = strtotime($from)) {
        	$whereTimeStart = $from ? "ctime>'{$from}' " : "";
        }
        if ($to = strtotime($to)) {
        	$whereTimeEnd = $to ? "ctime<'{$to}' " : "";
        }
        $whereName = $name ? "name='{$name}' " : "";
        $whereCode = $code ? "code='{$code}' " : "";
        $whereColor = $color ? "color='{$color}' " : "";
        $whereSize = $size ? "size='{$size}' " : "";

        $whereSql = "";
        $time = array("r.`id`=o.`repo_id`");
        if ($where) {
        	$where = array();
        	if (isset($whereTimeStart)) {
        		$where[] = "r.".$whereTimeStart;
        		$time[] = "r.".$whereTimeStart;
        	}
        	if (isset($whereTimeEnd)) {
        		$where[] = "r.".$whereTimeEnd;
        		$time[] = "r.".$whereTimeEnd;
        	}
        	if ($whereName) {
        		$where[] = "r.".$whereName;
        	}
        	if ($whereCode) {
        		$where[] = "r.".$whereCode;
        	}
        	if ($whereColor) {
        		$where[] = "r.".$whereColor;
        	}
        	if ($whereSize) {
        		$where[] = "r.".$whereSize;
        	}
            $whereSql.=($where ? " WHERE " : "") . implode(" and ",$where);
        }
        $sortMap = array('r.`name`','r.`code`','r.`color`','r.`size`','`left`','`times`','total_count',"id"=>"id");
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
                WHERE ".
                implode(" and ",$time).
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
                    FROM `mom_current_repo` r {$whereSql}
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
    
    private function filter($var)
    {
    	if ($var == "null" || $var == "" || $var == "请选择" || $var == "全部" || $var == "all") {
    		return false;
    	} else {
    		return $var;
    	}
    }
}
?>
