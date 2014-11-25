<?php
if(!defined('QI_PATH'))exit("error!");
include_once(QI_V."smarty/Smarty.class.php");
//==============================View==============================
//********Smarty设置**********
$smarty_template_dir	=WEB_PATH.'templates/';
$smarty_compile_dir		=WEB_PATH.'templates_c/';
$smarty_config_dir		=WEB_PATH.'QI/config/';
$smarty_cache_dir		=WEB_PATH.'tpl_cache/';
$smarty_caching			=false;
$smarty_delimiter		=explode("|","{|}");
//初始化smarty
$tpl = new smarty();
$tpl->template_dir	= $smarty_template_dir;
$tpl->compile_dir	= $smarty_compile_dir;
$tpl->config_dir		= $smarty_config_dir;
$tpl->cache_dir		= $smarty_cache_dir;
$tpl->caching		= $smarty_caching;
$tpl->cache_lifetime = 3600;//秒
$tpl->left_delimiter = $smarty_delimiter[0];
$tpl->right_delimiter= $smarty_delimiter[1];
$tpl->assign("web_dir",$smarty_template_dir);//修改模板样式的相对路径
?>