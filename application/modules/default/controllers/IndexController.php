<?php
/**
 * Index controller for the default module
 * 
 * @author Lucian Hontau
 * @copyright Lucian Hontau
 */
class Default_IndexController extends Bills_AppController
{

	//Stores our TwitterFeed object
	protected $twitterFeed;

	/**
	 * Get our model and store it
	 */
	public function init()
	{
		$this->twitterFeed = new Bills_Model_TwitterFeed();
	}

	/**
	 * The index controller will just forward to the pull controller which then forwards onto the display controller
	 */
	public function indexAction()
	{
		$this->_forward('display-Tweets', 'index', 'default');
	}

	/**
	 * Controller to pull tweets and store them into db.
	 * Forwards on to the display controller aferwards
	 */
	public function pullTweetsAction()
	{
		$this->twitterFeed->pullTweets('bills');
		print 100; die;
		$this->twitterFeed->insertTweets();
		$this->_forward('display-Tweets', 'index', 'default');
	}

	/**
	 * Main display controller
	 */
	public function displayTweetsAction()
	{
		$this->view->results = $this->twitterFeed->retrieveTweets();
	}

	/**
	 * AJAX action that returns a JSON object. Toggles the viewed/not viewed flag
	 * 
	 * @param int $id - twitter id
	 * @param int $status - boolean really but int for mysql purposes
	 */
	public function toggleTweetViewedAction()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('process', 'json')->initContext();

		$data = array('status' => 'started');
		$id = $state = '';
		
		if($this->_hasParam('id')) {
			$id = $this->_getParam('id');
			$data['id'] = $id;
		}
		else {
			$data['status'] = 'failed';
		}

		if($this->_hasParam('state')) {
			$state = $this->_getParam('state');
		}
		else {
			$data['status'] = 'failed';
		}

		$data['state'] = $state;

		if($data['status'] != 'failed') {
			$success = $this->twitterFeed->setViewedFlag($id, $state);
			$data['success'] = $success;
			if($success) {
				$data['status'] = 'success';
			}
		}
		
		$json = $this->_helper->json->sendJson($data);
	}

}

