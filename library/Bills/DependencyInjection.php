<?php
/**
 * Bills_DependencyInjection
 * @author Lucian Hontau
 * @copyright Lucian Hontau
 * 
 * Manages dependency injection (currently only for the db initialization)
 */

class Bills_DependencyInjection
{

	//static variables for db instances (implementing a factory pattern for read/write dbs)
	protected static $readDb; //this would really be an array of db slaves to read from in a real app
	protected static $writeDb; //this could be an array of writeable masters, but this is unlikely

	/**
	 * Gets the application configuration
	 * 
	 * @return Zend_Config
	 */
	protected static function getConfig()
	{
		if (Zend_Registry::isRegistered('config')) {
			$appConfig = Zend_Registry::get('config');
		}
		else {
			throw new Exception('The application configuration is not available to the ' . __CLASS__ . ' class.');
		}

		if($appConfig instanceof Zend_Config) {
			return $appConfig;
		}

		return false;
	}

	/**
	 * Returns the read db object - this could return any number of objects but currently returns a singleton
	 *
	 * @return Zend_Db_Adapter
	 */
	public static function getReadDb()
	{
		$config = self::getConfig();
		if($config) {
			if(!isset(self::$readDb)) {
				try {
					$options = $config->db->readdb;					
					$readDb = new Bills_Model_BillsDb($options);
					self::$readDb = $readDb->getDb();
				}
				catch (Exception $e) {
					throw new Exception('The read database could not be initialized from the ' . __CLASS__ . ' class.');
				}
			}
			
			return self::$readDb;
		}
		else {
			throw new Exception('The application configuration is not available to the read database injection in the ' . __CLASS__ . ' class.');
		}
	}

	/**
	 * Returns the write db object - this could potentially return any number of objects (although it's less likely to have multiple write points) but currently returns a singleton
	 * 
	 * @return Zend_Db_Adapter
	 */
	public static function getWriteDb()
	{
		$config = self::getConfig();
		if($config) {
			if(!isset(self::$writeDb)) {
				try {
					$options = $config->db->writedb;
					$writeDb = new Bills_Model_BillsDb($options);
					self::$writeDb = $writeDb->getDb();
				}
				catch (Exception $e) {
					throw new Exception('The write database could not be initialized from the ' . __CLASS__ . ' class.');
				}
			}

			return self::$writeDb;
		}
		else {
			throw new Exception('The application configuration is not available to the write database injection in the ' . __CLASS__ . ' class.');
		}
	}

}
