<?php
if(!defined('QI_PATH'))exit("error!");
echo "=====================控制器创建程序加载中======================<br/>";
$table = DB::GetTableList(DB_Name);
$inc_action="";
foreach ($table as $k) {
    $TableName = $k;
    $code = CreatActionCode($TableName);
    CreatActionTxt('action.' . $TableName, $code);
    $inc_action .= FTXT("case \"$TableName\":");
    $inc_action .= FTXT("include_once(QI_C.'entity/action.$TableName.php');");
    $inc_action .= FTXT("break;");
echo "==============模型action.$TableName.php生成成功!===========<br/>";
}
$code = FTXT("<?php");
$code .= FTXT("if(!defined('QI_PATH'))exit(\"error!\");");
$code .= FTXT("//执行页面");
$code .= FTXT("switch (\$qi_action){");
$code .= FTXT("case \"\":");
$code .= FTXT("include_once(QI_C.'entity/action.index.php');");
$code .= FTXT("break;");
$code .= FTXT("case \"index\":");
$code .= FTXT("include_once(QI_C.'entity/action.index.php');");
$code .= FTXT("break;");
$code .= FTXT("case \"tpl\":");
$code .= FTXT("include_once(QI_C.'entity/action.tpl.php');");
$code .= FTXT("break;");
$code .= $inc_action;
$code .= FTXT("default:");
$code .= FTXT("echo \"404!\";");
$code .= FTXT("}");
$code .= FTXT("?>");

$action_index_code= FTXT("<?php");
$action_index_code .= FTXT("if(!defined('QI_PATH'))exit(\"error!\");");
$action_index_code .= FTXT("//默认执行	");
$action_index_code .= FTXT("if (\$qi_do == \"\") {",4);
$action_index_code .= FTXT("echo \"Hello QI MVC!\";",4);
$action_index_code .= FTXT("Debug::stop();",4);
$action_index_code .= FTXT("Debug::message();",4);
$action_index_code .= FTXT("	exit();",5);
$action_index_code .= FTXT("}",1);
$action_index_code .= FTXT("?>");
CreatActionTxt("action.index", $action_index_code);

$action_index_code= FTXT("<?php");
$action_index_code .= FTXT("if(!defined('QI_PATH'))exit(\"error!\");");
$action_index_code .= FTXT("//默认执行	");
$action_index_code .= FTXT("if (\$qi_do == \"\") {",4);
$action_index_code .= FTXT("\$tpl->clear_all_cache();",4);
$action_index_code .= FTXT("	exit();",5);
$action_index_code .= FTXT("}",1);
$action_index_code .= FTXT("?>");
CreatActionTxt("action.tpl", $action_index_code);

CreatActionTxt("Actions", $code);
echo "==============控制器文件全部生成成功!===========<br/>";

