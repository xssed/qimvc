<?php
  if(!defined('QI_PATH'))exit("error!");
    
    
    //默认执行方法
    if ($qi_do == "") {
    	echo "Hello, this is action fun_type.";
      exit ();
    }
    
    
    //新建
    if ($qi_do == "add") {
        $add_fun_type = new M_fun_type();
    	//自动绑定表单数据,前提是表单中键名必须与字段名相同
    	//通过BindForm绑定的数据会被过滤处理
    	$add_fun_type->BindForm();
    	$add_fun_type = format_object($add_fun_type->Add());//添加后会返回这个对象
         $tpl->assign ( "fun_type_entity", $add_fun_type );
    	$tpl->display ( "fun_type.html" );
        exit ();
    }
    
    
    //删除
    if ($qi_do == "del") {
     	if(D_fun_type::DeleteByID($qi_id)){
        	$tpl->assign ( "result", "恭喜,操作成功!");
            $tpl->clear_cache("fun_type.html",md5(fun_type.$qi_id));
        	$tpl->display ( "ok.html" );
      }else{
            $tpl->assign ( "result", "抱歉,操作失败!");
        	$tpl->display ( "sorry.html" );
      }
        exit ();
    }
    
    
    //编辑
    if ($qi_do == "edit") {
         $edit_fun_type = new M_fun_type($qi_id);
    	$edit_fun_type->BindForm ();
    	if($edit_fun_type->Edit()){
        	$tpl->assign ( "result", "恭喜,操作成功!");
            $tpl->clear_cache("fun_type.html",md5(fun_type.$qi_id));
        	$tpl->display ( "ok.html" );
    	}else{
        	$tpl->assign ( "result", "抱歉,操作失败!");//与之前修改一致无变化也会修改失败
        	$tpl->display ( "sorry.html" );
    	}
      exit ();
    }
    
    
    //查询一个记录
    if ($qi_do == "get") {
         if(!$tpl->is_cached("fun_type.html",md5(fun_type.$qi_id))){
             $get_fun_type = D_fun_type::GetModel ( $qi_id );
            	$get_fun_type = format_object ( $get_fun_type);
            	$tpl->assign ( "fun_type_entity", $get_fun_type );
         }
    	$tpl->display ( "fun_type.html",md5(fun_type.$qi_id) );
      exit ();
    }
    
    
    //查询所有数据(分页)
    if ($qi_do == "getall") {
        if(!$tpl->is_cached("fun_type.html",md5($_SERVER['REQUEST_URI']))){
         $get_sql_where_fun_type=" ";
    	$PageSize=$qi_PageSize_news;
    	$PageIndex=$qi_page;
    	$get_all_fun_type= D_fun_type::GetArray($PageSize, $PageIndex,' * ',$get_sql_where_fun_type);
         $get_all_fun_type = format_object ( $get_all_fun_type );
         $tpl->assign ( "fun_type_entity_all_list", $get_all_fun_type );
     }
         $tpl->display ( "fun_type.html",md5($_SERVER['REQUEST_URI']) );
      exit ();
    }
    
    
    //查询所有数据(不分页)
    if ($qi_do == "getalldata") {
        if(!$tpl->is_cached("fun_type.html",md5($_SERVER['REQUEST_URI']))){
    	$get_all_fun_type = D_fun_type::GetArrayALL();
         $get_all_fun_type = format_object ( $get_all_fun_type );
    	$tpl->assign ( "fun_type_entity_all_list", $get_all_fun_type );
         }
    	$tpl->display ( "fun_type.html",md5($_SERVER['REQUEST_URI']) );
      exit ();
    }
    
    
    //找不到执行项目所做的
    echo "404!";
?>
