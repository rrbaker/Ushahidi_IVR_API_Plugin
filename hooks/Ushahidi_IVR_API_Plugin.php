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
		
		$this->condition_mapping = array(
		'0'=>'tech_hand_pump',
		'1'=>'tech_other',
		'2'=>'financial',
		'3'=>'vandalism',
		'4'=>'water_qual',
		'5'=>'call_error',
		'6'=>'water_table',
		'7'=>'mechanic_awol',
		'8'=>'unknown',
		'9'=>'other');
		
		$this->time_mapping = array(
			'0'=>' AND ic.category_id = 26 ',
			'1'=>' AND ic.category_id = 25 ',
			'2'=>'',
			);
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
			Event::add('ushahidi_action.header_scripts_admin', array($this, 'add_js_admin'));
			
		}
		
		//get the export link up on the reports page
		Event::add('ushahidi_action.nav_admin_reports', array($this, 'reports_menu_link'));
		
		if(Router::$controller == "reports")
		{
			Event::add('ushahidi_filter.fetch_incidents_set_params', array($this,'_add_ivr_comment_filter'));
			
			Event::add('ushahidi_action.report_filters_ui', array($this,'_add_report_filter_ui'));
			
			Event::add('ushahidi_action.header_scripts', array($this, '_add_report_filter_js'));
		}		
	}
	
	/**
	 * This bit makes the reports::fetch_incidents() filter by IVR comments
	 */
	 public function _add_ivr_comment_filter()
	 {
			$params = $this->get_get_params();
			if($params['time'] != 'N/A')
			{
				$filter_params = Event::$data;
				
				//get the table prefix
				$table_prefix = Kohana::config('database.default.table_prefix');
				
				$sql =  'i.id IN (SELECT DISTINCT data.incident_id FROM '.$table_prefix.'ivrapi_data AS data ';
				$sql .= 'LEFT JOIN '.$table_prefix.'ivrapi_data_comments AS comments ON comments.ivr_data_id = data.id ';
				//don't bother if we don't care about time
				if($params['time'] != '2' )
				{
					$sql .= 'LEFT JOIN '.$table_prefix.'incident_category AS ic ON ic.incident_id = data.incident_id ';
				}
				
				//create the where text
				$i = 0;
				foreach($params['conditions'] as $key)
				{
					//skip this
					if($key == 'undefined')
						continue;
						
					$i++;
					if($i == 1){$sql .=' WHERE ';}
					if($i > 1) {$sql .= " AND ";}
					$sql .= $this->condition_mapping[$key]. ' = 1 ';
					
				}
				//deal with the time component
				$sql .= $this->time_mapping[$params['time']];
				
				$sql .= ' ) ';
				array_push($filter_params, $sql);
				Event::$data = $filter_params;			
			}

	 }//end _add_ivr_comment_filter
	 
	 /**
	  * Parses the get parameters when calling fetch_incidents
	  */
	 public function get_get_params()
	 {
		 //get the time
		$time = "N/A"; //default to current
		if ( isset($_GET['ivr_t'])  )
		{
			$time = $_GET['ivr_t'];
		}
		//get the condition
		$conditions = array();
		if ( isset($_GET['ivr_c']) AND is_array($_GET['ivr_c']) )
		{
			$conditions = $_GET['ivr_c'];
		}
		
		$ret_val = array('time'=>$time, 'conditions'=>$conditions);
		
		return $ret_val;
	 }
	
	/**
	 * This little guy will add the JS to the /reports page so we can filter based on comments
	 */
	public function _add_report_filter_js()
	{
			$view = new View('ivr_api/report_filter_js');
			$view->render(true);
	}
	
	/**
	 * Used to add the UI on the search page so that users can search reports
	 * by the comments
	 */
	public function _add_report_filter_ui()
	{
		$view = new View('ivr_api/report_filter_ui');
		$view->render(true);
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
			
		//get the comments that go along with this IVR data
		//start by getting the IDs of all the IVR items into a string
		$in_str = "";
		$i = 0;
		foreach($ivr_datas as $data)
		{
			$i++;
			if($i > 1){$in_str .= ',';}
			$in_str .= $data->id;
		}
		$comments = array();

		if($in_str != "")
		{
			//get the database prefix:
			$table_prefix = Kohana::config('database.default.table_prefix');
			//make up some SQL
			$sql = 'SELECT * FROM '.$table_prefix.'ivrapi_data_comments as comments ';
			$sql .= 'WHERE ivr_data_id IN ('.$in_str.') ';
			$sql .= 'ORDER BY ivr_data_id, added_on_date';
			$db = new Database();
			$query = $db->query($sql);
			
			//now put all of this into useful arrays
			foreach($query as $comment)
			{		
				if(!isset($comments[$comment->ivr_data_id]))
				{
					$comments[$comment->ivr_data_id] = array();
				}
				$comments[$comment->ivr_data_id][] = $comment;
			}
		}

		
			
		//if there's no history, then bounce
		if(count($ivr_datas) == 0)
		{
			return;
		}			
				
		$view = View::factory('ivr_api/ivr_view');
		$view->can_comment = admin::permissions(new User_Model($_SESSION['auth_user']->id), "reports_edit");
		$view->ivr_datas = $ivr_datas;
		$view->comments = $comments;
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
		$view->on_admin = false;
		$view->render(TRUE);
	}
	
	/**
	 * Add the JS to make the history hide and appear
	 * Called when we're on the admin side
	 */
	public function add_js_admin()
	{
		$user = $this->user = new User_Model($_SESSION['auth_user']->id);
		$view = View::factory('ivr_api/ivr_view_js');
		$view->user = $user;
		$view->on_admin = true;
		$view->render(TRUE);
	}
}

new Ushahidi_IVR_API_Plugin;
