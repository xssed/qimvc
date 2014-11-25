<?php
  if(!defined('QI_PATH'))exit("error!");
  class M_conf extends DB
  {
    var $id;
    var $conf;
    var $value;
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
        $sql='select * from `conf` '.$id;
      else
        $sql='select * from `conf` where id='.$id;
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
      if (isset($_REQUEST['conf']))
        $this->conf=RequestString("conf");
      if (isset($_REQUEST['value']))
        $this->value=RequestString("value");
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
      $this->conf='';
      $this->value='';
      $this->bak1='';
      $this->bak2='';
    }
    /**
     * 数据库事物开启
    */
    function Transaction_start()
    {
      return D_conf::Transaction_start();
    }
    /**
     * 数据库事物回滚
    */
    function Transaction_rollback()
    {
      return D_conf::Transaction_rollback();
    }
    /**
     * 数据库事物提交
    */
    function Transaction_commit()
    {
      return D_conf::Transaction_commit();
    }
    /**
     * 添加数据
    */
    function Add()
    {
      $sql="insert into `conf`(";
      $sql=$sql.'`id`';
      $sql=$sql.',`conf`';
      $sql=$sql.',`value`';
      $sql=$sql.',`bak1`';
      $sql=$sql.',`bak2`';
      $sql=$sql.') values(%s,%s,%s,%s,%s)';
      $sql=sprintf($sql,
        0
        ,$this->quote_smart($this->conf)
        ,$this->quote_smart($this->value)
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
      $sql="update `conf` set ";
      $sql=$sql.'`id`=%s';
      $sql=$sql.',`conf`=%s';
      $sql=$sql.',`value`=%s';
      $sql=$sql.',`bak1`=%s';
      $sql=$sql.',`bak2`=%s';
      $sql=$sql.' where `id`=%s';
      $sql=sprintf($sql,
        $this->quote_smart($this->id)
        ,$this->quote_smart($this->conf)
        ,$this->quote_smart($this->value)
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
      return D_conf::DeleteByID($this->id);
    }
  }
  class D_conf extends DB
  {
    /**
     * 返回数据实体
     * $where 查询条件 参数中需带字符where
     * $cache 0不缓存1缓存2刷新缓存
     * $cachetime 缓存时长 单位：秒 默认120
    */
    static function GetModelByWhere($where='', $cache = 0, $cachetime = 120)
    {
      $sql='select * from `conf` '.$where;
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
      $model=new M_conf();
      $keys=array_keys($object);
      foreach($keys as $me)
      {
        $model->$me=$object[$me];
      }
      if ($cache != 0 && $GLOBALS['memcache']!=null)
        $GLOBALS['memcache']->set($cachekey, $model, false, $cachetime) or Bussiness::ErrorLog('conf',0, '缓存写入失败', $sql);
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
      $sql='select * from `conf`';
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
        $item=new M_conf();
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
        $GLOBALS['memcache']->set($cachekey, $objects, false, $cachetime) or Bussiness::ErrorLog('conf',0, '缓存写入失败', $sql);
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
      $sql='select * from `conf`';
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
        $item=new M_conf();
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
        $GLOBALS['memcache']->set($cachekey, $objects, false, $cachetime) or Bussiness::ErrorLog('conf',0, '缓存写入失败', $sql);
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
      $sql='select '.$keys.' from `conf` '.$where;
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
      $sql='select '.$keys.' from `conf` '.$where;
      return DB::GetArray($sql,$PageSize, $PageIndex,$cache,$cachetime);
    }
    /**
     * 根据WHERE条件删除数据
     * $where 参数中需带字符where
    */
    static function DeleteByWhere($where)
    {
      $sql="delete from `conf` $where";
      mysql_query($sql);
      return mysql_affected_rows();
    }
    /**
     * 根据ID删除数据
     * $ids 多个ID逗号分隔
    */
    static function DeleteByID($ids)
    {
      $sql="delete from `conf` where `id` in ($ids)";
      mysql_query($sql);
      return mysql_affected_rows();
    }
    /**
     * 根据WHERE条件查询记录总数
     * $where 参数中需带字符where
    */
    static function Count($where='')
    {
      return self::GetValue('count(*)','conf',$where);
    }
    /**
     * 根据WHERE条件获取最大ID
     * $where 参数中需带字符where 可为空
    */
    static function MaxID($where='')
    {
      return self::GetValue('max(id)','conf',$where);
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
