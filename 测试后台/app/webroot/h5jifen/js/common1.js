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
 var id=GetQueryString("id");
 var uid=GetQueryString("uid");
 var goodsId = GetQueryString("goodsId");
 var ordersNo = GetQueryString("ordersNo");
 var token = GetQueryString("token");
