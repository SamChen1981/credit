<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */ 
    Router::connect('/', array('controller' => 'products', 'action' => 'index'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
    Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

    //获取商品列表
    Router::connect(
        "/creditshop/goodsList",
        array("controller"=>"Interface","action" => "goodsList", "[method]" => "GET")
    );
    //获取商品详细信息
    Router::connect(
        "/creditshop/goodsDetail/:gid",
        array("controller"=>"Interface","action" => "goodsDetail", "[method]" => "GET"),
        array("gid" => "[0-9]+")
    );
    //获取用户兑换记录列表
    Router::connect(
        "/creditshop/exchangeList/:uid",
        array("controller"=>"Interface","action" => "exchangeList", "[method]" => "GET"),
        array("uid" => "[0-9]+")
    );
    //获取奖品详细信息
    Router::connect(
        "/creditshop/exchangeDetail/:rid",
        array("controller"=>"Interface","action" => "exchangeDetail", "[method]" => "GET"),
        array("rid" => "[0-9]+")
    );
    //兑换商品
    Router::connect(
        "/creditshop/exchange/:gid",
        array("controller"=>"Interface","action" => "exchange", "[method]" => "POST"),
        array("gid" => "[0-9]+")
    );
    //接收第三方系统中奖信息
    Router::connect(
        "/creditshop/addPrize/:uid",
        array("controller"=>"Interface","action" => "addPrize", "[method]" => "POST"),
        array("uid" => "[0-9]+")
    );
    //获取积分商城首页置顶banner
    Router::connect(
        "/creditshop/topBanners",
        array("controller"=>"Interface","action" => "topBanners", "[method]" => "GET")
    );
//    Router::connect(
//        "/creditshop/show",
//        array("controller"=>"Tests","action" => "show", "[method]" => "GET")
//    );
    Router::connect(
        "/creditshop/selectReceivAddress/",
        array("controller"=>"Interface","action" => "selectReceivAddress", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/getMember/",
        array("controller"=>"Interface","action" => "getMember", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/getAddress/",
        array("controller"=>"Interface","action" => "getAddress", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/getArea/",
        array("controller"=>"Interface","action" => "getArea", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/saveAddress/",
        array("controller"=>"Interface","action" => "saveAddress", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/selectAddress/",
        array("controller"=>"Interface","action" => "selectAddress", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/delAddress/",
        array("controller"=>"Interface","action" => "delAddress", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/getOrdersInfo/",
        array("controller"=>"Interface","action" => "getOrdersInfo", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/getOrdersList/",
        array("controller"=>"Interface","action" => "getOrdersList", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/getUid/",
        array("controller"=>"Interface","action" => "getUid", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/show",
        array("controller"=>"Interface","action" => "show", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/getSessionID",
        array("controller"=>"Interface","action" => "getSessionID", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/confirmOrders/",
        array("controller"=>"Interface","action" => "confirmOrders", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/getUserInfo/",
        array("controller"=>"Interface","action" => "getUserInfo", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/getSignPackage/",
        array("controller"=>"Interface","action" => "getSignPackage", "[method]" => "POST")
    );
    Router::connect(
        "/creditshop/checkgoodsId/",
        array("controller"=>"Interface","action" => "checkgoodsId", "[method]" => "POST")
    );
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
    CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
    require CAKE . 'Config' . DS . 'routes.php';
