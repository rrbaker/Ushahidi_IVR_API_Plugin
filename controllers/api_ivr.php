<?php defined('SYSPATH') or die('No direct script access.');
/**
 * This controller handles alt login requests.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Login Controller  
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class Api_Ivr_Controller extends Controller {
	
    public $auto_render = TRUE;

	private $form_fields = array(
		'ivrcode' => 'IVR Code',
		'mechanicknow' => 'Is the mechanic aware?',
		'mechanicfix' => 'Can the mechanic fix the issue?',
		'filename' => 'Voice Message');

	private $wellstatus = array(
		'functioning' => 'Functioning Well', 
		'malfunctioning' => 'Malfunctioning Well');

	private $form_answers = array();

	public function __construct()
    {
        parent::__construct();
    }
	
    public function index()
    {
		$errors_found = FALSE;
		
		$response = array('status'=>'OK','message'=>array());

		if(! isset($_GET['ivrcode'])){
			$response['status'] = 'Error';
			$response['message'][] = 'Missing ivrcode';
			$errors_found = TRUE;
		}elseif(! is_numeric($_GET['ivrcode'])){
			$response['status'] = 'Error';
			$response['message'][] = 'Invalid value for ivrcode - should be numeric';
			$errors_found = TRUE;
		}else{
			$form_answers['ivrcode'] = $_GET['ivrcode'];
		}

		if(! isset($_GET['wellwork'])){
			$response['status'] = 'Error';
			$response['message'][] = 'Missing wellwork';
			$errors_found = TRUE;
		}elseif($_GET['wellwork'] != 'Yes' && $_GET['wellwork'] != 'No'){
			$response['status'] = 'Error';
			$response['message'][] = 'Invalid value for wellwork - should be Yes or No';
			$errors_found = TRUE;
		}else{
			$form_answers['wellwork'] = $_GET['wellwork'];
		}

		if(isset($_GET['mechanicknow']))
			if($_GET['mechanicknow'] != 'Yes' && $_GET['mechanicknow'] != 'No'){
				$response['status'] = 'Error';
				$response['message'][] = 'Invalid value for mechanicknow - should be Yes or No';
				$errors_found = TRUE;
			}else{
				$form_answers['mechanicknow'] = $_GET['mechanicknow'];
			}

		if(isset($_GET['mechanicfix']))
			if($_GET['mechanicfix'] != 'Yes' && $_GET['mechanicfix'] != 'No'){
				$response['status'] = 'Error';
				$response['message'][] = 'Invalid value for mechanicfix - should be Yes or No';
				$errors_found = TRUE;
			}else{
				$form_answers['mechanicfix'] = $_GET['mechanicfix'];
			}

		if(isset($_GET['filename'])){
			$get = new Validation($_GET);
			$get->add_rules('filename','standard_text');
			if(! $get->validate()){
				$response['status'] = 'Error';
				$response['message'][] = 'Invalid value for filename - should be standard text';
				$errors_found = TRUE;
			}else{
				$form_answers['filename'] = $_GET['filename'];
			}
		}
		
		if(isset($_GET['resp'])){
			if($_GET['resp'] != 'json' && $_GET['resp'] != 'xml'){
				$response['status'] = 'Error';
				$response['message'][] = 'Invalid value for resp - should be json or xml';
				$errors_found = TRUE;
			}else{
				$resp = $_GET['resp'];
			}
		}else{
			$resp = 'json';
		}

		if($errors_found){
			$this->send_response($response, $resp);
			return;
		}

		// GET fields check out, let' check the database

		$ivr_field = ORM::factory('form_field')->where('field_name',$this->form_fields['ivrcode'])->find();

		if(! $ivr_field->loaded){
			$response['status'] = 'Error';
			$response['message'][] = "Could not find ivrcode db form field named " . $this->form_fields['ivrcode'];
			$this->send_response($response, $resp);
			return;
		}

		$incident_form_field = ORM::factory('form_response')
									->where('form_field_id',$ivr_field->id)
									->where('form_response',$form_answers['ivrcode'])
									->find();

		if(! $incident_form_field->loaded){
			$response['status'] = 'Error';
			$response['message'][] = "Could not find incident referenced by ivrcode " . $form_answers['ivrcode'];
			$errors_found = TRUE;
		}else{
			$incident_id = $incident_form_field->incident_id;
		}

		// incident with that ivr code exists, let's grab the form field id's

		$functioning_category = ORM::factory('category')->where('category_title',$this->wellstatus['functioning'])->find();
		if(! $functioning_category->loaded){
			$response['status'] = 'Error';
			$response['message'][] = "Could not find well functioning category: " . $this->wellstatus['functioning'];
			$errors_found = TRUE;
		}

		$malfunctioning_category = ORM::factory('category')->where('category_title',$this->wellstatus['malfunctioning'])->find();
		if(! $malfunctioning_category->loaded){
			$response['status'] = 'Error';
			$response['message'][] = "Could not find well malfunctioning category: " . $this->wellstatus['malfunctioning'];
			$errors_found = TRUE;
		}

		$mechanicknow_field = ORM::factory('form_field')->where('field_name',$this->form_fields['mechanicknow'])->find();
		if(! $mechanicknow_field->loaded){
			$response['status'] = 'Error';
			$response['message'][] = "Could not find db form field named " . $this->form_fields['mechanicknow'];
			$errors_found = TRUE;
		}

		$mechanicfix_field = ORM::factory('form_field')->where('field_name',$this->form_fields['mechanicfix'])->find();
		if(! $mechanicfix_field->loaded){
			$response['status'] = 'Error';
			$response['message'][] = "Could not find db form field named " . $this->form_fields['mechanicfix'];
			$errors_found = TRUE;
		}

		$filename_field = ORM::factory('form_field')->where('field_name',$this->form_fields['filename'])->find();
		if(! $filename_field->loaded){
			$response['status'] = 'Error';
			$response['message'][] = "Could not find db form field named " . $this->form_fields['filename'];
			$errors_found = TRUE;
		}

		if($errors_found){
			$this->send_response($response,$resp);
			return;
		}

		//update the well status
		if($form_answers['wellwork'] == 'Yes'){
			$malfunction_delete = ORM::factory('incident_category')->
										where('incident_id',$incident_id)->
										where('category_id',$malfunctioning_category->id)->find();
			$malfunction_delete->delete();

			$functioning_insert = ORM::factory('incident_category')->
										where('incident_id',$incident_id)->
										where('category_id',$functioning_category->id)->
										find();

			if(! $functioning_insert->loaded){
				$functioning_insert = ORM::factory('incident_category');
				$functioning_insert->incident_id = $incident_id;
				$functioning_insert->category_id = $functioning_category->id;
				$functioning_insert->save();
			}
			
		}

		if($form_answers['wellwork'] == 'No'){
			$functioning_delete = ORM::factory('incident_category')->
										where('incident_id',$incident_id)->
										where('category_id',$functioning_category->id)->find();
			$functioning_delete->delete();

			$malfunctioning_insert = ORM::factory('incident_category')->
										where('incident_id',$incident_id)->
										where('category_id',$malfunctioning_category->id)->
										find();

			if(! $malfunctioning_insert->loaded){
				$malfunctioning_insert = ORM::factory('incident_category');
				$malfunctioning_insert->incident_id = $incident_id;
				$malfunctioning_insert->category_id = $malfunctioning_category->id;
				$malfunctioning_insert->save();
			}
		}
			
		$db = new Database();

		if(isset($form_answers['mechanicknow'])){
			$where = array('incident_id' => $incident_id, 'form_field_id'=> $mechanicknow_field->id);
			$db->delete('form_response',$where);
			$insert = array('form_response' => $form_answers['mechanicknow'], 
								'incident_id' => $incident_id, 
								'form_field_id' => $mechanicknow_field->id);
			$db->merge('form_response',$insert,$where);
		}

		if(isset($form_answers['mechanicfix'])){
			$where = array('incident_id' => $incident_id, 'form_field_id'=> $mechanicfix_field->id);
			$db->delete('form_response',$where);
			$insert = array('form_response' => $form_answers['mechanicfix'], 
								'incident_id' => $incident_id, 
								'form_field_id' => $mechanicfix_field->id);
			$db->merge('form_response',$insert,$where);
		}

		if(isset($form_answers['filename'])){
			$where = array('incident_id' => $incident_id, 'form_field_id'=> $filename_field->id);
			$db->delete('form_response',$where);
			$file_path = '<a href=\''  . 'media/' . $form_answers['filename'] .'\'>' . $form_answers['filename'] . '</a>';
			$insert = array('form_response' => $file_path, 
								'incident_id' => $incident_id, 
								'form_field_id' => $filename_field->id);
			$db->merge('form_response',$insert,$where);
		}
		//if set update the mechanic know, mechanicfix, and filename

		$this->send_response($response,$resp);
   	}	


	private function send_response($response, $resp){
		if($resp == 'json')
			echo json_encode($response);
	}
}
