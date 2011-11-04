<?php
/**
 * Bills_Model_TwitterFeed
 * 
 * @author Lucian Hontau
 * @copyright Lucian Hontau
 *
 * Class to pull feeds from twitter, insert them into the database, and read them from the database
 */

class Bills_Model_TwitterFeed
{

	//Last pulled tweets
	protected $tweets;
	protected $table = 'tweets';
	protected $fields = array('id', 'text', 'is_viewed', 'from_user', 'profile_image_url', 'created_at');

	public function __construct() {}

	/**
	 * Pulls Twitter feeds and storest them in an array
	 *
	 * @param string $searchString string to search fo
	 * @return array Array of Tweets from the JSON pull
	 */
	public function pullTweets($searchString)
	{
		try {
			$twitterSearch  = new Zend_Service_Twitter_Search('json');
			$this->tweets  = $twitterSearch->search($searchString, array('lang' => 'en', 'rpp' => 100)); //get 100 results to make it more interesting
			return $this->tweets;
		}
		catch (Exception $e) {
			throw new Exception('Could not pull tweets from twitter.');
		}
	}

	/**
	 * Pulls tweets from twitter and inserts them into the db.
	 * Duplicate tweets are not allowed by the db (unique index on the text field (tweet content) and the tweet id (primary key)
	 *
	 * @return void
	 */
	public function insertTweets()
	{
		$tweets = $this->tweets;
		if(is_array($tweets) && array_key_exists('results', $tweets)) {
			//better ways DI could be done but it's a small sample app, so we will treat all DB connections as one offs
			$writeDb = Bills_DependencyInjection::getWriteDb();

			foreach($tweets['results'] as $tweet) {
				$writeData = array();
				foreach($this->fields as $value) {
					if($value != 'is_viewed') { //skip is_viewed as this is not a field that comes from twitter
						if($value == 'created_at') {
							$writeData[$value] = strtotime($tweet[$value]);
						}
						else {
							$writeData[$value] = $tweet[$value];
						}
					}
				}

				try {
					$writeDb->insert('tweets', $writeData);
				}
				catch (Exception $e) {}
			}
		}
	}

	/**
	 * Get all the records in the table for all rows
	 *
	 * @return array $result All the db records
	 */
	public function retrieveTweets()
	{
		$readDb = Bills_DependencyInjection::getReadDb();
		//retrieve all rows and all columns and order them reverse cronologically...
		//normally you'd never do this, but we'll be using all the data in the view so it actually makes sense
		$sql = "SELECT * FROM {$this->table} ORDER BY `created_at` DESC;"; 
		$result = $readDb->fetchAll($sql);
		return $result;
	}

	/**
	 * Sets the viewed flag to on or off for a specific ID
	 * 
	 * @param int $id (the tweet id)
	 * @param mixed $state (boolean viewed or not)
	 * @return bool Success or not
	 */
	public function setViewedFlag($id, $state)
	{
		$state = (int)$state; //explicitly convert to int for the tinyint column

		if(!is_numeric($id)) { //id is not a number so it's invalid
			return false;
		}

		if($state != 0 && $state != 1) { //invalid state
			return false;
		}

		$viewedData = array('is_viewed' => $state);
		$where['id = ?'] = $id;

		$writeDb = Bills_DependencyInjection::getWriteDb();
		$writeDb->update($this->table, $viewedData, $where);
		
		return true;
	}
	
}
