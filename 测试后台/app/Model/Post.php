<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2015/7/9
 * Time: 13:44
 */

class post extends  AppModel{
    public $validate = array(
        'title' => array(
            'rule' => 'notEmpty'
        ),
        'body' => array(
            'rule' => 'notEmpty'
        )
    );


}