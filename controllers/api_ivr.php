<?php defined('SYSPATH') or die('No direct script access.');
/**
 * This controller handles the USAID ivr api functions
 * @copyright  Konpa Group - http://konpagroup.com
 */

class Api_Ivr_Controller extends Controller {
	public $auto_render = TRUE;
	
	private $form_fields = array(
		'ivrcode' => 'IVR Code',
		'phonenumber' => 'Phone Number',
		'mechanicknow' => 'Is the mechanic aware?',
		'mechanicfix' => 'Can the mechanic fix the issue?',
		'filename' => 'Voice Message');

	private $wellstatus = array(
		'functioning' => 'Functioning Well', 
		'malfunctioning' => 'Malfunctioning Well');

	private $form_answers = array();
	
	private $errors_found = false;
	
	private $response = array('status'=>'OK','message'=>array());
	
	private $resp = "json";

	public function __construct()
    {
        parent::__construct();
    }
	
    public function index()
    {
		
    	//make sure we received all the data we need, and that it's properly formatted.
    	$this->validate_input();

    	//find the incident id that corresponds to the IVR code we have
    	$incident_id = $this->get_incident_id();

		//update the well status
		$ivr_data = ORM::factory("ivrapi_data");
		
		$ivr_data->incident_id = $incident_id;
		$ivr_data->ivr_code = $this->form_answers['ivrcode'];
		if(isset($this->form_answers['filename']))
		{
			$ivr_data->file_name = $this->form_answers['filename'];
		}
		$ivr_data->phone_number = $this->form_answers['phonenumber'];
		if(isset($this->form_answers['mechanicknow']))
		{
			$ivr_data->mechanic_aware = $this->form_answers['mechanicknow'] == 'yes' ? 1 : 0;
		}
		else
		{
			$ivr_data->mechanic_aware = 2;
		}
		if(isset($this->form_answers['mechanicfix']))
		{
			$ivr_data->can_fix = $this->form_answers['mechanicfix'] == 'yes' ? 1 : 0;
		}
		else
		{
			$ivr_data->can_fix = 2;
		}
		$ivr_data->well_working = $this->form_answers['wellwork'] == 'yes' ? 1 : 0;
		//$ivr_data->time_received = date("Y-m-d H:i:s"); //this never stores the right date in the DB, don't know why
		$ivr_data->save();
		
		
		//update the category this well falls under
		$this->update_categories($incident_id, $ivr_data);
		
		$this->send_response($this->response,$this->resp);
   	}	
   	
   	/**
   	 * This function updates the categories associated
   	 * with this well
   	 * @param $incident_id the id of the report for this well
   	 * @param $ivr_data the data we just got from the IVR
   	 * @return none
   	 */
   	private function update_categories($incident_id, $ivr_data)
   	{
   		//find the two categories that apply to wells and IVR data
   		$functioning_category = ORM::factory('category')->where('category_title',$this->wellstatus['functioning'])->find();
		if(! $functioning_category->loaded){
			$this->response['status'] = 'Error';
			$this->response['message'][] = "Could not find well functioning category: " . $this->wellstatus['functioning'];
			$this->errors_found = TRUE;
			return;
		}

		$malfunctioning_category = ORM::factory('category')->where('category_title',$this->wellstatus['malfunctioning'])->find();
		if(! $malfunctioning_category->loaded){
			$this->response['status'] = 'Error';
			$this->response['message'][] = "Could not find well malfunctioning category: " . $this->wellstatus['malfunctioning'];
			$this->errors_found = TRUE;
			return;
		}
		
		//now remove any current category associates between these two categories and our well
		ORM::factory('incident_category')
			->where(array('category_id'=> $functioning_category->id, 'incident_id'=>$incident_id))
			->delete_all();
						
		ORM::factory('incident_category')
			->where(array('category_id'=> $malfunctioning_category->id, 'incident_id'=>$incident_id))
			->delete_all();
		
		//now add the correct category
		$chosen_cat_id = $malfunctioning_category->id;
		if($ivr_data->well_working)
		{
			$chosen_cat_id = $functioning_category->id;			 
		}
		$cat = ORM::factory('incident_category');
		$cat->incident_id = $incident_id;
		$cat->category_id = $chosen_cat_id;
		$cat->save();
   	}
   	
