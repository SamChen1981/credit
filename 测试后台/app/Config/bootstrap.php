<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
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
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.

CakePlugin::load(array('RedisCache' => array('bootstrap' => true)));
Cache::config('_creditshop_',
 array(
 	'engine' => 'RedisCache.Redis',
 	'prefix' => 'creditshop_',
 	'duration' => '+1 days'
 	));
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 *
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */

/**
 * To prefer app translation over plugin translation, you can set
 *
 * Configure::write('I18n.preferApp', true);
 */

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyCacheFilter' => array('prefix' => 'my_cache_'), //  will use MyCacheFilter class from the Routing/Filter package in your app with settings array.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 *		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging option
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));
if(!defined('MODELS_NAME')) define('MODELS_NAME', 'creditshop');

if(!defined('NMC_INTERFA CE_URL')) define('NMC_INTERFACE_URL', 'http://www.net.cc/interface/');

if(!defined('WORKFLOW_INTERFACE_URL')) define('WORKFLOW_INTERFACE_URL', 'http://172.16.146.27:8080/crms-workflow/common');

if(!defined('WORKFLOW_INTERFACE_XUANTI_URL')) define('WORKFLOW_INTERFACE_XUANTI_URL', 'http://172.16.146.27:8080/crms-workflow/workflow/choose/new
');
if(!defined('ORDER_URL')) define('ORDER_URL', 'http://www.hyhorder.dev/');

if(!defined('SHAKE_API_URL')) define('SHAKE_API_URL','http://113.142.30.203:82');
if(!defined('CRED_URL')) define('CRED_URL','');

if(!defined("IMG_URL")) define('IMG_URL','http://113.142.30.203:81/app/webroot');
//if(!defined("IMG_URL")) define('IMG_URL','http://test.yang_jifen.com/app/webroot');
if(!defined('ROOT1')) define('ROOT1','http://113.142.30.203:81');
if(!defined('APP_SECRET')) define('APP_SECRET','02bcebf67be82fc6c596f7aaab159833090189d8');
//租户信息读取地址
//if(!defined('TENANT_URL'))define('TENANT_URL','http://tenant.wx.sztv.com.cn/interface/getTenantEnv');
if(!defined('TENANT_URL'))define('TENANT_URL','http://devtenant.sobeycloud.com:8008/interface/getTenantEnv');
//模块在租户模块中的名称
if(!defined('APP_NAME'))define('APP_NAME','creditshop');
//会员接口地址
//if(!defined('PIC_PORT'))define('PIC_PORT','84');
if(!defined('PIC_PORT'))define('PIC_PORT','8006');
//会员接口地址
//if(!defined('MEMBER_URL'))define('MEMBER_URL','http://memberrest.wx.sztv.com.cn:8080/wayu-rest/');
// if(!defined('MEMBER_URL'))define('MEMBER_URL','http://memberrest-vip.sobeycloud.com:8080/memberapi/api');
// if(!defined('MEMBER_URL'))define('MEMBER_URL','http://memberrest.sobeycloud.com:8080/memberapi/api');
// if(!defined('MEMBER_URL'))define('MEMBER_URL','http://wjmemberi.sobeycloud.com:80/memberi/api');
if(!defined('MEMBER_URL'))define('MEMBER_URL','http://dev.devmemberi.sobeycloud.com:8002/memberapi/api');


//权限中心接口地址
//if(!defined('NMC_URL'))define('NMC_URL','http://ida.wx.sztv.com.cn/Interface/');
// if(!defined('NMC_URL'))define('NMC_URL','http://ida.sobeyyun.com/Interface/');
if(!defined('NMC_URL'))define('NMC_URL','http://devida.sobeycloud.com:8009/Interface');
//网站接口，默认80
//if(!defined("SERVER_PORT"))define("SERVER_PORT","84");
if(!defined("SERVER_PORT"))define("SERVER_PORT","8006");
if(!defined("BASE_URL"))define("BASE_URL","test.yang_jifen.com/app");
if(!defined("app_keys"))define("app_keys",'okqb7vqm8y2I6Q7zeyyY9K1JfTzs');
if(!defined("wx_url"))define("wx_url",'mp-vip.sobeycloud.com');
if(!defined('UP_DIR'))define('UP_DIR','/webtv/wjdev/wangjie/credit');
if(!defined('FILE_DIR'))define('FILE_DIR','/data/wwwroot/h5jifen/files/default');
if(!defined('PIC_URL'))define('PIC_URL','http://devmserver.sobeycloud.com:81/credit');
