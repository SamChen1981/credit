function  get_tenet(url){
     url=url.split('.')[0];
     return  url;

 }
var url= window.location.host;
var storage = window.localStorage;
//存储租户信息
var tenent=get_tenet(url)?get_tenet(url):storage.getItem('tenent');
// var tenent=GetQueryString('TenantID')?GetQueryString('TenantID'):null;
if(storage.getItem('tenent')!=null&&storage.getItem('tenent')!=""){
    var tenent=storage.getItem('tenent');
}else{
    var tenent=get_tenet(url);
}
var tenent='defaultDB';
storage.setItem('tenent',tenent);


//配置项
// var host_name="testjifen.sobeycloud.com";  //当前积分商城域名
// var host_server="http://"+tenent+"."+host_name; //当前积分商城加上租户模块域名
// var member_name="testhuiyuan.sobeycloud.com"; //个人中心域名
// var memberc_uri="http://"+tenent+'.'+member_name; //个人中心租户模块与域名
// var wechat_name="testmp.sobeycloud.com";//微信矩阵域名
// // var wx_name='测试';//关注微信公众号名称
// console.debug(memberc_uri+'/home/loginhtml');
// console.log(url);


//配置项 开发环境
var host_name="wjcreditf.sobeycloud.com";  //当前积分商城域名
var host_server="http://"+tenent+"."+host_name; //当前积分商城加上租户模块域名
var member_name="wjmemberc.sobeycloud.com:8007"; //个人中心域名
var memberc_uri="http://"+tenent+'.'+member_name; //个人中心租户模块与域名
var wechat_name="wjmp.sobeycloud.com";//微信矩阵域名
console.debug(memberc_uri+'/home/loginhtml');
console.log(url);