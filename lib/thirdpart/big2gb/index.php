<?
	include("convert.php");
	function language($str){
		return zhconversion_tw($str);//转换为台湾正体
	}
	ob_start('language');
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
什么 什么 什么 什么 什么 
<?
	ob_end_flush();
?>