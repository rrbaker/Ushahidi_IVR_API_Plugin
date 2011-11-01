<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Performs install/uninstall methods for the Ushahidi-IVR-API-Plugin plugin
 *
 * PHP version 5
 * @author	   John Etherton <john@ethertontech.com> 
 * @module	   Ushahidi-IVR-API-Plugin Installer
 * @website	   https://github.com/rrbaker/Ushahidi-IVR-API-Plugin
 */

class Ushahidi_IVR_API_Plugin_Install {

	/**
	 * Constructor to load the shared database library
	 */
	public function __construct()
	{
		$this->db = Database::instance();
	}

	/**
	 * Creates the required database tables for the smssync plugin
	 */
	public function run_install()
	{
		// Create the database tables.
		// Also include table_prefix in name
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'ivrapi_data` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `incident_id` int(11) NOT NULL,
				  `ivr_code` varchar(200) default NULL,
				  `file_name` varchar(512) default NULL,
				  `phone_number` varchar(200) default NULL,
				  `mechanic_aware` tinyint(4) default 1,
				  `can_fix` tinyint(4) default 1,
				  `well_working` tinyint(4) default 1,
				  `time_received` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,				  				  
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
	}

	/**
	 * Deletes the database tables for the actionable module
	 */
	public function uninstall()
	{
		/** 
		 * It scares me too much to let any old user on the backend click deactivate and loose all their data, so 
		 * I just don't make it that easy.
		 */
	}
}