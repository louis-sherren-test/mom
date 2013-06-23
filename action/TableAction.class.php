<?php
class TableAction extends BaseAction
{
    public function _preCall()
    {
        //todo : do oauth
        if (0) {
            // failed, forbbiden user action and alert user to login
        }
    }

    public function _lasCall()
    {
        
    }

    public function createTable()
    {
        $table = isset($_GET["table"]) ? $_GET["table"] : "";
        $this->mod->tquery("SELECT `table_name` FROM `mom_table_info` WHERE `table_name`='?s'",array($table));
        $res = $this->mod->getSingleResult();
        if ($res) {
            echo "failed! 1";
            return;
        }
        $realname = sha1($table);
        $res = $this->mod->query("INSERT INTO `mom_table_info` VALUES('','{$table}','{$realname}')");
        if (!$res) {
            echo "failed! 2";
            return;
        }
        $res = $this->mod->query("CREATE TABLE {$realname}(id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL)");
        if (!$res) {
            echo "failed! 3";
            return;
        }
        echo "success";
    }
    
    /*
     * 删除表，在表记录表中删除表，在字段记录表中删除所有关联字段，最后drop真实表. 
     */
    public function dropTable()
    {
        $table = isset($_GET["table"]) ? $_GET["table"] : "";
        $this->mod->tquery("SELECT `id`,`table_real_name` FROM `mom_table_info` WHERE `table_name`='?s'",array($table));
        $res = $this->mod->getSingleResult();
        if (!$res) {
            echo "failed! 1";
        }
        $this->mod->tquery("DELETE FROM `mom_key_info` WHERE `tid`=?i",array($res["id"]));
        $this->mod->tquery("DELETE FROM `mom_table_info` WHERE `id`=?i",array($res["id"]));
        $this->mod->tquery("DROP TABLE ?s",array($res["table_real_name"]));
        echo "success";
    }

    public function showTables()
    {
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $items = C("TABLES_PER_PAGE");
        $start = $items*($page-1);
        $this->mod->query("SELECT `table_name` FROM `mom_table_info` WHERE id>{$start} LIMIT {$items}");
        $data = $this->mod->getMultiResult();
        var_dump($data);
    }

    public function changeTableName()
    {
        $oldTable = isset($_GET["oldTable"]) ? $_GET["oldTable"] : "";
        $newTable = isset($_GET["newTable"]) ? $_GET["newTable"] : "";
        $this->mod->tquery("SELECT `table_real_name` FROM `mom_table_info` WHERE `table_name`='?s'",array($oldTable));
        $oldRealName = $this->mod->getSingleResult();
        $newRealName = sha1($newTable);
        $res = $this->mod->tquery("UPDATE `mom_table_info` SET `table_name`='?s' WHERE `table_name`='?s'",array($newTable,$oldTable));
        if (!$res) {
            echo "failed!";
            return;
        }
        $this->mod->query("ALTER TABLE `{$oldReamName}` RENAME `{$newRealName}`");
        echo "success";
    }

    public function showTablesKeys()
    {
        
    }

    public function addKey()
    {
        $table = isset($_GET["table"]) ? $_GET["table"] : "";
        $key = isset($_GET["key"]) ? $_GET["key"] : "";
        $type = isset($_GET["int"]) ? $_GET["int"] : "VARCHAR";
        $long = isset($_GET["long"]) ? $_GET["long"] : "65500";
        $sql = "";
        if ($int) {
            $sql = $type;
        }
        if ($long) {
            $sql .= "({$long})";
        }
        $res = $this->mod->tquery("SELECT `id`,`table_real_name` FROM `mom_table_info` WHERE `table_name`='?s'",array($table));
        if (!$res) {
            echo "table do not existed!";
        }
        $tableData = $this->mod->getSingleResult();
        $realKey = sha1($key);
        $this->mod->query("ALTER TABLE `{$tableData["table_real_name"]}` ADD {$realKey} {$sql} NOT NULL DEFAULT 0");
        $this->mod->query("INSERT INTO `mom_key_info` VALUES ('','{$tableData["id"]}','{$key}','{$realKey}')");
    }

    public function deleteKey()
    {
        $table = isset($_GET["table"]) ? $_GET["table"] : "";
        $key = isset($_GET["key"]) ? $_GET["key"] : "";
        $res = $this->mod->tquery("SELECT `id`,`table_real_name` FROM `mom_table_info` WHERE `table_name`='?s'",array($table));
        if (!$res) {
            echo "table do not exited!";
            return;
        }
        $tableData = $this->mod->getSingleResult();
        $res = $this->mod->tquery("SELECT `key_real_name` FROM `mom_key_info` WHERE `id`={$tableData["id"]} and `key_name`='?s'",array($key));
        if (!$res) {
            echo "key do not existed!";
            return;
        }
        $keyData = $this->mod->getSingleResult();
        $this->mod->query("ALTER TABLE `{$tableData["table_real_name"]}` DROP `{$keyData["key_real_name"]}`");
        $this->mod->tquery("DELETE FROM `mom_key_info` WHERE `key_name`='?s'",array($key));
        echo "success";
    }

   /* public function changeKeyName()
    {
        $table = isset($_GET["table"]) ? $_GET["table"] : "";
        $oldKey = isset($_GET["old_key"]) ? $_GET["old_key"] : "";
        $newKey = isset($_GET["new_key"]) ? $_GET["new_key"] : "";
        $realNewKey = sha1($newKey);
        $realOldKey = sha1($oldKey);
        $res = $this->mod->tquery("SELECT `id`,`table_real_name` FROM `mom_table_info` WHERE `table_name`='?s'",array($table));
        if (!$res) {
            echo "table do not existed!";
            return;
        }
        $tableData = $this->mod->getSingleResult();
        $res = $this->mod->tquery("SELECT `key_real_name` FROM `mom_key_info` WHERE `tid`='{$tableData["id"]}' and `key_name`='?s'",array($oldKey));
        if (!$res) {
            echo "key do not existed!";
            return;
        }
        $this->mod->tquery("UPDATE `mom_key_info` SET `key_name`='?s',`key_real_name`='?s' WHERE `key_name`='{$oldKey}'",array($newKey,$realNewKey));
        $this->mod->tquery("ALTER TABLE ");
}*/
}


?>
