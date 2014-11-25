<?php
if(!defined('QI_PATH'))exit("error!");
echo "=====================模型创建程序加载中======================<br/>";
$table = DB::GetTableList(DB_Name);
$inc_model="";
foreach ($table as $k) {
    $TableName = $k;
    $code = CreatCode($TableName);
    CreatTxt('model.' . $TableName, $code);

    $inc_model .= FTXT("require_once('model.$TableName.php');"); 

echo "==============模型model.$TableName.php生成成功!===========<br/>";
}
$code = FTXT("<?php");
$code .= $inc_model;
$code .= FTXT("?>");
CreatTxt("Models", $code);
echo "==============模型文件全部生成成功!===========<br/>";

function CreatCode($TableName)
{
    //$db = new DataBase();
    $TableInfo = DB::GetTableColName($TableName);
    $primary_key = Getprimary_key($TableInfo);
    $len = count($TableInfo);
    $code = FTXT("<?php");
    $code .= FTXT("if(!defined('QI_PATH'))exit(\"error!\");", 1);
    $code .= FTXT("class M_$TableName extends DB", 1);
    $code .= FTXT("{", 1);
    foreach ($TableInfo as $k => $v) {
        $code .= FTXT("var \$" . $k . ";", 2);
    }

    $code .= FTXT("function __construct(\$id='', \$cache = 0, \$cachetime = 120)", 2);
    $code .= FTXT("{", 2);
    //$code .= FTXT("parent::__construct();", 3);
    $code .= FTXT("if(\$id!='')", 3);
    $code .= FTXT("{", 3);
    $code .= FTXT("\$this->GetModel(\$id, \$cache, \$cachetime);", 4);
    //$code .= FTXT("if(\$this->$primary_key!=0)", 4);
    $code .= FTXT("return;", 4);
    $code .= FTXT("}", 3);
    $code .= FTXT("\$this->SetDefault();", 3);
    
    $code .= FTXT("}", 2);
    $code .= FTXT("");
    //获取实体的方法
    $code .= FTXT("private function GetModel(\$id, \$cache = 0, \$cachetime = 120)", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("if(stristr(\$id,'where '))",3);
    $code .= FTXT("\$sql='select * from `$TableName` '.\$id;",4);
    $code .= FTXT("else", 3);
    $code .= FTXT("\$sql='select * from `$TableName` where $primary_key='.\$id;",4);
    
    $code .= FTXT("\$cachekey = md5(\$sql);", 3);
    $code .= FTXT("if (\$cache == 1 && \$GLOBALS['memcache']!=null) {", 3);
    $code .= FTXT("if (\$GLOBALS['memcache']->get(\$cachekey) != false){//验证缓存", 4);
    $code .= FTXT("foreach(\$GLOBALS['memcache']->get(\$cachekey) as \$k=>\$v){", 5);
    $code .= FTXT("\$this->\$k = \$v; ", 6);
    $code .= FTXT("}", 5);
    $code .= FTXT("return;", 5);
    $code .= FTXT("}", 4); 
    $code .= FTXT("}", 3);
    $code .= FTXT("\$result = mysql_query(\$sql);", 3);
    $code .= FTXT("if(\$result==FALSE)", 3);
    $code .= FTXT("{", 3);
    $code .= FTXT("\$this->SetDefault();", 4);
    $code .= FTXT("return; ", 4);
    $code .= FTXT("}", 3);
    $code .= FTXT("\$object=mysql_fetch_array(\$result,MYSQL_ASSOC);", 3);
    $code .= FTXT("mysql_free_result(\$result);", 3);
    $code .= FTXT("if(\$object==FALSE)", 3);
    $code .= FTXT("{", 3);
    $code .= FTXT("\$this->SetDefault();", 4);
    $code .= FTXT("return; ", 4);
    $code .= FTXT("}", 3);
    $code .= FTXT("\$keys=array_keys(\$object);", 3);
    $code .= FTXT("foreach(\$keys as \$me)", 3);
    $code .= FTXT("{", 3);
    $code .= FTXT("\$this->\$me=\$object[\$me];", 4);
    $code .= FTXT("}", 3);
    $code .= FTXT("if (\$cache != 0 && \$GLOBALS['memcache']!=null)", 3);
    $code .= FTXT("\$GLOBALS['memcache']->set(\$cachekey, \$this, false, \$cachetime) or Bussiness::ErrorLog('file',0, '缓存写入失败', \$sql);", 4);
   
    $code .= FTXT("return \$this;", 3);
    $code .= FTXT("}", 2);

    //获取表单内容绑定实体的方法
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 绑定表单数据", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("function BindForm()", 2);
    $code .= FTXT("{", 2);

    foreach ($TableInfo as $k => $v) {
        if (!$v[2]) {
            $code .= FTXT("if (isset(\$_REQUEST['$k']))", 3);
            $code .= FTXT("\$this->$k=" . FormRequest($v, $k) . ";", 4);
        }
    }
    $code .= FTXT("return \$this;", 3);
    $code .= FTXT("}", 2);
    
    //设置默认值的方法
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 设置实体为默认值", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("function SetDefault()", 2);
    $code .= FTXT("{", 2);
    foreach ($TableInfo as $k => $v) {
        $code .= FTXT("\$this->" . $k . "=" . DefaultValue($v) . ";", 3);
    }
    $code .= FTXT("}", 2);
    
    
    
    
    //数据库事物开启
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 数据库事物开启", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("function Transaction_start()", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("return D_$TableName::Transaction_start();", 3);
    $code .= FTXT("}", 2);
    
    
    //数据库事物回滚
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 数据库事物回滚", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("function Transaction_rollback()", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("return D_$TableName::Transaction_rollback();", 3);
    $code .= FTXT("}", 2);
    
    
    //数据库事物提交
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 数据库事物提交", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("function Transaction_commit()", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("return D_$TableName::Transaction_commit();", 3);
    $code .= FTXT("}", 2);
    

    //添加数据
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 添加数据", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("function Add()", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("\$sql=\"insert into `$TableName`(\";", 3);
    $i = 0;
    foreach ($TableInfo as $k => $v) {
        //if (!$v[2]) {
        if ($i == 0)
            $code .= FTXT("\$sql=\$sql.'`$k`';", 3);
        else
            $code .= FTXT("\$sql=\$sql.',`$k`';", 3);
        $i++;
        // }
    }
    $tmp = "\$sql=\$sql.') values(";
    for ($i = 0; $i < $len; $i++) {
        //if (!$v[2]) {
        if ($i == 0)
            $tmp .= "%s";
        else
            $tmp .= ",%s";
        // }
    }
    $tmp .= ")';";
    $code .= FTXT($tmp, 3);
    $code .= FTXT("\$sql=sprintf(\$sql,", 3);
    $i = 0;
    foreach ($TableInfo as $k => $v) {
        if (!$v[2]) {
            if ($i == 0)
                $code .= FTXT("\$this->quote_smart(\$this->$k)", 4);

            else
                $code .= FTXT(",\$this->quote_smart(\$this->$k)", 4);

        } else {
            if ($i == 0)
                $code .= FTXT("0", 4);

            else
                $code .= FTXT(",0", 4);
        }
        $i++;
    }
    $code .= FTXT(");", 3);
    $code .= FTXT("mysql_query(\$sql);", 3);
    $code .= FTXT("if(mysql_affected_rows()<=0) return null;", 3);
    $code .= FTXT("\$id=mysql_insert_id();", 3);
    $code .= FTXT("return \$this->GetModel('where `$primary_key`='.\$id);", 3);
    $code .= FTXT("}", 2);
    //更新数据
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 更新数据", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("function Edit()", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("\$sql=\"update `$TableName` set \";", 3);
    $i = 0;
    foreach ($TableInfo as $k => $v) {
        //if (!$v[2]) {
        if ($i == 0)
            $code .= FTXT("\$sql=\$sql.'`$k`=%s';", 3);
        else
            $code .= FTXT("\$sql=\$sql.',`$k`=%s';", 3);
        $i++;
        //}
    }

    $code .= FTXT("\$sql=\$sql.' where `$primary_key`=%s';", 3);
    $code .= FTXT("\$sql=sprintf(\$sql,", 3);
    $i = 0;
    foreach ($TableInfo as $k => $v) {
        //if (!$v[2]) {
        if ($i == 0)
            $code .= FTXT("\$this->quote_smart(\$this->$k)", 4);

        else
            $code .= FTXT(",\$this->quote_smart(\$this->$k)", 4);
        $i++;
        //}
    }
    $code .= FTXT(",\$this->quote_smart(\$this->$primary_key)", 4);
    $code .= FTXT(");", 3);
    $code .= FTXT("mysql_query(\$sql);", 3);
    $code .= FTXT("if(mysql_affected_rows()<1) return false;", 3);
    $code .= FTXT("return true;", 3);
    $code .= FTXT("}", 2);
    //删除数据
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 删除实体自身关联数据", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("function Delete()", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("return D_$TableName::DeleteByID(\$this->$primary_key);", 3);
    $code .= FTXT("}", 2);

    $code .= FTXT("}", 1);
    //=============================================================================
    
    //静态方法
    
    //=============================================================================
    $code .= FTXT("class D_$TableName extends DB", 1);
    $code .= FTXT("{", 1);
        //获取实体的方法
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 返回数据实体", 2);
    $code .= FTXT(" * \$where 查询条件 参数中需带字符where", 2);
    $code .= FTXT(" * \$cache 0不缓存1缓存2刷新缓存", 2);
    $code .= FTXT(" * \$cachetime 缓存时长 单位：秒 默认120", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("static function GetModelByWhere(\$where='', \$cache = 0, \$cachetime = 120)", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("\$sql='select * from `$TableName` '.\$where;",3);
    $code .= FTXT("\$cachekey = md5(\$sql);", 3);
    $code .= FTXT("if (\$cache == 1 && \$GLOBALS['memcache']!=null) {", 3);
    $code .= FTXT("if (\$GLOBALS['memcache']->get(\$cachekey)!=false) //验证缓存", 4);
    $code .= FTXT("return \$GLOBALS['memcache']->get(\$cachekey);", 5);
    $code .= FTXT("}", 3);
    $code .= FTXT("\$result=mysql_query(\$sql);",3);
    $code .= FTXT("if(\$result==FALSE)", 3);
    $code .= FTXT("{", 3);
    $code .= FTXT("return null; ", 4);
    $code .= FTXT("}", 3);
    $code .= FTXT("\$object=mysql_fetch_array(\$result,MYSQL_ASSOC);", 3);
    $code .= FTXT("mysql_free_result(\$result);", 3);
    $code .= FTXT("if(\$object==FALSE)", 3);
    $code .= FTXT("{", 3);
    $code .= FTXT("return null; ", 4);
    $code .= FTXT("}", 3);  
    $code .= FTXT("\$model=new M_$TableName();", 3);
    $code .= FTXT("\$keys=array_keys(\$object);", 3);
    $code .= FTXT("foreach(\$keys as \$me)", 3);
    $code .= FTXT("{", 3);
    $code .= FTXT("\$model->\$me=\$object[\$me];", 4);
    $code .= FTXT("}", 3);
    $code .= FTXT("if (\$cache != 0 && \$GLOBALS['memcache']!=null)", 3);
    $code .= FTXT("\$GLOBALS['memcache']->set(\$cachekey, \$model, false, \$cachetime) or Bussiness::ErrorLog('$TableName',0, '缓存写入失败', \$sql);", 4);
    $code .= FTXT("return \$model;", 3);
    $code .= FTXT("}", 2);
    
    //通过主键获取实体的方法
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 返回数据实体", 2);
    $code .= FTXT(" * \$id 主键", 2);
    $code .= FTXT(" * \$cache 0不缓存1缓存2刷新缓存", 2);
    $code .= FTXT(" * \$cachetime 缓存时长 单位：秒 默认120", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("static function GetModel(\$id, \$cache = 0, \$cachetime = 120)", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("return self::GetModelByWhere('where $primary_key='.\$id, \$cache, \$cachetime);", 3);
    $code .= FTXT("}", 2);
    //获取列表
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 获取分页数据", 2);
    $code .= FTXT(" * \$PageSize 分页大小", 2);
    $code .= FTXT(" * \$PageIndex 页码", 2);
    $code .= FTXT(" * \$where 查询条件 参数中需带字符where", 2);
    $code .= FTXT(" * \$order 排序", 2);
    $code .= FTXT(" * \$cache 0不缓存1缓存2刷新缓存", 2);
    $code .= FTXT(" * \$cachetime 缓存时长 单位：秒 默认120", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("static function GetList(\$PageSize,\$PageIndex,\$where='',\$order='', \$cache = 0, \$cachetime = 120)",
        2);
    $code .= FTXT("{", 2);
    $code .= FTXT("if (\$PageSize < 1 || \$PageIndex < 1)", 3);
    $code .= FTXT("return array();", 4);
    $code .= FTXT("\$sql='select * from `$TableName`';", 3);
    $code .= FTXT("if(\$where!='')", 3);
    $code .= FTXT("\$sql.=' '.\$where;", 4);
    $code .= FTXT("if(\$order!='')", 3);
    $code .= FTXT("\$sql.=' order by '.\$order;", 4);
    $code .= FTXT("\$Limit = (\$PageIndex - 1) * \$PageSize . \",\" . \$PageSize;",
        3);
    $code .= FTXT("\$sql.=' LIMIT '.\$Limit;", 3);
    $code .= FTXT("\$cachekey = md5(\$sql);", 3);
    $code .= FTXT("if (\$cache == 1 && \$GLOBALS['memcache']!=null) {", 3);
    $code .= FTXT("if (\$GLOBALS['memcache']->get(\$cachekey)!=false) //验证缓存", 4);
    $code .= FTXT("return \$GLOBALS['memcache']->get(\$cachekey);", 5);
    $code .= FTXT("}", 3);
    $code .= FTXT("\$result=mysql_query(\$sql);", 3);
    $code .= FTXT("if(\$result==FALSE) return array();", 3);
    $code .= FTXT("\$row=mysql_fetch_array(\$result,MYSQL_ASSOC);", 3);
    $code .= FTXT("if(\$row==FALSE) return array();", 3);
    $code .= FTXT("\$keys=array_keys(\$row);", 3);
    $code .= FTXT("\$objects=array();", 3);
    $code .= FTXT("\$i=0;", 3);
    $code .= FTXT("do", 3);
    $code .= FTXT("{", 3);
    $code .= FTXT("\$item=new M_$TableName();", 4);
    $code .= FTXT("foreach(\$keys as \$me)", 4);
    $code .= FTXT("{", 4);
    $code .= FTXT("\$item->\$me=\$row[\$me];", 5);
    $code .= FTXT("}", 4);
    $code .= FTXT("\$objects[\$i]=\$item;", 4);
    $code .= FTXT("\$i++;", 4);
    $code .= FTXT("}", 3);
    $code .= FTXT("while(\$row=mysql_fetch_array(\$result));", 3);
    $code .= FTXT("mysql_free_result(\$result);", 3);
    $code .= FTXT("if (\$cache != 0 && \$GLOBALS['memcache']!=null)", 3);
    $code .= FTXT("\$GLOBALS['memcache']->set(\$cachekey, \$objects, false, \$cachetime) or Bussiness::ErrorLog('$TableName',0, '缓存写入失败', \$sql);", 4);
    $code .= FTXT("return \$objects;", 3);
    $code .= FTXT("}", 2);
    //获取全部列表
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 获取数据", 2);
    $code .= FTXT(" * \$where 查询条件 参数中需带字符where", 2);
    $code .= FTXT(" * \$order 排序", 2);
    $code .= FTXT(" * \$cache 0不缓存1缓存2刷新缓存", 2);
    $code .= FTXT(" * \$cachetime 缓存时长 单位：秒 默认120", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("static function GetListAll(\$where='',\$order='')", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("\$sql='select * from `$TableName`';", 3);
    $code .= FTXT("if(\$where!='')", 3);
    $code .= FTXT("\$sql.=' '.\$where;", 4);
    $code .= FTXT("if(\$order!='')", 3);
    $code .= FTXT("\$sql.=' order by '.\$order;", 3);
    $code .= FTXT("\$cachekey = md5(\$sql);", 3);
    $code .= FTXT("if (\$cache == 1 && \$GLOBALS['memcache']!=null) {", 3);
    $code .= FTXT("if (\$GLOBALS['memcache']->get(\$cachekey)!=false) //验证缓存", 4);
    $code .= FTXT("return \$GLOBALS['memcache']->get(\$cachekey);", 5);
    $code .= FTXT("}", 3);

    $code .= FTXT("\$result=mysql_query(\$sql);", 3);
    $code .= FTXT("if(\$result==FALSE) return array();", 3);
    $code .= FTXT("\$row=mysql_fetch_array(\$result,MYSQL_ASSOC);", 3);
    $code .= FTXT("if(\$row==FALSE) return array();", 3);
    $code .= FTXT("\$keys=array_keys(\$row);", 3);
    $code .= FTXT("\$objects=array();", 3);
    $code .= FTXT("\$i=0;", 3);
    $code .= FTXT("do", 3);
    $code .= FTXT("{", 3);
    $code .= FTXT("\$item=new M_$TableName();", 4);
    $code .= FTXT("foreach(\$keys as \$me)", 4);
    $code .= FTXT("{", 4);
    $code .= FTXT("\$item->\$me=\$row[\$me];", 5);
    $code .= FTXT("}", 4);
    $code .= FTXT("\$objects[\$i]=\$item;", 4);
    $code .= FTXT("\$i++;", 4);
    $code .= FTXT("}", 3);
    $code .= FTXT("while(\$row=mysql_fetch_array(\$result));", 3);
    $code .= FTXT("mysql_free_result(\$result);", 3);
    $code .= FTXT("if (\$cache != 0 && \$GLOBALS['memcache']!=null)", 3);
    $code .= FTXT("\$GLOBALS['memcache']->set(\$cachekey, \$objects, false, \$cachetime) or Bussiness::ErrorLog('$TableName',0, '缓存写入失败', \$sql);", 4);
    $code .= FTXT("return \$objects;", 3);
    $code .= FTXT("}", 2);
    
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 返回不带带分页的全部结果集数组", 2);
    $code .= FTXT(" * \$keys 查询的字段，逗号分隔", 2);
    $code .= FTXT(" * \$where 查询条件", 2);
    $code .= FTXT(" * \$cache 0不缓存1缓存2刷新缓存", 2);
    $code .= FTXT(" * \$cachetime 缓存时长 单位：秒 默认120", 2);
    $code .= FTXT(" */", 2);
    $code .= FTXT("static public function GetArrayALL(\$keys='*',\$where='', \$cache = 0, \$cachetime = 120)", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("\$sql='select '.\$keys.' from `$TableName` '.\$where;", 3);
    $code .= FTXT("return DB::GetArrayALL(\$sql,\$cache,\$cachetime);", 3);
    $code .= FTXT("}", 2);
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 返回带分页的全部结果集数组", 2);
    $code .= FTXT(" * \$PageSize 分页大小", 2);
    $code .= FTXT(" * \$PageIndex 页码", 2);
    $code .= FTXT(" * \$keys 查询的字段，逗号分隔", 2);
    $code .= FTXT(" * \$where 查询条件", 2);
    $code .= FTXT(" * \$cache 0不缓存1缓存2刷新缓存", 2);
    $code .= FTXT(" * \$cachetime 缓存时长 单位：秒 默认120", 2);
    $code .= FTXT(" */", 2);
    $code .= FTXT("static public function GetArray(\$PageSize, \$PageIndex, \$keys='*', \$where='', \$cache = 0, \$cachetime = 120)", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("\$sql='select '.\$keys.' from `$TableName` '.\$where;", 3);
    $code .= FTXT("return DB::GetArray(\$sql,\$PageSize, \$PageIndex,\$cache,\$cachetime);", 3);
    $code .= FTXT("}", 2);
    
    
    //删除数据
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 根据WHERE条件删除数据", 2);
    $code .= FTXT(" * \$where 参数中需带字符where", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("static function DeleteByWhere(\$where)", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("\$sql=\"delete from `$TableName` \$where\";", 3);
    $code .= FTXT("mysql_query(\$sql);", 3);
    $code .= FTXT("return mysql_affected_rows();", 3);
    $code .= FTXT("}", 2);
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 根据ID删除数据", 2);
    $code .= FTXT(" * \$ids 多个ID逗号分隔", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("static function DeleteByID(\$ids)", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("\$sql=\"delete from `$TableName` where `$primary_key` in (\$ids)\";", 3);
    $code .= FTXT("mysql_query(\$sql);", 3);
    $code .= FTXT("return mysql_affected_rows();", 3);
    $code .= FTXT("}", 2);
    
    //计算数据总数
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 根据WHERE条件查询记录总数", 2);
    $code .= FTXT(" * \$where 参数中需带字符where", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("static function Count(\$where='')", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("return self::GetValue('count(*)','$TableName',\$where);", 3);
    $code .= FTXT("}", 2);


    //获取最大ID
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 根据WHERE条件获取最大ID", 2);
    $code .= FTXT(" * \$where 参数中需带字符where 可为空", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("static function MaxID(\$where='')", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("return self::GetValue('max($primary_key)','$TableName',\$where);",
        3);
    $code .= FTXT("}", 2);
    
    
    //数据库事物开启
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 数据库事物开启", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("static function Transaction_start()", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("return mysql_query('START TRANSACTION');", 3);
    $code .= FTXT("}", 2);
    
    
    //数据库事物回滚
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 数据库事物回滚", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("static function Transaction_rollback()", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("return mysql_query('ROLLBACK');", 3);
    $code .= FTXT("}", 2);
    
    
    //数据库事物提交
    $code .= FTXT("/**", 2);
    $code .= FTXT(" * 数据库事物提交", 2);
    $code .= FTXT("*/", 2);
    $code .= FTXT("static function Transaction_commit()", 2);
    $code .= FTXT("{", 2);
    $code .= FTXT("return mysql_query('COMMIT');", 3);
    $code .= FTXT("}", 2);
    //-------------------------------------------------------
    
    
    $code .= FTXT("}", 1);
    $code .= FTXT("?>");
    return $code;
}
/**
 * 获取主键
 */
function Getprimary_key($tableinfo)
{
    $tmp;
    $i = 0;
    foreach ($tableinfo as $k => $v) {
        if ($i == 0)
            $tmp = $k;
        $i++;
        if ($v[1] == true)
            return $k;
    }
    return $tmp;
}
/**
 * 根据字段类型设置默认值
 */
function DefaultValue($type)
{

    switch ($type[0]) {
        case "datetime":
            $Key = "date(\"Y-m-d H:i:s\")";
            break;
        case "date":
            $Key = "date(\"Y-m-d\")";
            break;
        case "real":
            $Key = 0;
            break;
        case "int":
            $Key = 0;
            break;
        default:
            $Key = "''";
            break;

    }
    return $Key;

}
/**
 * 根据字段类型返回提取表单数据方法
 */
function FormRequest($type, $key)
{

    switch ($type[0]) {
        case "datetime":
            $method = "RequestTime(\"$key\",date(\"Y-m-d H:i:s\"))";
            break;
        case "date":
            $method = "RequestDate(\"$key\",date(\"Y-m-d\"))";
            break;
        case "real":
            $method = "RequestFloat(\"$key\")";
            break;
        case "int":
            $method = "RequestInt(\"$key\",0)";
            break;
        default:
            $method = "RequestString(\"$key\")";
            break;

    }
    return $method;

}

/**
 * 生成文本文件
 */
function CreatTxt($filename, $code)
{
    $filedir = QI_PATH.'model/entity';
    if (!is_dir($filedir)) {
        //如果不存在就建立
        mkdir($filedir, 0777);
    }
    $filename = $filename . ".php";
    $fp = fopen("$filedir/$filename", "w");
    fwrite($fp, $code);
    fclose($fp);
    //echo "文件写入成功";
}
?>