<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2015/7/22
 * Time: 9:30
 */
class product extends AppModel{
   var $name= "product";

    public $actsAs = array('Containable');

    var $hasMany = array(
        "ProductLogs" => array(
            "className"  => "ProductLogs",
//            "conditions" => array("Recipe.approved" => "1"),
//            "order"      => "Recipe.created DESC",
            "dependent"    => true
        )
    );
    //对存储字段做限制
    var  $valitate=array(
        "activity_name"=>array(
            "maxlength"=>array(
                "rule"=>array("maxlength",20),
                "message"=>"活动名称20字以内",
            ),
            "notEmpty"=>array(
                "rule"=>"notEmpty",
                "message"=>"活动名称不能为空"
            )
        ),
        "product_name"=>array(
            "maxlength"=>array(
                "rule"=>array("maxlength",20),
                "message"=>"商品名称20字以内",
            ),
            "notEmpty"=>array(
                "rule"=>"notEmpty",
                "message"=>"商品名称不能为空"
            )
        ),
        "quantity"=>array(
            "notEmpty"=>array(
                "rule"=>"notEmpty",
                "message"=>"商品数量不能为空"
            ),
            "numRole"=>array(
                "rule"=>array("numRole","/^[1-9][0-9]{0,4}$/"),
                "message"=>"数量在1-99999之间"
            )
        ),
        "create_by"=>array(
            "rule"=>"notEmpty",
            "message"=>"创建人不能为空"
        ),
        "create_time"=>array(
            "rule"=>"notEmpty",
            "message"=>"创建时间不能为空"
        ),
        "exchange_times"=>array(
            "rule"=>array("changetime","/^[0-9]|[1-9][0-9]*$/"),
            "message"=>"只能为正整数"
        ),
        "thumb_img"=>array(
            "rule"=>array("extension",array( 'jpeg', 'png','jpg')),
        ),
        "banner_img"=>array(
            "rule"=>array("extension",array( 'jpeg', 'png','jpg')),
        ),
        "link_phone"=>array(
            "rule"=>array("phone","/^1[0-9]{10}$/"),
            "message"=>"手机号码位数不正确",
        ),
        "thumb_img"=>array(
            "rule"=>array("isFileExist"),
            'message'=>"商品图路径不正确",
        ),
         "banner_img"=>array(
            "rule"=>array("isFileExist"),
            'message'=>"banner路径不正确",
        )


    );
    function isFileExist($data){
         if(file_exists($data)){
             return true;
         }
         else{
                return  false;
         }
    }


}