function CreatActionCode($TableName)
{
    $code = FTXT("<?php");
    $code .= FTXT("if(!defined('QI_PATH'))exit(\"error!\");", 1);
    
    //美观空隙
    $code .= FTXT("", 2);
    $code .= FTXT("", 2);

    //默认执行方法
    $code .= FTXT("//默认执行方法", 2);
    $code .= FTXT("if (\$qi_do == \"\") {", 2);
    $code .= FTXT("	echo \"Hello, this is action $TableName.\";", 2);
    $code .= FTXT("exit ();", 3);
    $code .= FTXT("}", 2);
    
    //美观空隙
    $code .= FTXT("", 2);
    $code .= FTXT("", 2);
     
    //新建	
    $code .= FTXT("//新建", 2);
    $code .= FTXT("if (\$qi_do == \"add\") {", 2);
    $code .= FTXT("\$add_$TableName = new M_$TableName();", 4);
    $code .= FTXT("	//自动绑定表单数据,前提是表单中键名必须与字段名相同", 2);
    $code .= FTXT("	//通过BindForm绑定的数据会被过滤处理", 2);
    $code .= FTXT("	\$add_".$TableName."->BindForm();", 2);
    $code .= FTXT("	\$add_$TableName = format_object(\$add_".$TableName."->Add());//添加后会返回这个对象", 2);
    $code .= FTXT(" \$tpl->assign ( \"".$TableName."_entity\", \$add_".$TableName." );", 4);
    $code .= FTXT("	\$tpl->display ( \"$TableName.html\" );", 2);
    $code .= FTXT("exit ();", 4);
    $code .= FTXT("}", 2);
    
    //美观空隙
    $code .= FTXT("", 2);
    $code .= FTXT("", 2);
    
    //删除	
    $code .= FTXT("//删除", 2);
    $code .= FTXT("if (\$qi_do == \"del\") {", 2);
    $code .= FTXT(" 	if(D_$TableName::DeleteByID(\$qi_id)){", 2);
    $code .= FTXT("	\$tpl->assign ( \"result\", \"恭喜,操作成功!\");", 4);
    $code .= FTXT("\$tpl->clear_cache(\"".$TableName.".html\",md5(".$TableName.".\$qi_id));", 6);
    $code .= FTXT("	\$tpl->display ( \"ok.html\" );", 4);
    $code .= FTXT("  }else{", 2);
    $code .= FTXT("\$tpl->assign ( \"result\", \"抱歉,操作失败!\");", 6);
    $code .= FTXT("	\$tpl->display ( \"sorry.html\" );", 4);
    $code .= FTXT("  }", 2);

    $code .= FTXT("exit ();", 4);
    $code .= FTXT("}", 2);

    //美观空隙
    $code .= FTXT("", 2);
    $code .= FTXT("", 2);
        
    //编辑	
    $code .= FTXT("//编辑", 2);
    $code .= FTXT("if (\$qi_do == \"edit\") {", 2);
    $code .= FTXT(" \$edit_".$TableName." = new M_".$TableName."(\$qi_id);", 4);
    $code .= FTXT("	\$edit_".$TableName."->BindForm ();", 2);
    $code .= FTXT("	if(\$edit_".$TableName."->Edit()){", 2);
    $code .= FTXT("	\$tpl->assign ( \"result\", \"恭喜,操作成功!\");", 4);
    $code .= FTXT("\$tpl->clear_cache(\"".$TableName.".html\",md5(".$TableName.".\$qi_id));", 6);
    $code .= FTXT("	\$tpl->display ( \"ok.html\" );", 4);
    $code .= FTXT("	}else{", 2);
    $code .= FTXT("	\$tpl->assign ( \"result\", \"抱歉,操作失败!\");//与之前修改一致无变化也会修改失败", 4);
    $code .= FTXT("	\$tpl->display ( \"sorry.html\" );", 4);
    $code .= FTXT("	}", 2);
    $code .= FTXT("exit ();", 3);
    $code .= FTXT("}", 2);
    
    //美观空隙
    $code .= FTXT("", 2);
    $code .= FTXT("", 2);
    
    //查询一个记录
    $code .= FTXT("//查询一个记录", 2);
    $code .= FTXT("if (\$qi_do == \"get\") {", 2);
    $code .= FTXT(" if(!\$tpl->is_cached(\"".$TableName.".html\",md5(".$TableName.".\$qi_id))){", 4);
    $code .= FTXT(" \$get_".$TableName." = D_$TableName::GetModel ( \$qi_id );", 6);
    $code .= FTXT("	\$get_".$TableName." = format_object ( \$get_".$TableName.");", 6);
    $code .= FTXT("	\$tpl->assign ( \"".$TableName."_entity\", \$get_".$TableName." );", 6);
    $code .= FTXT(" }", 4);
    $code .= FTXT("	\$tpl->display ( \"".$TableName.".html\",md5(".$TableName.".\$qi_id) );", 2);
    $code .= FTXT("exit ();", 3);
    $code .= FTXT("}", 2);
    
    //美观空隙
    $code .= FTXT("", 2);
    $code .= FTXT("", 2);
    
    //查询所有数据(分页)
    $code .= FTXT("//查询所有数据(分页)", 2);
    $code .= FTXT("if (\$qi_do == \"getall\") {", 2);
    $code .= FTXT("if(!\$tpl->is_cached(\"".$TableName.".html\",md5(\$_SERVER['REQUEST_URI']))){", 4);
    $code .= FTXT(" \$get_sql_where_$TableName=\" \";", 4);
    $code .= FTXT("	\$PageSize=\$qi_PageSize_news;", 2);
    $code .= FTXT("	\$PageIndex=\$qi_page;", 2);
    $code .= FTXT("	\$get_all_".$TableName."= D_".$TableName."::GetArray(\$PageSize, \$PageIndex,' * ',\$get_sql_where_$TableName);", 2);
    $code .= FTXT("     \$get_all_".$TableName." = format_object ( \$get_all_".$TableName." );", 2);
    $code .= FTXT("     \$tpl->assign ( \"".$TableName."_entity_all_list\", \$get_all_".$TableName." );", 2);
    $code .= FTXT(" }", 2);
    $code .= FTXT("     \$tpl->display ( \"".$TableName.".html\",md5(\$_SERVER['REQUEST_URI']) );", 2);
    $code .= FTXT("exit ();", 3);
    $code .= FTXT("}", 2);
    
    //美观空隙
    $code .= FTXT("", 2);
    $code .= FTXT("", 2);
    
    //查询所有数据(不分页)
    $code .= FTXT("//查询所有数据(不分页)", 2);
    $code .= FTXT("if (\$qi_do == \"getalldata\") {", 2);
    $code .= FTXT("if(!\$tpl->is_cached(\"".$TableName.".html\",md5(\$_SERVER['REQUEST_URI']))){", 4);
    $code .= FTXT("	\$get_all_".$TableName." = D_".$TableName."::GetArrayALL();", 2);
    $code .= FTXT(" \$get_all_".$TableName." = format_object ( \$get_all_".$TableName." );", 4);
    $code .= FTXT("	\$tpl->assign ( \"".$TableName."_entity_all_list\", \$get_all_".$TableName." );", 2);
    $code .= FTXT(" }", 4);
    $code .= FTXT("	\$tpl->display ( \"$TableName.html\",md5(\$_SERVER['REQUEST_URI']) );", 2);
    $code .= FTXT("exit ();", 3);
    $code .= FTXT("}", 2);
    
    //美观空隙
    $code .= FTXT("", 2);
    $code .= FTXT("", 2);
    
   $code .= FTXT("//找不到执行项目所做的", 2);
   $code .= FTXT("echo \"404!\";", 2);
    
    $code .= FTXT("?>");
    return $code;
}

/**
 * 生成文本文件
 */
function CreatActionTxt($filename, $code)
{
    $filedir = QI_PATH.'action/entity';
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