<?php
  if(!defined('QI_PATH'))exit("error!");
    
    
    //默认执行方法
    if ($qi_do == "") {
    	echo "Hello, this is action conf.";
      exit ();
    }
    
    
    //新建
    if ($qi_do == "add") {
        $add_conf = new M_conf();
    	//自动绑定表单数据,前提是表单中键名必须与字段名相同
    	//通过BindForm绑定的数据会被过滤处理
    	$add_conf->BindForm();
    	$add_conf = format_object($add_conf->Add());//添加后会返回这个对象
         $tpl->assign ( "conf_entity", $add_conf );
    	$tpl->display ( "conf.html" );
        exit ();
    }
    
    
    //删除
    if ($qi_do == "del") {
     	if(D_conf::DeleteByID($qi_id)){
        	$tpl->assign ( "result", "恭喜,操作成功!");
            $tpl->clear_cache("conf.html",md5(conf.$qi_id));
        	$tpl->display ( "ok.html" );
      }else{
            $tpl->assign ( "result", "抱歉,操作失败!");
        	$tpl->display ( "sorry.html" );
      }
        exit ();
    }
    
    
    //编辑
    if ($qi_do == "edit") {
         $edit_conf = new M_conf($qi_id);
    	$edit_conf->BindForm ();
    	if($edit_conf->Edit()){
        	$tpl->assign ( "result", "恭喜,操作成功!");
            $tpl->clear_cache("conf.html",md5(conf.$qi_id));
        	$tpl->display ( "ok.html" );
    	}else{
        	$tpl->assign ( "result", "抱歉,操作失败!");//与之前修改一致无变化也会修改失败
        	$tpl->display ( "sorry.html" );
    	}
      exit ();
    }
    
    
    //查询一个记录
    if ($qi_do == "get") {
         if(!$tpl->is_cached("conf.html",md5(conf.$qi_id))){
             $get_conf = D_conf::GetModel ( $qi_id );
            	$get_conf = format_object ( $get_conf);
            	$tpl->assign ( "conf_entity", $get_conf );
         }
    	$tpl->display ( "conf.html",md5(conf.$qi_id) );
      exit ();
    }
    
    
    //查询所有数据(分页)
    if ($qi_do == "getall") {
        if(!$tpl->is_cached("conf.html",md5($_SERVER['REQUEST_URI']))){
         $get_sql_where_conf=" ";
    	$PageSize=$qi_PageSize_news;
    	$PageIndex=$qi_page;
    	$get_all_conf= D_conf::GetArray($PageSize, $PageIndex,' * ',$get_sql_where_conf);
         $get_all_conf = format_object ( $get_all_conf );
         $tpl->assign ( "conf_entity_all_list", $get_all_conf );
     }
         $tpl->display ( "conf.html",md5($_SERVER['REQUEST_URI']) );
      exit ();
    }
    
    
    //查询所有数据(不分页)
    if ($qi_do == "getalldata") {
        if(!$tpl->is_cached("conf.html",md5($_SERVER['REQUEST_URI']))){
    	$get_all_conf = D_conf::GetArrayALL();
         $get_all_conf = format_object ( $get_all_conf );
    	$tpl->assign ( "conf_entity_all_list", $get_all_conf );
         }
    	$tpl->display ( "conf.html",md5($_SERVER['REQUEST_URI']) );
      exit ();
    }
    
    
    //找不到执行项目所做的
    echo "404!";
?>
