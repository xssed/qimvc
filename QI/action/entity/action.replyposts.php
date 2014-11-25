<?php
  if(!defined('QI_PATH'))exit("error!");
    
    
    //默认执行方法
    if ($qi_do == "") {
    	echo "Hello, this is action replyposts.";
      exit ();
    }
    
    
    //新建
    if ($qi_do == "add") {
        $add_replyposts = new M_replyposts();
    	//自动绑定表单数据,前提是表单中键名必须与字段名相同
    	//通过BindForm绑定的数据会被过滤处理
    	$add_replyposts->BindForm();
    	$add_replyposts = format_object($add_replyposts->Add());//添加后会返回这个对象
         $tpl->assign ( "replyposts_entity", $add_replyposts );
    	$tpl->display ( "replyposts.html" );
        exit ();
    }
    
    
    //删除
    if ($qi_do == "del") {
     	if(D_replyposts::DeleteByID($qi_id)){
        	$tpl->assign ( "result", "恭喜,操作成功!");
            $tpl->clear_cache("replyposts.html",md5(replyposts.$qi_id));
        	$tpl->display ( "ok.html" );
      }else{
            $tpl->assign ( "result", "抱歉,操作失败!");
        	$tpl->display ( "sorry.html" );
      }
        exit ();
    }
    
    
    //编辑
    if ($qi_do == "edit") {
         $edit_replyposts = new M_replyposts($qi_id);
    	$edit_replyposts->BindForm ();
    	if($edit_replyposts->Edit()){
        	$tpl->assign ( "result", "恭喜,操作成功!");
            $tpl->clear_cache("replyposts.html",md5(replyposts.$qi_id));
        	$tpl->display ( "ok.html" );
    	}else{
        	$tpl->assign ( "result", "抱歉,操作失败!");//与之前修改一致无变化也会修改失败
        	$tpl->display ( "sorry.html" );
    	}
      exit ();
    }
    
    
    //查询一个记录
    if ($qi_do == "get") {
         if(!$tpl->is_cached("replyposts.html",md5(replyposts.$qi_id))){
             $get_replyposts = D_replyposts::GetModel ( $qi_id );
            	$get_replyposts = format_object ( $get_replyposts);
            	$tpl->assign ( "replyposts_entity", $get_replyposts );
         }
    	$tpl->display ( "replyposts.html",md5(replyposts.$qi_id) );
      exit ();
    }
    
    
    //查询所有数据(分页)
    if ($qi_do == "getall") {
        if(!$tpl->is_cached("replyposts.html",md5($_SERVER['REQUEST_URI']))){
         $get_sql_where_replyposts=" ";
    	$PageSize=$qi_PageSize_news;
    	$PageIndex=$qi_page;
    	$get_all_replyposts= D_replyposts::GetArray($PageSize, $PageIndex,' * ',$get_sql_where_replyposts);
         $get_all_replyposts = format_object ( $get_all_replyposts );
         $tpl->assign ( "replyposts_entity_all_list", $get_all_replyposts );
     }
         $tpl->display ( "replyposts.html",md5($_SERVER['REQUEST_URI']) );
      exit ();
    }
    
    
    //查询所有数据(不分页)
    if ($qi_do == "getalldata") {
        if(!$tpl->is_cached("replyposts.html",md5($_SERVER['REQUEST_URI']))){
    	$get_all_replyposts = D_replyposts::GetArrayALL();
         $get_all_replyposts = format_object ( $get_all_replyposts );
    	$tpl->assign ( "replyposts_entity_all_list", $get_all_replyposts );
         }
    	$tpl->display ( "replyposts.html",md5($_SERVER['REQUEST_URI']) );
      exit ();
    }
    
    
    //找不到执行项目所做的
    echo "404!";
?>
