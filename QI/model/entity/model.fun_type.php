<?php
  if(!defined('QI_PATH'))exit("error!");
  class M_fun_type extends DB
  {
    var $id;
    var $type_name;
    var $type_type;
    var $type_rank;
    var $type_parent;
    var $param1;
    var $param2;
    var $active;
    var $show_level;
    var $title;
    var $keyword;
    var $description;
    var $php_page;
    var $html_page;
    var $bak1;
    var $bak2;
    function __construct($id='', $cache = 0, $cachetime = 120)
    {
      if($id!='')
      {
        $this->GetModel($id, $cache, $cachetime);
        return;
      }
      $this->SetDefault();
    }

    private function GetModel($id, $cache = 0, $cachetime = 120)
    {
      if(stristr($id,'where '))
        $sql='select * from `fun_type` '.$id;
      else
        $sql='select * from `fun_type` where id='.$id;
      $cachekey = md5($sql);
      if ($cache == 1 && $GLOBALS['memcache']!=null) {
        if ($GLOBALS['memcache']->get($cachekey) != false){//验证缓存
          foreach($GLOBALS['memcache']->get($cachekey) as $k=>$v){
            $this->$k = $v; 
          }
          return;
        }
      }
      $result = mysql_query($sql);
      if($result==FALSE)
      {
        $this->SetDefault();
        return; 
      }
      $object=mysql_fetch_array($result,MYSQL_ASSOC);
      mysql_free_result($result);
      if($object==FALSE)
      {
        $this->SetDefault();
        return; 
      }
      $keys=array_keys($object);
      foreach($keys as $me)
      {
        $this->$me=$object[$me];
      }
      if ($cache != 0 && $GLOBALS['memcache']!=null)
        $GLOBALS['memcache']->set($cachekey, $this, false, $cachetime) or Bussiness::ErrorLog('file',0, '缓存写入失败', $sql);
      return $this;
    }
    /**
     * 绑定表单数据
    */
    function BindForm()
    {
      if (isset($_REQUEST['type_name']))
        $this->type_name=RequestString("type_name");
      if (isset($_REQUEST['type_type']))
        $this->type_type=RequestInt("type_type",0);
      if (isset($_REQUEST['type_rank']))
        $this->type_rank=RequestInt("type_rank",0);
      if (isset($_REQUEST['type_parent']))
        $this->type_parent=RequestInt("type_parent",0);
      if (isset($_REQUEST['param1']))
        $this->param1=RequestString("param1");
      if (isset($_REQUEST['param2']))
        $this->param2=RequestString("param2");
      if (isset($_REQUEST['active']))
        $this->active=RequestInt("active",0);
      if (isset($_REQUEST['show_level']))
        $this->show_level=RequestInt("show_level",0);
      if (isset($_REQUEST['title']))
        $this->title=RequestString("title");
      if (isset($_REQUEST['keyword']))
        $this->keyword=RequestString("keyword");
      if (isset($_REQUEST['description']))
        $this->description=RequestString("description");
      if (isset($_REQUEST['php_page']))
        $this->php_page=RequestString("php_page");
      if (isset($_REQUEST['html_page']))
        $this->html_page=RequestString("html_page");
      if (isset($_REQUEST['bak1']))
        $this->bak1=RequestString("bak1");
      if (isset($_REQUEST['bak2']))
        $this->bak2=RequestString("bak2");
      return $this;
    }
    /**
     * 设置实体为默认值
    */
    function SetDefault()
    {
      $this->id=0;
      $this->type_name='';
      $this->type_type=0;
      $this->type_rank=0;
      $this->type_parent=0;
      $this->param1='';
      $this->param2='';
      $this->active=0;
      $this->show_level=0;
      $this->title='';
      $this->keyword='';
      $this->description='';
      $this->php_page='';
      $this->html_page='';
      $this->bak1='';
      $this->bak2='';
    }
    /**
     * 数据库事物开启
    */
    function Transaction_start()
    {
      return D_fun_type::Transaction_start();
    }
    /**
     * 数据库事物回滚
    */
    function Transaction_rollback()
    {
      return D_fun_type::Transaction_rollback();
    }
    /**
     * 数据库事物提交
    */
    function Transaction_commit()
    {
      return D_fun_type::Transaction_commit();
    }
    /**
     * 添加数据
    */
    function Add()
    {
      $sql="insert into `fun_type`(";
      $sql=$sql.'`id`';
      $sql=$sql.',`type_name`';
      $sql=$sql.',`type_type`';
      $sql=$sql.',`type_rank`';
      $sql=$sql.',`type_parent`';
      $sql=$sql.',`param1`';
      $sql=$sql.',`param2`';
      $sql=$sql.',`active`';
      $sql=$sql.',`show_level`';
      $sql=$sql.',`title`';
      $sql=$sql.',`keyword`';
      $sql=$sql.',`description`';
      $sql=$sql.',`php_page`';
      $sql=$sql.',`html_page`';
      $sql=$sql.',`bak1`';
      $sql=$sql.',`bak2`';
      $sql=$sql.') values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)';
      $sql=sprintf($sql,
        0
        ,$this->quote_smart($this->type_name)
        ,$this->quote_smart($this->type_type)
        ,$this->quote_smart($this->type_rank)
        ,$this->quote_smart($this->type_parent)
        ,$this->quote_smart($this->param1)
        ,$this->quote_smart($this->param2)
        ,$this->quote_smart($this->active)
        ,$this->quote_smart($this->show_level)
        ,$this->quote_smart($this->title)
        ,$this->quote_smart($this->keyword)
        ,$this->quote_smart($this->description)
        ,$this->quote_smart($this->php_page)
        ,$this->quote_smart($this->html_page)
        ,$this->quote_smart($this->bak1)
        ,$this->quote_smart($this->bak2)
      );
      mysql_query($sql);
      if(mysql_affected_rows()<=0) return null;
      $id=mysql_insert_id();
      return $this->GetModel('where `id`='.$id);
    }
    /**
     * 更新数据
    */
    function Edit()
    {
      $sql="update `fun_type` set ";
      $sql=$sql.'`id`=%s';
      $sql=$sql.',`type_name`=%s';
      $sql=$sql.',`type_type`=%s';
      $sql=$sql.',`type_rank`=%s';
      $sql=$sql.',`type_parent`=%s';
      $sql=$sql.',`param1`=%s';
      $sql=$sql.',`param2`=%s';
      $sql=$sql.',`active`=%s';
      $sql=$sql.',`show_level`=%s';
      $sql=$sql.',`title`=%s';
      $sql=$sql.',`keyword`=%s';
      $sql=$sql.',`description`=%s';
      $sql=$sql.',`php_page`=%s';
      $sql=$sql.',`html_page`=%s';
      $sql=$sql.',`bak1`=%s';
      $sql=$sql.',`bak2`=%s';
      $sql=$sql.' where `id`=%s';
      $sql=sprintf($sql,
        $this->quote_smart($this->id)
        ,$this->quote_smart($this->type_name)
        ,$this->quote_smart($this->type_type)
        ,$this->quote_smart($this->type_rank)
        ,$this->quote_smart($this->type_parent)
        ,$this->quote_smart($this->param1)
        ,$this->quote_smart($this->param2)
        ,$this->quote_smart($this->active)
        ,$this->quote_smart($this->show_level)
        ,$this->quote_smart($this->title)
        ,$this->quote_smart($this->keyword)
        ,$this->quote_smart($this->description)
        ,$this->quote_smart($this->php_page)
        ,$this->quote_smart($this->html_page)
        ,$this->quote_smart($this->bak1)
        ,$this->quote_smart($this->bak2)
        ,$this->quote_smart($this->id)
      );
      mysql_query($sql);
      if(mysql_affected_rows()<1) return false;
      return true;
    }
    /**
     * 删除实体自身关联数据
    */
    function Delete()
    {
      return D_fun_type::DeleteByID($this->id);
    }
  }
  class D_fun_type extends DB
  {
    /**
     * 返回数据实体
     * $where 查询条件 参数中需带字符where
     * $cache 0不缓存1缓存2刷新缓存
     * $cachetime 缓存时长 单位：秒 默认120
    */
    static function GetModelByWhere($where='', $cache = 0, $cachetime = 120)
    {
      $sql='select * from `fun_type` '.$where;
      $cachekey = md5($sql);
      if ($cache == 1 && $GLOBALS['memcache']!=null) {
        if ($GLOBALS['memcache']->get($cachekey)!=false) //验证缓存
          return $GLOBALS['memcache']->get($cachekey);
      }
      $result=mysql_query($sql);
      if($result==FALSE)
      {
        return null; 
      }
      $object=mysql_fetch_array($result,MYSQL_ASSOC);
      mysql_free_result($result);
      if($object==FALSE)
      {
        return null; 
      }
      $model=new M_fun_type();
      $keys=array_keys($object);
      foreach($keys as $me)
      {
        $model->$me=$object[$me];
      }
      if ($cache != 0 && $GLOBALS['memcache']!=null)
        $GLOBALS['memcache']->set($cachekey, $model, false, $cachetime) or Bussiness::ErrorLog('fun_type',0, '缓存写入失败', $sql);
      return $model;
    }
    /**
     * 返回数据实体
     * $id 主键
     * $cache 0不缓存1缓存2刷新缓存
     * $cachetime 缓存时长 单位：秒 默认120
    */
    static function GetModel($id, $cache = 0, $cachetime = 120)
    {
      return self::GetModelByWhere('where id='.$id, $cache, $cachetime);
    }
    /**
     * 获取分页数据
     * $PageSize 分页大小
     * $PageIndex 页码
     * $where 查询条件 参数中需带字符where
     * $order 排序
     * $cache 0不缓存1缓存2刷新缓存
     * $cachetime 缓存时长 单位：秒 默认120
    */
    static function GetList($PageSize,$PageIndex,$where='',$order='', $cache = 0, $cachetime = 120)
    {
      if ($PageSize < 1 || $PageIndex < 1)
        return array();
      $sql='select * from `fun_type`';
      if($where!='')
        $sql.=' '.$where;
      if($order!='')
        $sql.=' order by '.$order;
      $Limit = ($PageIndex - 1) * $PageSize . "," . $PageSize;
      $sql.=' LIMIT '.$Limit;
      $cachekey = md5($sql);
      if ($cache == 1 && $GLOBALS['memcache']!=null) {
        if ($GLOBALS['memcache']->get($cachekey)!=false) //验证缓存
          return $GLOBALS['memcache']->get($cachekey);
      }
      $result=mysql_query($sql);
      if($result==FALSE) return array();
      $row=mysql_fetch_array($result,MYSQL_ASSOC);
      if($row==FALSE) return array();
      $keys=array_keys($row);
      $objects=array();
      $i=0;
      do
      {
        $item=new M_fun_type();
        foreach($keys as $me)
        {
          $item->$me=$row[$me];
        }
        $objects[$i]=$item;
        $i++;
      }
      while($row=mysql_fetch_array($result));
      mysql_free_result($result);
      if ($cache != 0 && $GLOBALS['memcache']!=null)
        $GLOBALS['memcache']->set($cachekey, $objects, false, $cachetime) or Bussiness::ErrorLog('fun_type',0, '缓存写入失败', $sql);
      return $objects;
    }
    /**
     * 获取数据
     * $where 查询条件 参数中需带字符where
     * $order 排序
     * $cache 0不缓存1缓存2刷新缓存
     * $cachetime 缓存时长 单位：秒 默认120
    */
    static function GetListAll($where='',$order='')
    {
      $sql='select * from `fun_type`';
      if($where!='')
        $sql.=' '.$where;
      if($order!='')
      $sql.=' order by '.$order;
      $cachekey = md5($sql);
      if ($cache == 1 && $GLOBALS['memcache']!=null) {
        if ($GLOBALS['memcache']->get($cachekey)!=false) //验证缓存
          return $GLOBALS['memcache']->get($cachekey);
      }
      $result=mysql_query($sql);
      if($result==FALSE) return array();
      $row=mysql_fetch_array($result,MYSQL_ASSOC);
      if($row==FALSE) return array();
      $keys=array_keys($row);
      $objects=array();
      $i=0;
      do
      {
        $item=new M_fun_type();
        foreach($keys as $me)
        {
          $item->$me=$row[$me];
        }
        $objects[$i]=$item;
        $i++;
      }
      while($row=mysql_fetch_array($result));
      mysql_free_result($result);
      if ($cache != 0 && $GLOBALS['memcache']!=null)
        $GLOBALS['memcache']->set($cachekey, $objects, false, $cachetime) or Bussiness::ErrorLog('fun_type',0, '缓存写入失败', $sql);
      return $objects;
    }
    /**
     * 返回不带带分页的全部结果集数组
     * $keys 查询的字段，逗号分隔
     * $where 查询条件
     * $cache 0不缓存1缓存2刷新缓存
     * $cachetime 缓存时长 单位：秒 默认120
     */
    static public function GetArrayALL($keys='*',$where='', $cache = 0, $cachetime = 120)
    {
      $sql='select '.$keys.' from `fun_type` '.$where;
      return DB::GetArrayALL($sql,$cache,$cachetime);
    }
    /**
     * 返回带分页的全部结果集数组
     * $PageSize 分页大小
     * $PageIndex 页码
     * $keys 查询的字段，逗号分隔
     * $where 查询条件
     * $cache 0不缓存1缓存2刷新缓存
     * $cachetime 缓存时长 单位：秒 默认120
     */
    static public function GetArray($PageSize, $PageIndex, $keys='*', $where='', $cache = 0, $cachetime = 120)
    {
      $sql='select '.$keys.' from `fun_type` '.$where;
      return DB::GetArray($sql,$PageSize, $PageIndex,$cache,$cachetime);
    }
    /**
     * 根据WHERE条件删除数据
     * $where 参数中需带字符where
    */
    static function DeleteByWhere($where)
    {
      $sql="delete from `fun_type` $where";
      mysql_query($sql);
      return mysql_affected_rows();
    }
    /**
     * 根据ID删除数据
     * $ids 多个ID逗号分隔
    */
    static function DeleteByID($ids)
    {
      $sql="delete from `fun_type` where `id` in ($ids)";
      mysql_query($sql);
      return mysql_affected_rows();
    }
    /**
     * 根据WHERE条件查询记录总数
     * $where 参数中需带字符where
    */
    static function Count($where='')
    {
      return self::GetValue('count(*)','fun_type',$where);
    }
    /**
     * 根据WHERE条件获取最大ID
     * $where 参数中需带字符where 可为空
    */
    static function MaxID($where='')
    {
      return self::GetValue('max(id)','fun_type',$where);
    }
    /**
     * 数据库事物开启
    */
    static function Transaction_start()
    {
      return mysql_query('START TRANSACTION');
    }
    /**
     * 数据库事物回滚
    */
    static function Transaction_rollback()
    {
      return mysql_query('ROLLBACK');
    }
    /**
     * 数据库事物提交
    */
    static function Transaction_commit()
    {
      return mysql_query('COMMIT');
    }
  }
?>
