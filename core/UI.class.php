<?php
abstract class UI
{
    public function __construct()
    {
        
    }
    
    /*
     * items array(array(...),array(...)) 每个二级元素中的 ：第一个元素是字段名 第二个元素是映射名 
     */
    public function createDataTable($items,$data)
    {
        $stream = "<table id='auto_table' class='table table-bordered data-table'><thead><tr>";
        $keys = array();
        foreach ($items as $k => $v) {
            array_push($keys,$v[0]);
            $stream .= "<th>{$v[1]}</th>";
        }
        $stream .= "</tr></thead><tbody>";
        /*foreach ($data as $columns) {
            $stream .= "<tr class='gradeA'>";
            foreach ($columns as $key => $value) {
                if (in_array($key,$keys)) {
                    $stream.= "<td>{$value}</td>";
                }
            }
            $stream .= "</tr>";
        }*/
        $stream .= "</tbody></table>";
        $script = "<script type='text/javascript'>
                    $.ajax({
                    'url': 'http://localhost/mom/?mod=sell&act=echo1',
                    'success': function(data){
                            
                    },
                    'dataType': 'json',
                    })
                    </script>";
        return $stream;
    }
}
