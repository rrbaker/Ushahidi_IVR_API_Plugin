<?php defined('SYSPATH') or die('No direct script access.');
/**
 * IVR API Hook
 *
 * PHP version 5
 * @author	   John Etherton <john@ethertontech.com> 
 * @package	   IVR API
 */

class Ushahidi_IVR_API_Plugin {
	
	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{	
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
	}
	
	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{		
		if(Router::$controller=='reports' AND (Router::$method == 'view' OR Router::$method == 'edit'))
		{
			
			Event::add('ushahidi_action.report_extra',  array($this, 'show_ivr_history'));
			Event::add('ushahidi_action.report_form_admin',  array($this, 'show_ivr_history'));	
			plugin::add_stylesheet('ivr_api/css/ivr_api');		
			Event::add('ushahidi_action.header_scripts', array($this, 'add_js'));
			Event::add('ushahidi_action.header_scripts_admin', array($this, 'add_js'));
			
		}
		
		//get the export link up on the reports page
		Event::add('ushahidi_action.nav_admin_reports', array($this, 'reports_menu_link'));
	}
	
	
	/**
	 * Creates a link to the IVR export page on the reports menu on the admin side
	 */	 
	public function reports_menu_link()
	{
		$fusionsystem = new View("ivr_api/ivr-link");
		$fusionsystem->render(TRUE);
	}
	
	/**
	 * Creates the view of the IVR history
	 */
	public function show_ivr_history()
	{
		
		
		//get the incident_id
		$id = Event::$data;
		
		
		//make sure it's a valid id
		if($id == null || $id=="0" || $id==false)
		{
			return;
		}
		
		//get the IVR data that is associated with this incident
		$ivr_datas = ORM::factory('ivrapi_data')
			->where('incident_id',$id)
			->orderby('time_received', 'DESC')
			->find_all();
			
		//if there's no history, then bounce
		if(count($ivr_datas) == 0)
		{
			return;
		}			
		
		$view = View::factory('ivr_api/ivr_view');
		$view->ivr_datas = $ivr_datas;
		$view->render(TRUE);
		
		
	}
	
	/**
	 * Add the JS to make the history hide and appear
	 */
	public function add_js()
	{
		$user = $this->user = new User_Model($_SESSION['auth_user']->id);
		$view = View::factory('ivr_api/ivr_view_js');
		$view->user = $user;
		$view->render(TRUE);
	}
}

new Ushahidi_IVR_API_Plugin;
