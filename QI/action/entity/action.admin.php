<?php
  if(!defined('QI_PATH'))exit("error!");
    
    
    //默认执行方法
    if ($qi_do == "") {
    	echo "Hello, this is action admin.";
      exit ();
    }
    
    
    //新建
    if ($qi_do == "add") {
        $add_admin = new M_admin();
    	//自动绑定表单数据,前提是表单中键名必须与字段名相同
    	//通过BindForm绑定的数据会被过滤处理
    	$add_admin->BindForm();
    	$add_admin = format_object($add_admin->Add());//添加后会返回这个对象
         $tpl->assign ( "admin_entity", $add_admin );
    	$tpl->display ( "admin.html" );
        exit ();
    }
    
    
    //删除
    if ($qi_do == "del") {
     	if(D_admin::DeleteByID($qi_id)){
        	$tpl->assign ( "result", "恭喜,操作成功!");
            $tpl->clear_cache("admin.html",md5(admin.$qi_id));
        	$tpl->display ( "ok.html" );
      }else{
            $tpl->assign ( "result", "抱歉,操作失败!");
        	$tpl->display ( "sorry.html" );
      }
        exit ();
    }
    
    
    //编辑
    if ($qi_do == "edit") {
         $edit_admin = new M_admin($qi_id);
    	$edit_admin->BindForm ();
    	if($edit_admin->Edit()){
        	$tpl->assign ( "result", "恭喜,操作成功!");
            $tpl->clear_cache("admin.html",md5(admin.$qi_id));
        	$tpl->display ( "ok.html" );
    	}else{
        	$tpl->assign ( "result", "抱歉,操作失败!");//与之前修改一致无变化也会修改失败
        	$tpl->display ( "sorry.html" );
    	}
      exit ();
    }
    
    
    //查询一个记录
    if ($qi_do == "get") {
         if(!$tpl->is_cached("admin.html",md5(admin.$qi_id))){
             $get_admin = D_admin::GetModel ( $qi_id );
            	$get_admin = format_object ( $get_admin);
            	$tpl->assign ( "admin_entity", $get_admin );
         }
    	$tpl->display ( "admin.html",md5(admin.$qi_id) );
    	Debug::stop();
        Debug::message();
      exit ();
    }
    
    
    //查询所有数据(分页)
    if ($qi_do == "getall") {
        if(!$tpl->is_cached("admin.html",md5($_SERVER['REQUEST_URI']))){
         $get_sql_where_admin=" ";
    	$PageSize=$qi_PageSize_news;
    	$PageIndex=$qi_page;
    	$get_all_admin= D_admin::GetArray($PageSize, $PageIndex,' * ',$get_sql_where_admin);
         $get_all_admin = format_object ( $get_all_admin );
         $tpl->assign ( "admin_entity_all_list", $get_all_admin );
     }
         $tpl->display ( "admin.html",md5($_SERVER['REQUEST_URI']) );
        Debug::stop();
        Debug::message();
      exit ();
    }
    
    
    //查询所有数据(不分页)
    if ($qi_do == "getalldata") {
        if(!$tpl->is_cached("admin.html",md5($_SERVER['REQUEST_URI']))){
    	$get_all_admin = D_admin::GetArrayALL();
         $get_all_admin = format_object ( $get_all_admin );
    	$tpl->assign ( "admin_entity_all_list", $get_all_admin );
         }
    	$tpl->display ( "admin.html",md5($_SERVER['REQUEST_URI']) );
        Debug::stop();
        Debug::message();
      exit ();
    }
    
    
    //找不到执行项目所做的
    echo "404!";
?>
