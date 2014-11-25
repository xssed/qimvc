<?php
  if(!defined('QI_PATH'))exit("error!");
    
    
    //默认执行方法
    if ($qi_do == "") {
    	echo "Hello, this is action news.";
      exit ();
    }
    
    
    //新建
    if ($qi_do == "add") {
        $add_news = new M_news();
    	//自动绑定表单数据,前提是表单中键名必须与字段名相同
    	//通过BindForm绑定的数据会被过滤处理
    	$add_news->BindForm();
    	$add_news = format_object($add_news->Add());//添加后会返回这个对象
         $tpl->assign ( "news_entity", $add_news );
    	$tpl->display ( "news.html" );
        exit ();
    }
    
    
    //删除
    if ($qi_do == "del") {
     	if(D_news::DeleteByID($qi_id)){
        	$tpl->assign ( "result", "恭喜,操作成功!");
            $tpl->clear_cache("news.html",md5(news.$qi_id));
        	$tpl->display ( "ok.html" );
      }else{
            $tpl->assign ( "result", "抱歉,操作失败!");
        	$tpl->display ( "sorry.html" );
      }
        exit ();
    }
    
    
    //编辑
    if ($qi_do == "edit") {
         $edit_news = new M_news($qi_id);
    	$edit_news->BindForm ();
    	if($edit_news->Edit()){
        	$tpl->assign ( "result", "恭喜,操作成功!");
            $tpl->clear_cache("news.html",md5(news.$qi_id));
        	$tpl->display ( "ok.html" );
    	}else{
        	$tpl->assign ( "result", "抱歉,操作失败!");//与之前修改一致无变化也会修改失败
        	$tpl->display ( "sorry.html" );
    	}
      exit ();
    }
    
    
    //查询一个记录
    if ($qi_do == "get") {
         if(!$tpl->is_cached("news.html",md5(news.$qi_id))){
             $get_news = D_news::GetModel ( $qi_id );
            	$get_news = format_object ( $get_news);
            	$tpl->assign ( "news_entity", $get_news );
         }
    	$tpl->display ( "news.html",md5(news.$qi_id) );
      exit ();
    }
    
    
    //查询所有数据(分页)
    if ($qi_do == "getall") {
        if(!$tpl->is_cached("news.html",md5($_SERVER['REQUEST_URI']))){
         $get_sql_where_news=" ";
    	$PageSize=$qi_PageSize_news;
    	$PageIndex=$qi_page;
    	$get_all_news= D_news::GetArray($PageSize, $PageIndex,' * ',$get_sql_where_news);
         $get_all_news = format_object ( $get_all_news );
         $tpl->assign ( "news_entity_all_list", $get_all_news );
     }
         $tpl->display ( "news.html",md5($_SERVER['REQUEST_URI']) );
      exit ();
    }
    
    
    //查询所有数据(不分页)
    if ($qi_do == "getalldata") {
        if(!$tpl->is_cached("news.html",md5($_SERVER['REQUEST_URI']))){
    	$get_all_news = D_news::GetArrayALL();
         $get_all_news = format_object ( $get_all_news );
    	$tpl->assign ( "news_entity_all_list", $get_all_news );
         }
    	$tpl->display ( "news.html",md5($_SERVER['REQUEST_URI']) );
      exit ();
    }
    
    
    //找不到执行项目所做的
    echo "404!";
?>