   	/**
   	 * This function finds the incident id that the IVR code goes with
   	 * @param unknown_type $this->response reponse array
   	 * @param unknown_type $this->resp string
   	 * @return integer id of the incident we're referencing
   	 */
   	private function get_incident_id()
   	{
   		////////////////////////////////////////////////////////////////
		// GET fields check out, let's check the database
		// can we find the custom field that stores the IVR code
		$ivr_field = ORM::factory('form_field')->where('field_name',$this->form_fields['ivrcode'])->find();
		//no we couldn't find it
		if(! $ivr_field->loaded){
			$this->response['status'] = 'Error';
			$this->response['message'][] = "Could not find ivrcode db form field named " . $this->form_fields['ivrcode'];
			$this->send_response($this->response, $this->resp);
			return;
		}

		//can we find the reponse for the IVR custom field that has the IVR code in question?
		$incident_form_field = ORM::factory('form_response')
									->where('form_field_id',$ivr_field->id)
									->where('form_response',$this->form_answers['ivrcode'])
									->find();
		//no we couldn't find it.
		if(! $incident_form_field->loaded){
			$this->response['status'] = 'Error';
			$this->response['message'][] = "Could not find incident referenced by ivrcode " . $this->form_answers['ivrcode'];
			$this->errors_found = TRUE;
		}else{
			$incident_id = $incident_form_field->incident_id;
		}


		//if any thing above didn't work then error out.
		if($this->errors_found){
			$this->send_response();
			exit;
		}
   		return $incident_id;
   	}
   	
   	/**
   	 * Run this little guy to make sure everything is legit
   	 * Enter description here ...
   	 */
   	private function validate_input()
   	{
   		$this->errors_found = FALSE;				

		// validate the get request
		//is their an IVR code?
		if(! isset($_GET['ivrcode'])){
			$this->response['status'] = 'Error';
			$this->response['message'][] = 'Missing ivrcode';
			$this->errors_found = TRUE;
		}elseif(! is_numeric($_GET['ivrcode'])){
			$this->response['status'] = 'Error';
			$this->response['message'][] = 'Invalid value for ivrcode - should be numeric';
			$this->errors_found = TRUE;
		}else{
			$this->form_answers['ivrcode'] = $_GET['ivrcode'];
		}

		//is there a phone number
		if(! isset($_GET['phonenumber'])){
			$this->response['status'] = 'Error';
			$this->response['message'][] = 'Missing phonenumber';
			$this->errors_found = TRUE;
		}elseif(! is_numeric($_GET['phonenumber'])){
			$this->response['status'] = 'Error';
			$this->response['message'][] = 'Invalid value for phonenumber - should be numeric';
			$this->errors_found = TRUE;
		}else{
			$this->form_answers['phonenumber'] = $_GET['phonenumber'];
		}
		
		//is there a well working?
		if(! isset($_GET['wellwork'])){
			$this->response['status'] = 'Error';
			$this->response['message'][] = 'Missing wellwork';
			$this->errors_found = TRUE;
		}else{
			$_GET['wellwork'] = strtolower($_GET['wellwork']);
		}if($_GET['wellwork'] != 'yes' && $_GET['wellwork'] != 'no'){
			$this->response['status'] = 'Error';
			$this->response['message'][] = 'Invalid value for wellwork - should be Yes or No';
			$this->errors_found = TRUE;
		}else{
			$this->form_answers['wellwork'] = $_GET['wellwork'];
		}

		//is there a does the mechanic know?
		if(isset($_GET['mechanicknow']))
		{
			$_GET['mechanicknow'] = strtolower($_GET['mechanicknow']);
			if($_GET['mechanicknow'] != 'yes' && $_GET['mechanicknow'] != 'no'){
				$this->response['status'] = 'Error';
				$this->response['message'][] = 'Invalid value for mechanicknow - should be Yes or No';
				$this->errors_found = TRUE;
			}else{
				$this->form_answers['mechanicknow'] = $_GET['mechanicknow'];
			}
		}

			
		//is there a can the mechanic fix
		if(isset($_GET['mechanicfix']))
		{
			$_GET['mechanicfix'] = strtolower($_GET['mechanicfix']);
			if($_GET['mechanicfix'] != 'yes' && $_GET['mechanicfix'] != 'no'){
				$this->response['status'] = 'Error';
				$this->response['message'][] = 'Invalid value for mechanicfix - should be Yes or No';
				$this->errors_found = TRUE;
			}else{
				$this->form_answers['mechanicfix'] = $_GET['mechanicfix'];
			}
		}
		//is there a file name
		if(isset($_GET['filename'])){
			$get = new Validation($_GET);
			$get->add_rules('filename','standard_text');
			if(! $get->validate()){
				$this->response['status'] = 'Error';
				$this->response['message'][] = 'Invalid value for filename - should be standard text';
				$this->errors_found = TRUE;
			}else{
				$this->form_answers['filename'] = $_GET['filename'];
			}
		}
		
		//is there a response format 
		if(isset($_GET['resp'])){
			if($_GET['resp'] != 'json' && $_GET['resp'] != 'xml'){
				$this->response['status'] = 'Error';
				$this->response['message'][] = 'Invalid value for resp - should be json or xml';
				$this->errors_found = TRUE;
			}else{
				$this->resp = $_GET['resp'];
			}
		}else{
			$this->resp = 'json';
		}

		//if there are errors, let them know.
		if($this->errors_found){
			$this->send_response($this->response, $this->resp);
			return;
		}		
   	}


	private function send_response(){
		if($this->resp == 'json')
			echo json_encode($this->response);
		else
			echo "sorry, don't support XML yet";
	}
}
