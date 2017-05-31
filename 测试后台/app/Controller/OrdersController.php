<?php
/**
 * User Controller
 *
 *
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

class OrdersController extends AppController{
	public $name = 'Orders';
	public $helpers = array('Html', 'Form', 'Page');
	
	public $components = array('Session');
	public $uses = array('Order');
	
	// 当前用户ID
	public $uid = 0;

	// 当前用户名称
	public $userName = 'nobody';
	
	public function beforeFilter () {
		parent::beforeFilter();
		//$this->Auth->allow(null);
		//todo登录权限判断并得到当前用户的信息	
	}
/**
 * 工单列表
 */
	public function index(){
		$conditions = $this->request->query['searchName'] ? array( 'title like'=>'%' .  $this->request->query['searchName'] . '%' ) : array();

		$this->set( 'searchName',  $this->request->query['searchName'] ? : '' );
		$this->set( 'Orders', $this->paginate( 'Order', $conditions ) );

	}
	
	/**
	 * 创建工单选择流程
	 * @return [type] [description]
	 */
	public function choose_step() {
		//todo 前台展示需要的所有流程 数据从工作流接口获取
		
		//通过接口获取nmc的角色列表
		$ApiComponent = $this->Components->load( 'Api' );

		$patams = array();

		$roleDatas = $ApiComponent->exec( WORKFLOW_INTERFACE_URL . '/template
', $patams );
		if(empty($roleDatas)){
			$roleDatas = array();
		}		
		$this->set( compact( 'roleDatas' ) );

	}

/**
 * 工单添加
 */
	public function add( $workflowName = '' ){
		$ApiComponent = $this->Components->load( 'Api' );

		if( $this->request->data && $this->request->is('post') ){
			$this->request->data['Order']['uid'] = $this->Auth->user('uid');
			$this->request->data['Order']['author'] = $this->Auth->user('username');
			$this->request->data['Order']['workflow_name'] = $workflowName;
			if(@$order = $this->Order->save($this->request->data)) {
				/*用户与流程步骤绑定开始*/
				unset($this->request->data['Order']);			
				/*写入相关用户*/
				$this->loadModel('Workflow');
				$this->loadModel('Task');/*工单任务加入*/
				foreach( $this->request->data as $k=>$v ) {
					$saveDatatask = array('title'=>$order['Order']['title'],'desc'=>$order['Order']['desc'],'priority'=>$order['Order']['priority'],'lasttime'=>date('Y-m-d H:i:s',$order['Order']['lasttime']),'uid'=>$order['Order']['uid'],'author'=>$order['Order']['author'],'order_id'=>$order['Order']['id'],'workflow_step'=>$k,'workflow_name'=>$order['Order']['workflow_name']);
					$this->log($saveDatatask);
					$task = $this->Task->save($saveDatatask);
					/*操作日志记录*/
					$this->_setLogs($task['Task']['id'], $task['Task']['author'], $task['Task']['uid'], "创建任务");
					/**/
						if( $v !== ' ' ) {
							$uids = explode( ',', $v );	
							
							foreach( $uids as $uid ) {	
								$saveData = array( 'oid'=>$task['Task']['id'], 'uid'=>$uid, 'workflow'=>$k, );
								$this->Workflow->save( $saveData );
								$this->Workflow->id = null;
							}		
						}
						$this->Task->id = null;
				}
			//获取工作流ID开始
			$patams = array( 'uid'=> $this->Auth->user('uid'), 'data'=>'null' );
			$addWorkflowResult = $ApiComponent->exec( WORKFLOW_INTERFACE_XUANTI_URL, $patams );
			$workflowID = $addWorkflowResult['choose_1_piid'];
			$this->Order->save( array( 'id'=>$order['Order']['id'] , 'workflow_id'=>$workflowID ) );
			$data = array('workflow_id' => $workflowID); 
			$condition = array('Task.order_id' => $order['Order']['id']);  
			$this->Task->updateAll($data,$condition);
			//获取工作流ID处理后结束
				$this->Session->setFlash(__('Add Success'),'alert');
				$this->redirect(array('action'=>'/index'));
			}else{
				$this->Session->setFlash(__('Add Fail'),'alert');
				$this->redirect(array('action'=>'/add'));
			}
		}

		//通过接口获取工作流的详细步骤 
		//todo 获取流程及对应步骤后可以考虑缓存到工单模块中
		

		$patams = array( 'process_name'=> $workflowName );

		$roleDatas = $ApiComponent->exec( WORKFLOW_INTERFACE_URL . '/process_assign
', $patams );

		//$roleDatas = json_decode('{"选题策划流程_添加稿件":["角色A","角色B"],"选题策划流程_123456":["角色A"],"选题策划流程_选题":["角色A","角色B"],"选题策划流程_上传图片":["角色B"],"选题策划流程_上传音频":["角色B","角色A"],"选题策划流程_上传视频":["角色B","角色A"]}', true);
		// //todo步骤数据、每个步骤推荐的可见人员 从工作流接口获取
		$this->log($roleDatas);
		
		if(!empty($roleDatas)){
			$workflowSteps = array_keys( $roleDatas );
			foreach( $workflowSteps as &$value ) {
				$value = explode( '_', $value );
				$value = $value[1];
			}
		}else{
			$workflowSteps = array();
		}
		$NmcApiComponent = $this->Components->load( 'NmcAPI' );
		
		//获取网管中心所有角色
		$roles =  $NmcApiComponent->exec('getRoles',array('type'=>1,'uid'=>$this->Auth->user('uid')));
		
		$ids = array();
		$ids = array_map('array_shift', $roles);
		$ids =  implode(',',$ids);
		
		$rolesArray =  $NmcApiComponent->exec('getRoleUsers',array('type'=>1,'uid'=>$this->Auth->user('uid'), 'roleId'=>$ids));
		$roles = json_encode( $rolesArray, true );

		//获取网管中心所有用户
		$users = $NmcApiComponent->exec('getUserList',array('type'=>1,'uid'=>$this->Auth->user('uid'), 'page'=>0, 'pageSize'=>0));
		
		$this->set( compact( 'roleDatas', 'workflowSteps', 'roles', 'users', 'rolesArray' ) );

	}

/**
 * 工单修改
 *
 */
	public function edit($id = null){
		
		$this->Order->id = $id;

		if ($this->request->is('get')) {

			$this->request->data = $this->Order->read();


		} else {

			$this->request->data['Order']['updatetime'] = time();

			if ( $this->Order->Save( $this->request->data ) )
			{
				//todo记录编辑工单的日志
				
				$this->Session->setFlash(__('Edit Success'),'alert');

				$this->redirect(array('action' => 'index'));

			} else {

				$this->Session->setFlash(__('Edit Fail'),'alert');

			}

		}

	}



/**
 * 删除工单
 * @param int $id
 */
	public function del($id = null) {

		if ($this->request->is('get')) {		
			throw new MethodNotAllowedException();		
		}
		//删除工单标记 工单和其对应的子任务也会删除
		if ($this->Order->Save( array( 'is_deleted'=>1, 'id'=>$id ) )) {

		//todo 更新日志	
		
			$this->Session->setFlash(__('Del Success'),'alert');		
			$this->redirect(array('action' => 'index'));		
		}

	}

	/**
	 * 工单状态预览
	 * @param  [type] $oid 工单ID
	 * @return [type]      [description]
	 */
	function preview( $oid = null ) {
		//工单预览
		
		//todo获取工单状态
		
	}


	/**
	 * 工单 修改操作 [创建者]
	 * @param  int $oid 工单ID
	 * @param   $status 工单修改状态 1 催单 2 重新指派
	 * @return [type]      [description]
	 */
	function order_back( $oid = null, $status = 1 ) {

		//todo修改工单状态status 重新指派人current_uid修改 [orders] 
		
		//todo修改日志状态 [logs]

	}


	/**
	 * 工单日志查询
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function logs( $id = null ) {

		$this->loadModel( 'Log' );

		$logs = $this->Log->findByoid( $id );

		$this->set( compact( 'logs' ) );
	} 

	/**
	 * 工单状态
	 * @param  int $oid 工单ID
	 * @return [type]      [description]
	 */
	public function order_status ( $oid = null ) {

		$orderDetail = $this->Order->findByid( $oid );

		$this->set( compact( 'orderDetail' ) );
	}

	/**
	 * 接受者工作台
	 * @return [type] [description]
	 */
	public function my_orders () {
		
		//todo 查询自己能看到的流程任务

	}


	/**
	 * 工作流交互接口 
	 */

	public function workflow_interface() {
		//根据工作流的状态更新调此接口，更新状态
		$this->autoRender = false;
		//$this->log($this->request);
		// $postData = '{"workflow_id":"123456","task_name":"选题","task_status":"start or finish","pool_actors":[{"id":"111","name":"角色A"},{"id":"222","name":"角色B"},{"id":"333","name":"角色C"}]}';

		$postData = $this->request->data['parameter'];
		$postData = json_decode( $postData,  true );
		$taskStatus = $postData['task_status']?:'';
		$taskName = $postData['task_name']?:'';
		$workflow_id = $postData['workflow_id']?:'';
		$taskRoleGroup = $postData['pool_actors']?:'';
		$uid = $postData['actor_id']?:'0';
		$author = $postData['actor_name']?:'';
		$this->loadModel('Task');/*工单任务加入*/
				//todo 判断这个步骤任务的查看权限 维护表users_workflows by heyuhan	
				
		
				$data = array('Task.status' => '"'.$taskStatus.'"', 'Task.current_uid' =>$uid);
				$condition = array('Task.workflow_id' => $workflow_id, 'Task.workflow_step' => $taskName);
				$this->Task->updateAll($data,$condition);
				/*操作记录*/
				$return = $this->Task->find('first', array(
						'conditions' => array('Task.workflow_id' => $workflow_id, 'Task.workflow_step' => $taskName)
				));
				switch ( $taskStatus ) {
					//1:创建任务 create
					case 'create':
						$desc = "建立任务可见状态";
						break;
					case 'start':
						$desc = "领取任务";
						break;
					case 'end':
						$desc = "完成任务";
						break;
					case 'reject':
						$desc = "驳回任务";
						break;
				}
				$this->_setLogs($return['Task']['id'], $author, $uid, $desc);
				/*操作记录*/
		//todo 更新logs 日志状态
		echo 1;

	}
/*
 * 工作流交互任务新增
 * 
 */
	function task_add(){
		$this->autoRender = false;
		
		$postdatatwo = $this->request->data['parameter'];
		$postdatatwo = json_decode($postdatatwo, true);
		$workflow_id = $postdatatwo['work_flow_id'];//工作流ID
		$task_detail = $postdatatwo['task_detail'];//新增任务array
		$order = $this->Order->find( 'first', array( 'conditions'=>array('workflow_id'=>$workflow_id),'contain'=>array()));
		/*通知创建任务start*/
		foreach ($task_detail as $k =>$value){
			if(empty($order)){
				$title = $value['title'];
				$order_id = 0;
				$workflow_name = $value['workflow_name'];
			}else{
				$title =$order['Order']['title'];
				$order_id = $order['Order']['id'];
				$workflow_name = $order['Order']['workflow_name'];
			}	
			$saveDatatask = array('title'=>$title,'lasttime'=>date('Y-m-d H:i:s',$value['lasttime']),'uid'=>$value['uid'],'author'=>$value['author'],'order_id'=>$order_id,'workflow_id'=>$workflow_id, 'workflow_step'=>$value['workflow_step'],'workflow_name'=>$workflow_name, 'status' =>$value['status'] );
			$this->loadModel('Task');
			$this->loadModel('Workflow');
			$task = $this->Task->save($saveDatatask); 
			/*操作记录*/
			switch ( $task['Task']['status'] ) {
				//1:创建任务 create
				case 'create':
					$desc = "创建任务且可见状态";
					break;
				case 'start':
					$desc = "领取任务";
					break;
				case 'end':
					$desc = "完成任务";
					break;
				case 'reject':
					$desc = "驳回任务";
					break;
				default:
					$desc = "创建任务";
			}
			$this->_setLogs($task['Task']['id'], $task['Task']['author'], $task['Task']['uid'], $desc);
			/*操作记录*/
			if($value['current_uid'] !== ' ' ) {
				foreach( $value['current_uid'] as $current_uid ) {
					$saveData = array( 'oid'=>$task['Task']['id'], 'uid'=>$current_uid, 'workflow'=>$value['workflow_step'], );
					$this->Workflow->save( $saveData );
					$this->Workflow->id = null;
				}
			}
			$this->Task->id = null;
		}
		/*通知创建任务end*/
		echo 1;
	}
	/**
	 * 工作流通知工单结束状态
	 * 
	 */
	function order_end(){
		$this->autoRender = false;
		$postdatathree = $this->request->data['parameter'];
		$postdatathree = json_decode( $postdatathree,  true );
		$taskStatus = $postdatathree['task_status']?:'';
		$workflow_id = $postdatathree['workflow_id']?:'';
		$data = array('Order.status' => '"'.$taskStatus.'"');
		$condition = array('Order.workflow_id' => $workflow_id);
		$this->Order->updateAll($data,$condition);
	}
	/**
	 * 选题模块任务数据
	 * @param  integer $uid 用户id
	 * @return json       选题策划需要的相关数据
	 */
	public function xuanti_interface( $uid = 0 ) {

		// todo 提供给选题模块的任务数据
	} 

}
?>