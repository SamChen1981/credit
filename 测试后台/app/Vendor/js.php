<?php 
require_once "jssdk.php";
$ss='';
//$jssdk = new JSSDK("wxad27a99600602785", "07f2d1f6ca26781bee2f44a555b1c20c");
//$tid=Session::get('tenantid','');
//$wid=Session::get('weid','');
//$keys = Config::get('app.keys');
$tid=!empty($_SESSION['tenantid'])?$_SESSION['tenantid']:"";
$wid=!empty($_SESSION['weid'])?$_SESSION['weid']:"";
$keys = app_keys;
$jssdk = new JSSDK($tid,$wid,$keys);
$signPackage = $jssdk->GetSignPackage();
?>