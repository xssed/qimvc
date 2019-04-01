<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
QI MVC 测试代码<br/><br/>
<?php
include("QI/QI.php");
$smarty->assign("st_dir", $smarty_template_dir); //修改模板样式的相对路径

echo '取得多条数据方法(4种)<br/>';
echo '1.实体不分页<br/>';

$list=D_news::GetListALL('where id>310','id desc');
foreach($list as $v )
{
    echo $v->news_title.'<br/>';
}

echo '2.实体分页<br/>';
//10为分页1为页码
$list=D_news::GetList('2',2,'where id>310','id desc');
foreach($list as $v )
{
    echo $v->news_title.'<br/>';
}

echo '3.数组不分页,可以限制字段<br/>';
//此方法排序写在WHERE参数里面
$list=D_news::GetArrayALL('id,`news_title`','where id>310');
foreach($list as $v )
{
    echo $v['id'].$v['news_title'].'<br/>';
}
echo '4.数组分页,可以限制字段<br/>';
//此方法排序写在WHERE参数里面
$list=D_news::GetArray(2,1,'id,`news_title`','where id>310');
foreach($list as $v )
{
    echo $v['id'].$v['news_title'].'<br/>';
}

echo '<br/>===========================================<br/>';
echo '取得一条数据方法(4种)<br/>';
//10为分页1为页码
$model=new D_news(1);
//$model=new M_test("where `name`='a'");
//$model=D_test::GetModel(244);
//$model=D_test::GetModelByWhere("where `name`='a'");
var_dump($model);
echo '<br/>===========================================<br/>';
echo '删除方法(2种)<br/>';
echo 'D_test::DeleteByID(1)<br/>';
echo 'D_test::DeleteByWhere("where id>2")<br/>';
//D_test::DeleteByID(1);
//D_test::DeleteByWhere("where id>2");
echo '<br/>===========================================<br/>';
echo '添加数据方法<br/>';

$model=new M_news();
//$model->BindForm();//自动绑定表单数据，可大幅提高开发效率，可使用index.php?name=test 测试
////前提表单中键名必须与字段名相同
//$model->news_title=$_GET['add'].rand(100, 500);
//$model->BindForm();//通过BindForm绑定的数据会被过滤处理
//$model=$model->Add();
//echo $model->news_title;

echo '<br/>===========================================<br/>';
echo '更新数据方法<br/>';

$testmodel=new M_news(244);
//$model->BindForm();
$testmodel->news_title ='new test';
$testmodel->Edit();

echo '<br/>===========================================<br/>';
echo '取得数字总数<br/>';
//echo D_test::Count();
echo D_news::Count();
echo '<br/>===========================================<br/>';
echo '取得最大ID<br/>';
echo D_news::MaxID();
//echo D_test::MaxID("where id>2");



echo '<br/>===========================================<br/>';
echo '事物处理数据<br/>';

$testmodel=new M_news();
$testmodel->Transaction_start();
$addmodel=new M_news();
$addmodel->news_title='ceshi'.rand(100, 500);
$addmodel=$addmodel->Add();
echo '已经添加数据:'.$addmodel->news_title;
if($testmodel->Delete()==0){
	echo '删除失败事务回滚';
	$testmodel->Transaction_rollback();
}
$testmodel->Transaction_commit();

echo '<br/>===========================================<br/>';
echo 'LOG<br/>';
Log::Qi_Log("ceshi");


echo '<br/>===========================================<br/>';
echo '模板--实体<br/>';

$model_news=D_news::GetModel(224);
print_r($model_news);
$user_array=format_object($model_news);
$smarty->assign("model_news",$user_array);
//$smarty->display("page.html");
echo $smarty->_version;


echo '<br/>===========================================<br/>';
echo '模板--列表<br/>';

$model_news=D_news::GetListALL('','id desc');
foreach($model_news as $v )
{
    echo $v->news_title.'<br/>';
}
$user_array=format_object($model_news);
$smarty->assign("model_news_list",$user_array);
$smarty->display("page.html");


echo '<br/>===========================================<br/>';
$url = $_SERVER['PHP_SELF'];  
$filename=substr($url,strrpos($url,'/')+1);  
echo $filename;
?>