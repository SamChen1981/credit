/*
* @Author: anchen
* @Date:   2016-02-22 14:23:14
* @Last Modified by:   anchen
* @Last Modified time: 2016-02-29 15:26:58
*/

'use strict';
function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}
function GetQueryToken(name){
    var url=window.location.href;
    $.ajax({
        url: '/creditshop/getUid/',
        type: 'post',
        dataType: 'json',
        async:false,
        data: {data:url},
        success: function(data){
            if(data.code==='0000'){
                if(name==='uid'){
                   uid=data.uid;
                }else if(name==='token'){
                   token=data.token;
                }else{
                    return null;
                }
            }else if(data.code==='0002'){
                alert(data.message);
                // location.href='http://cctv5.huiyuan-vip.sobeycloud.com/home/loginhtml/?callback='+window.location.host;
                // location.href='http://default.yanshimemberc.sobeycloud.com/home/loginhtml/?callback='+window.location.host;
                location.href=memberc_uri+'/home/loginhtml/?callback='+window.location.host;
                exit;
            }
        },
        error:function(data){
                return null;
        }
    })
    if(name==='uid'){
        return uid;
    }
    if(name==='token'){
        return token;
    }else{
        return false;
    }
}
 // function  get_tenet(url){
 //     url=url.split('.')[0];
 //     return  url;

 // }
function getsessionID() {
    $.ajax({
        // url: 'http://cctv5.huiyuan-vip.sobeycloud.com/auth/getid',
        url: memberc_uri+'/auth/getid',
        type: 'get',
        async : false,
        dataType: 'jsonp',
        jsonp: "callback",
        success: function (data) {
            if (data.session_id) {
                if (storage.getItem('sessionID') != 'null' && storage.getItem('sessionID') == data.session_id) {
                   getuid();
                } else {
                    storage.setItem('sessionID', data.session_id);
                    getuid();
                }
            } else {
                if (storage.getItem('sessionID') != 'null') {
                    storage.removeItem('sessionID');
                } else {

                }
                storage.removeItem('uid');
                storage.removeItem('token');
            }
        },
        error: function (data) {
            return false;
        }
    })
}
function getuid(){
    if(storage.getItem('sessionID')!=null){
        if(storage.getItem('sessionID')){
            $.ajax({
                url: '/creditshop/getSessionID',
                type: 'post',
                async : false,
                dataType: 'json',
                // data:{session_id:storage.getItem('sessionID'),url:'http://cctv5.huiyuan-vip.sobeycloud.com'},
                data:{session_id:storage.getItem('sessionID'),url:memberc_uri},
                success: function (data) {
                    console.debug(data);
                    console.log(data.uid);
                    console.log(data.token);
                    uid=data.uid;
                    token=data.token;
                    storage.setItem('token', token);
                    storage.setItem('uid', uid);
                    getmember();
                }
            })
        }
    }
}
var id=GetQueryString("id");
var goodsId = GetQueryString("goodsId");
var ordersNo = GetQueryString("ordersNo");
// var url= window.location.host;
// var storage = window.localStorage;
// //存储租户信息
// var tenent=get_tenet(url)?get_tenet(url):storage.getItem('tenent');
// // var tenent=GetQueryString('TenantID')?GetQueryString('TenantID'):null;
// if(storage.getItem('tenent')!=null&&storage.getItem('tenent')!=""){
//     var tenent=storage.getItem('tenent');
// }else{
//     var tenent=get_tenet(url);
// }
// var tenent='default';
// storage.setItem('tenent',tenent);


// //配置项
// var host_name="testjifen.sobeycloud.com";  //当前积分商城域名
// var host_server="http://"+tenent+"."+host_name; //当前积分商城加上租户模块域名
// var member_name="testhuiyuan.sobeycloud.com"; //个人中心域名
// var memberc_uri="http://"+tenent+'.'+member_name; //个人中心租户模块与域名
// var wechat_name="testmp.sobeycloud.com";//微信矩阵域名
// // var wx_name='测试';//关注微信公众号名称
// console.debug(memberc_uri+'/home/loginhtml');
// console.log(url);


//配置项 开发环境
// var host_name="wjcreditf.sobeycloud.com";  //当前积分商城域名
// var host_server="http://"+tenent+"."+host_name; //当前积分商城加上租户模块域名
// var member_name="devmemberc.sobeycloud.com:8007"; //个人中心域名
// var memberc_uri="http://"+tenent+'.'+member_name; //个人中心租户模块与域名
// var wechat_name="wjmp.sobeycloud.com";//微信矩阵域名
// console.debug(memberc_uri+'/home/loginhtml');
// console.log(url);


var params=storage.getItem('params')?storage.getItem('params'):window.location.search;
storage.setItem('params',params);
var isDefault=GetQueryString('isDefault')?GetQueryString('isDefault'):0;
var logisticsNum=GetQueryString('logisticsNum')?GetQueryString('logisticsNum'):null;
var SourceID=GetQueryString('SourceID')?GetQueryString('SourceID'):null;
if(GetQueryString("SourceID")){
    var SourceID =GetQueryString("SourceID");
}else if(storage.getItem('SourceID') !='null'){
    var SourceID = storage.getItem('SourceID');console.log(123);
}else{
    var SourceID = null;
}
storage.setItem('SourceID',SourceID);
console.debug(storage.getItem('SourceID'));
console.debug(storage.getItem('SourceID').indexOf("appfactory_mobile")==-1);
var params_count=(window.location.search).substr(1).split("&");
if(GetQueryString('openid')){
    var openid=GetQueryString('openid');
}else if(storage.getItem('openid') != 'null'){
    var openid=storage.getItem('openid');
}else{
    var openid=0;
}
storage.setItem('openid',openid);
if(storage.getItem('SourceID')=='null'){
            
}else if(storage.getItem('SourceID').indexOf("appfactory_mobile")!=-1){
           
}else if(params_count.length==2&&openid==0){

}else{
    getsessionID();
}  
var weid=GetQueryString('WeChatID')?GetQueryString('WeChatID'):null;
storage.setItem('weid',weid);
if(GetQueryString("uid")&&GetQueryString("uid")!=0){
    var uid =GetQueryString("uid");
}else if(storage.getItem('uid') !='null'){
    var uid = storage.getItem('uid');console.log(123);
}else{
    var uid = GetQueryToken("uid");console.log(3);
}
storage.setItem('uid',uid);
if(GetQueryString("token")&&GetQueryString("token")!=0){
    var token =GetQueryString("token");
}else if(storage.getItem('token') != 'null'){
    var token = storage.getItem('token');
}else{
    var token = GetQueryToken("token");
}
storage.setItem('token',token);
function callback(data){
    console.log(data);
}
console.debug(storage.getItem('sessionID'));
console.debug(uid);
console.debug(token);
