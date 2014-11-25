<?php
  if(!defined('QI_PATH'))exit("error!");
    
    
    //默认执行方法
    if ($qi_do == "") {
    	echo "Hello, this is action modual.";
      exit ();
    }
    
    
    //新建
    if ($qi_do == "add") {
        $add_modual = new M_modual();
    	//自动绑定表单数据,前提是表单中键名必须与字段名相同
    	//通过BindForm绑定的数据会被过滤处理
    	$add_modual->BindForm();
    	$add_modual = format_object($add_modual->Add());//添加后会返回这个对象
         $tpl->assign ( "modual_entity", $add_modual );
    	$tpl->display ( "modual.html" );
        exit ();
    }
    
    
    //删除
    if ($qi_do == "del") {
     	if(D_modual::DeleteByID($qi_id)){
        	$tpl->assign ( "result", "恭喜,操作成功!");
            $tpl->clear_cache("modual.html",md5(modual.$qi_id));
        	$tpl->display ( "ok.html" );
      }else{
            $tpl->assign ( "result", "抱歉,操作失败!");
        	$tpl->display ( "sorry.html" );
      }
        exit ();
    }
    
    
    //编辑
    if ($qi_do == "edit") {
         $edit_modual = new M_modual($qi_id);
    	$edit_modual->BindForm ();
    	if($edit_modual->Edit()){
        	$tpl->assign ( "result", "恭喜,操作成功!");
            $tpl->clear_cache("modual.html",md5(modual.$qi_id));
        	$tpl->display ( "ok.html" );
    	}else{
        	$tpl->assign ( "result", "抱歉,操作失败!");//与之前修改一致无变化也会修改失败
        	$tpl->display ( "sorry.html" );
    	}
      exit ();
    }
    
    
    //查询一个记录
    if ($qi_do == "get") {
         if(!$tpl->is_cached("modual.html",md5(modual.$qi_id))){
             $get_modual = D_modual::GetModel ( $qi_id );
            	$get_modual = format_object ( $get_modual);
            	$tpl->assign ( "modual_entity", $get_modual );
         }
    	$tpl->display ( "modual.html",md5(modual.$qi_id) );
      exit ();
    }
    
    
    //查询所有数据(分页)
    if ($qi_do == "getall") {
        if(!$tpl->is_cached("modual.html",md5($_SERVER['REQUEST_URI']))){
         $get_sql_where_modual=" ";
    	$PageSize=$qi_PageSize_news;
    	$PageIndex=$qi_page;
    	$get_all_modual= D_modual::GetArray($PageSize, $PageIndex,' * ',$get_sql_where_modual);
         $get_all_modual = format_object ( $get_all_modual );
         $tpl->assign ( "modual_entity_all_list", $get_all_modual );
     }
         $tpl->display ( "modual.html",md5($_SERVER['REQUEST_URI']) );
      exit ();
    }
    
    
    //查询所有数据(不分页)
    if ($qi_do == "getalldata") {
        if(!$tpl->is_cached("modual.html",md5($_SERVER['REQUEST_URI']))){
    	$get_all_modual = D_modual::GetArrayALL();
         $get_all_modual = format_object ( $get_all_modual );
    	$tpl->assign ( "modual_entity_all_list", $get_all_modual );
         }
    	$tpl->display ( "modual.html",md5($_SERVER['REQUEST_URI']) );
      exit ();
    }
    
    
    //找不到执行项目所做的
    echo "404!";
?>
