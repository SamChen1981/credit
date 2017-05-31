<?php
class PageHelper extends AppHelper {

/**
 * 前台分页显示
 * @param GET参数 $url
 */
   public function pages($count, $url){
   	if($count != 0){
		   foreach($url as $k =>$value){
				if($k!='page'){
				 	$param[] ="$k".'='."$value";
				 }
			}
			if(!empty($param)){
				$param_url = "&".implode("&",$param);
			}
			 $js .= "<script>";
			 $js .= "$(function(){
			             laypage({
						    cont:$('#myform'), 
						    pages: $count,
						    skip: true, 
			    			skin: 'xcontent',
			    			groups: 3,
						    curr: function(){
						        var page = location.search.match(/page=(\d+)/);
						        return page ? page[1] : 1;
						    }(), 
						    jump: function(e, first){
						        if(!first){
						            location.href = '?page='+e.curr+'$param_url';
						        }
						    }
						});
			    }) ";
			$js .="</script>"; 	
			
	   	return $js;
   	}
   }
}