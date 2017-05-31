<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2015/7/22
 * Time: 9:34
 */
class ProductLog extends AppModel{
      var $name="ProductLog";
       public  $belongsTo="Product";

      var $validate=array(

      );


}