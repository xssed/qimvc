<?php
// --------------------------------------------------------------------------
// File name   : CreateHtml.class.php
// Description : 生成静态页面的类
// Requirement : PHP5
//--------------------------------------------------------------------------
class CreateHtml{
//生成html文件路径
private $html_dir="./";
//html文件名称
private $html_name;
//生成html文件的位置名称
public  $path;
//缓存区内容
private $content;
//文件句柄
private $handle;
//内存指针
private $accesses;
//构造函数
public function __construct($html_dir="",$html_name="")
{
   $this->accesses++;
  //如果文件路径不存在建立文件夹
  if(opendir($html_dir)==0)
         {
           mkdir($html_dir);
         }
  $this->html_dir=$html_dir!=""?$html_dir:"./";
  $this->html_name=$html_name!=""?$html_name:substr(basename(__FILE__),0,strrpos(basename(__FILE__),".")).".html";
  $this->path= ($this->html_dir{strlen($this->html_dir)-1}=="/")
     ?($this->html_dir.$this->html_name):($this->html_dir."/".$this->html_name);
  ob_start();
}
//析构函数
public function __destruct()
        {
         $this->accesses--;
          ob_end_clean();
        }
//生成html页面
function tohtml()
{
$this->content=ob_get_contents();
if (is_file ($this->path)){
  @unlink ($this->path);
}
$handle = fopen ($this->path,"w");
if (!is_writable ($this->path)){
  return false;
}
if (!fwrite ($handle,$this->content)){
  return false;
}
fclose ($handle); //关闭指针
return $this->path;
}
}
//$html=new CreateHtml("./admin","z.htm");
//print "静态页面程序";
//$html->tohtml();
?>