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

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class Order extends Model {
	public $actsAs = array('Containable');
	public $name = 'Order';
	public $uid=50;

	public $hasMany = array(
			'Task' => array(
					'className'    => 'Task',
					'foreignKey'=>'order_id',
					//'conditions'   => array('now() between begintime and endTime'),
					//'dependent'    => true
					//'associationForeignKey' => 'oid',
			)
	);

	function beforeSave( $options = array()  ) {

		//新加
		if( empty( $this->data['Order']['addtime'] ) && empty( $this->data['Order']['updatetime'] ) ) {
			$this->data['Order']['addtime'] = time();
			$this->data['Order']['updatetime'] = time();
		}
		// 编辑
		if( !empty( $this->data['Order']['updatetime'] ) ) $this->data['Order']['updatetime'] = time();

		if( !empty( $this->data['Order']['lasttime'] ) ) $this->data['Order']['lasttime'] = strtotime( $this->data['Order']['lasttime'] );

		return true;

	}

}
