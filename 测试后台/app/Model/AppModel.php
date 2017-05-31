<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');
App::uses('Sequence', 'Model');
App::uses('Tenant', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

    private function getDbConfig($tenantid){
         if(!($conn = Cache::read($tenantid,'_cake_config_'))){
            $tenant = new Tenant();
            $conn = $tenant->getTenantEnv($tenantid);
            $this->log($conn);
            Cache::write($tenantid,$conn,'_cake_config_');
        }
        return $conn;
    }

	public function setDataSource($dataSource = null) {
        if($_GET['tenantid']){
            $tenantid = [addslashes($_GET['tenantid'])];
        }else{
            preg_match('@^(?:http://)?([^/]+)@i', FULL_BASE_URL, $matches);
            $tenantid = explode('.', $matches[1]);
        }
        $conn = $this->getDbConfig($tenantid[0]);
        if(!$conn) die('系统错误506，无法链接数据库');

		$this->useDbConfig = $dataSource = 'tmp';
		$oldConfig = $this->useDbConfig;

		if ($dataSource) {
			$this->useDbConfig = $dataSource;
		}

		$config = new stdClass();
        $config->tmp = array(
           'datasource' => 'Database/Mysql',
           'persistent' => false,
           'host' =>$conn['db_info']['host'],
           'login' => $conn['db_info']['login'],
           'password' => $conn['db_info']['password'],
           'database' => $conn['db_info']['database'],
           'port' => 3306,
           'prefix' => 'operation_',
           'encoding' => 'utf8'
       );
		// if(!$conn){
        // $config->tmp = array(
        //     'datasource' => 'Database/Mysql',
        //     'persistent' => false,
        //     'host' => '101.201.234.5',
        //     'login' =>'root',
        //     'password' =>'sobey-123',
        //     'database' =>"credit",
        //     'port' => 3306,
        //     'prefix' => 'operation_',
        //     'encoding' => 'utf8'
        // );
		// }
//        $this->log($config->tmp);
        ConnectionManager::$config = $config;
		$db = ConnectionManager::getDataSource($this->useDbConfig);
		if (!empty($oldConfig) && isset($db->config['prefix'])) {
			$oldDb = ConnectionManager::getDataSource($oldConfig);

			if (!isset($this->tablePrefix) || (!isset($oldDb->config['prefix']) || $this->tablePrefix === $oldDb->config['prefix'])) {
				$this->tablePrefix = $db->config['prefix'];
			}
		} elseif (isset($db->config['prefix'])) {
			$this->tablePrefix = $db->config['prefix'];
		}
		$schema = $db->getSchemaName();
		$defaultProperties = get_class_vars(get_class($this));
		if (isset($defaultProperties['schemaName'])) {
			$schema = $defaultProperties['schemaName'];
		}
		$this->schemaName = $schema;
     //pr($config->tmp);  
	}

/**
 * 序列查询获取自增
 * @var sqlname
 */
	public function sequence($sqlname){
		$data['Sequence']['seq_name'] = $sqlname;
		$data['Sequence']['seq_value'] = 1;
		$Sequences = new Sequence();
		$vv = $Sequences->findBySeqName($sqlname);
		if (!empty($vv)){
			$Sequences->id = $sqlname;
			$sequence = $Sequences->read();
			
			$Sequences->saveField('seq_value', $sequence['Sequence']['seq_value']+1);
			return $vv['Sequence']['seq_value']+1;
		}else{
			$Sequences->save($data);
			return 1;
		}	
	}

}
