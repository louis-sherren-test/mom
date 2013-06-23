<?php
class BaseModel
{
	protected $table;
	protected $fields;
	protected $db;
	protected $options = array();
	
	/*
	 * key is the real key name of table.
	 * value is the key name of data from client. like $_POST , $_GET
	 */
	protected $_keyMap = array();
	
	/*
	 * keys name of table.
	 */
	protected $_keys = array();
	
	/*
	 * key is the real key name of table.
	 * value is custom key name of data you got from database.
	 * 
	 * you can define your own name of keys of data you got from database through this array.
	 */
	protected $_alias = array();

	/*
	 * default value
	 */
	protected $_default = array();

	protected $_tableFix = null;

	
	public function __construct($db = false)
	{
		if($db == false)
			$this->db = new Db();
		$this->_tableFix = C('TABLE_PRE_FIX');
	}
	
	public function query($sql)
	{
		return $this->db->query($sql);
    }

    public function tquery($sql,$params) 
    {
        foreach ($params as $k=>$v) {
            $params[$k] = mysql_real_escape_string($v);
        }
        $reg = "/\?([is])/";
        $sql = preg_replace_callback($reg,array($this,"preg_callback"),$sql);
        $count = count($params);
        for ($i = 0 ; $i < $count ; $i++) {
            $str[] = '$params['.$i.']';
        }
        $vars = join(",",$str);
        eval('$result = sprintf($sql,'.$vars.');');
        return $this->db->query($result);
    }

    public function getSingleResult()
    {
       return $this->db->getSingleResult();
    }

    public function getMultiResult()
    {
        return $this->db->getMultyResult();
    }

    private function preg_callback($matches)
    {
        if ($matches[1] == "s") {
            return "%s";
        }
        if ($matches[1] == "i") {
            return "%d";
        }
    }
	
	/*
	 * create data array depend on a model.
	 * @param $data , data to be added to the object.
	 */
	public function create($data = false)
	{
		if($data === false)
			$data = $_POST;
		$lv = $this->_default;
		if(is_array($this->_keys) && !empty($this->_keys))
		{
			foreach ($this->_keys as $v)
			{
				if(array_key_exists($v, $data))
				{
					$lv[$v] = $data[$v];
				}
				elseif(array_key_exists($v, $this->_keyMap)) // && array_key_exists($v,$data)
				{
					$lv[$v] = $data[$this->_keyMap[$v]]; 
				}
			}
			return $lv;
		}
		else
		{
			return false;
		}
	}
	
	public function insert($obj)
	{
		if(is_array($obj) && $obj != null)
		{
			$sql = 'insert into ' . (isset($this->_tableFix) ? $this->_tableFix : '') . (isset($this->_table) ? $this->_table : $this->options['table']) . ' ' . implode_key($obj) . ' values ' . implode_value($obj);
			$this->reset();
			if(!$this->db->query($sql))
				return false;
			else
				return true;
		}
		else
		{
			return false;
		}
	}
	
	public function update($data)
	{
		if(!is_array($data))
			return false;
		$str = ' ';
		$buff = array();
		foreach($data as $key => $value)
		{
			array_push($buff, '`'.$key.'`=\''.$value.'\'');
		}
		$str .= implode(',',$buff);
		$sql = 'update ' . (isset($this->_tableFix) ? $this->_tableFix : '') . (isset($this->_table) ? $this->_table : $this->options['table']) . ' set ' . $str . $this->options['where'];
		$this->reset();
		if(!$this->db->query($sql) || $this->db->getAffectedRows() == 0)
			return false;
		else
			return true;
	}
	
	public function select($fields = false,$multy = false,$multySingle = false)
	{
		if(!is_array($fields) && $fields !== false)
		{
			return false;
		}
		if(!empty($this->_alias))
		{
			foreach($this->_alias as $key => $alias)
			{
				foreach ($this->_keys as $k => $v)
				{
					if($v != $key)
						continue;
					else
						$this->_keys[$k] .= ' as ' . $alias;	
				}
			}
		}
		$sql = 'select ' . ($fields ? implode(',',$fields) : implode(',',$this->_keys)) . ' from ' . (isset($this->_tableFix) ? $this->_tableFix : '') . (isset($this->options['table']) ? $this->options['table'] : $this->_table) . ' ' . (isset($this->options['order']) ? $this->options['order'] : '') . ' ' . (isset($this->options['where']) ? $this->options['where'] : '') . ' ' . (isset($this->options['group']) ? $this->options['group'] : '') . ' ' . (isset($this->options['limit']) ? $this->options['limit'] : '');
		$result = $this->db->query($sql);
		$this->reset();
		if($result === false)
			return false;
		if($multy === false) {
            $data = $this->db->getSingleResult();
        } else {
            $data = $this->db->getMultyResult();
            if (!$data) {
                return false;
            }
            if ($multySingle !== false) {
                $ret = array();
                foreach ($data as $v) {
                    array_push($ret,$v["$multySingle"]);
                }
                $data = $ret;
            }
        }
		return $data;
	}
	
	public function delete()
	{
		$sql = 'delete from ' . (isset($this->_tableFix) ? $this->_tableFix : '') . (isset($this->_table) ? $this->_table : $this->options['table']) . $this->options['where'];
		$this->reset();
		if(!$this->db->query($sql) || $this->db->getAffectedRows() == 0)
			return false;
		else
			return true;
	}
	
	public function table($sql)
	{
		$this->options['table'] =  $sql . ' ';
		return $this;
	}
	
	public function where($sql)
	{
        if (!$sql) {
           return $this; 
        }
		$this->options['where'] = ' where ' . $sql . ' ';
		return $this;
	}

    public function group($sql)
    {
        $this->options['group'] = ' group by ' . $sql . ' ';
        return $this;
    }
	
	public function order($key,$order)
	{
		$this->options['order'] = ' order by `' . $key . '` ' . $order . ' ';
		return $this;
	}
	
	public function limit($start,$end)
	{
		$this->options['limit'] = ' limit ' . $start . ',' . $end . ' ';
		return $this;
	}

	public function lastInsertId()
	{
		$a = $this->db->query('select LAST_INSERT_ID()');
        $a = $this->getSingleResult();
		return $a['LAST_INSERT_ID()'];
	}

	public function exist($key,$value)
	{
		$res = $this->where($key . '=' . $value)->select('count(*)');
		if($res['count(*)'] == 0)
			return false;
		else
			return true;
	}

	protected function getFields()
	{
		if(isset($_fields) && is_array($_fields))
		{
			
		}
		else
		{
			$sql = 'desc' . $this->options['table'];
			if(!$this->db->query($sql))
				return false;
			$r = $this->db->getMultyResult();
			$keys = array();
			foreach ($r as $v)
			{
				array_push($keys, $v['Field']);
			}
			return $keys;
		}
	}
	
	public function reset()
	{
		unset($this->options);
	}

}
