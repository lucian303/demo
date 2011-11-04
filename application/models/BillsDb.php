<?php
/**
 * @class Bills_Model_BillsDb
 * @author Lucian Hontau
 * @copyright Lucian Hontau
 * 
 * This should be called through the DependencyInjection class
 */

class Bills_Model_BillsDb  {

	//Stores the db adapter and options
	protected $db;
	protected $options;

    public function __construct($options) {
		$this->initDB($options);
	}

	/**
	 * Initializes a db connection from a Zend_Config object or array
	 * 
	 * @param Zend_Config or array $options
	 * @return Zend_Db_Adapter
	 */
	public function initDB($options) {
		try {
			$this->options = $options;
			$this->db = Zend_Db::factory($options);			
			return $this->db;
		}
		catch (Exception $e) {
			throw new Exception('Could not get database connection.');
		}
	}

	/**
	 * Returns the adapter created by initDB
	 * 
	 * @return Zend_Db_Adapter
	 */
	public function getDb()
	{
		return $this->db;
	}
	
}
