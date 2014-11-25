<?php
if(!defined('QI_PATH'))exit("error!");
class DB
{
    //释放数据库连接
    static function dbtarget_free()
    {
        mysql_close();
    }

    // Quote variable to make safe
    static function quote_smart($value)
    {
        // Stripslashes
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        // Quote if not a number or a numeric string
        if (!is_numeric($value)) {
            $value = "'" . mysql_real_escape_string($value) . "'";
            //$value = "'" . mysql_escape_string($value) . "'";
        }
        return $value;
    }

    static function GetSingleValue($SQL)
    {
        $result = mysql_query($SQL, $this->mysql_link);
        if ($result == false) {
            return null;
        }

        $row = mysql_fetch_array($result, MYSQL_NUM);
        $r = $row[0];
        mysql_freeresult($result);
        return $r;
    }


    //转换为  JSON 字符串对象
    static function toJSON()
    {
        $sResult = "";
        foreach ($this as $key => $value) {
            //根据 http://www.json.org/
            $value = str_replace("\"", "\\\"", $value);
            $value = str_replace("\\", "\\\\", $value);
            $value = str_replace("/", "\\/", $value);
            $value = str_replace("\b", "\\b", $value);
            $value = str_replace("\f", "\\f", $value);
            $value = str_replace("\n", "\\n", $value);
            $value = str_replace("\r", "\\r", $value);
            $value = str_replace("\t", "\\t", $value);

            if ($sResult == "")
                $sResult = "\"$key\":\"$value\"";
            else
                $sResult .= " , \"$key\":\"$value\"";
        }
        return "{" . $sResult . "}";
    }


    /**
     *根据表名返回全部字段信息 
     */
    static function GetTableColName($TableName)
    {

        $sql = "select * from `" . $TableName . "` LIMIT 1,2";
        $result = mysql_query($sql);
        $count = mysql_num_fields($result);
        $data;
        for ($i = 0; $i < $count; $i++) {
            $key = false;
            $auto = false;
            if (strstr(mysql_field_flags($result, $i), 'primary_key'))
                $key = true;
            if (strstr(mysql_field_flags($result, $i), 'auto_increment'))
                $auto = true;
            $data[mysql_field_name($result, $i)] = array(
                mysql_field_type($result, $i),
                $key,
                $auto);
        }
        return $data;
    }


    /**
     *获取全部表的名称 
     */
    static function GetTableList($dbname)
    {
        //echo $this->host;die;
        $rs = mysql_query("SHOW TABLES FROM $dbname");
        $tables = array();
        while ($row = mysql_fetch_row($rs)) {
            $tables[] = $row[0];
        }
        mysql_free_result($rs);
        return $tables;
    }
    /**
     * 返回不带带分页的全部结果集数组
     * $cache 0不缓存1缓存2刷新缓存
     * $cachetime 缓存时长 单位：秒 默认120
     */
    static public function GetArrayALL($sql, $cache = 0, $cachetime = 120)
    {
        $cachekey = md5('Array_' . $sql);
        if ($cache == 1 && $GLOBALS['memcache'] != null) {
            if ($GLOBALS['memcache']->get($cachekey) != false) //验证缓存

                return $GLOBALS['memcache']->get($cachekey);
        }
        $result = mysql_query($sql);
        //echo $sql;die();
        if (!$result) {
            return null;
        } else {
            while ($rsRow = @mysql_fetch_array($result)) {
                $rs[] = $rsRow;
            }
            return $rs;
            if ($cache != 0 && $GLOBALS['memcache'] != null)
                $GLOBALS['memcache']->set($cachekey, $rs, false, $cachetime) or Bussiness::
                    ErrorLog('-', 0, '缓存写入失败', $sql);

        }
    }
    /**
     * 返回带分页的结果集数组
     * $cache 0不缓存1缓存2刷新缓存
     * $cachetime 缓存时长 单位：秒 默认120
     */
    static public function GetArray($sql, $PageSize, $PageIndex, $cache = 0, $cachetime =
        120)
    {

        if ($PageSize < 1 || $PageIndex < 1)
            return null;
        else {
            $Limit = ($PageIndex - 1) * $PageSize . "," . $PageSize;
            $sql = $sql . " LIMIT " . $Limit;
            $cachekey = md5('Array_' . $sql);
            if ($cache == 1 && $GLOBALS['memcache'] != null) {
                if ($GLOBALS['memcache']->get($cachekey) != false) //验证缓存

                    return $GLOBALS['memcache']->get($cachekey);
            }
            $result = mysql_query($sql);
            //echo $sql;die();
            if (!$result) {
                return null;
            } else {
                while ($rsRow = @mysql_fetch_array($result)) {
                    $rs[] = $rsRow;
                }
                if ($cache != 0 && $GLOBALS['memcache'] != null)
                    $GLOBALS['memcache']->set($cachekey, $rs, false, $cachetime) or Bussiness::
                        ErrorLog('-', 0, '缓存写入失败', $sql);
                return $rs;
            }
        }
    }
    /**
     * 得到一个值
     */
    static public function GetValueBySQL($sql)
    {
        $result = mysql_query($sql);
        //var_dump($sql);
        try {
            $data_count = mysql_fetch_array($result, MYSQL_NUM);
            //var_dump($data_count);die;
            return $data_count[0];
        }
        catch (exception $e) {
            var_dump($e);
        }
    }

    static public function GetValue($key, $tablename, $where = '')
    {
        $sql = "select $key from `$tablename`";
        if ($where != '')
            $sql .= " $where";

        return self::GetValueBySQL($sql);
    }


    /**
     * 返回记录总数
     */
    static public function GetCount($sql)
    {
        $sql_count = "select count(*) as total_count " . strstr($sql, "from"); //查询总数
        $result_count = mysql_query($sql_count);
        $data_count = mysql_fetch_assoc($result_count);

        return $data_count['total_count'];
    }
    /**
     * 原始的query操作
     */
    static public function Query($sql)
    {
        return mysql_query($this->quote_smart($sql));
    }
    /**
     * 自动释放资源
     */
    function __destruct(){
    	$this->dbtarget_free();
    }

}
?>