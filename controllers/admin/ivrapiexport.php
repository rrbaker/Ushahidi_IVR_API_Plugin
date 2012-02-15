<?php defined('SYSPATH') or die('No direct script access.');
/**
 * IVR API Export Controller
 * @written by John Etherton <john@ethertontech.com>
 */

class ivrapiexport_Controller extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->template->this_page = 'reports';
    }


    /**
    * Export the reports to a CSV file
    * @param int $page
    */
    function index()
    {

        // If user doesn't have access, redirect to dashboard
        if ( ! admin::permissions($this->user, "reports_download"))
        {
            url::redirect(url::site().'admin/dashboard');
        }
        
        //export that data
        if ($_SERVER['REQUEST_METHOD']=='POST')        
        {
			
			//get that table prefix
			$table_prefix = Kohana::config('database.default.table_prefix');
						
			
			//get the IVR data from the database				
			$sql = "SELECT incident_id, ivr_code, incident.incident_title as well_title, location.location_name as well_location, phone_number, well_working, time_received ";
			$sql .= "FROM ".$table_prefix."ivrapi_data ";
			$sql .= "LEFT JOIN ".$table_prefix."incident AS incident ON incident.id = ivrapi_data.incident_id ";
			$sql .= "LEFT JOIN ".$table_prefix."location AS location ON incident.location_id = location.id ";
			$db = new Database();
			$ivr_data = $db->query($sql);			
			
			$ivr_csv = $this->_csv_text(Kohana::lang('ivr_api.ivr_code'));
			$ivr_csv .= ',' . $this->_csv_text(Kohana::lang('ivr_api.well_title'));
			$ivr_csv .= ',' . $this->_csv_text(Kohana::lang('ivr_api.well_location'));
			$ivr_csv .= ',' . $this->_csv_text(Kohana::lang('ivr_api.phone_number'));
			$ivr_csv .= ',' . $this->_csv_text(Kohana::lang('ivr_api.well_working'));
			$ivr_csv .= ',' . $this->_csv_text(Kohana::lang('ivr_api.time_received'));
			$ivr_csv .= "\n";
			
			//now loop through the data
			foreach($ivr_data as $data)
			{
				$ivr_csv .= '"="' . $this->_csv_text($data->ivr_code) . '""';
				$ivr_csv .= ',' . $this->_csv_text($data->well_title);
				$ivr_csv .= ',' . $this->_csv_text($data->well_location);
				$ivr_csv .= ',"="' . $this->_csv_text($data->phone_number) . '""';
				$ivr_csv .= ',' . $this->_csv_text($this->_code_numbers($data->well_working));
				$ivr_csv .= ',' . $this->_csv_text($data->time_received);
				$ivr_csv .= "\n";
			}
			
			// Output to browser
			header("Content-type: text/x-csv");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Disposition: attachment; filename=IVR_export_" . date("Y-m-d_H.i.s") . ".csv");
			header("Content-Length: " . strlen($ivr_csv));
			echo $ivr_csv;
			exit;
				
				
        } // _POST

        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->template->content = new View('ivr_api/ivrapiexport');
            $this->template->content->title = Kohana::lang('ivr_api.export_ivr');
            $this->template->content->form_error = false;
        }
    }//end index()
    
    
    /**
     * Used to sanitize and wrap text
     */
    private function _csv_text($text)
	{
		$text = '"'.stripslashes(htmlspecialchars($text)).'"';
		return $text;
	}
	
	
	/**
	* Used to convert the 0,1,2 system into meaningful words
	*/
	private function _code_numbers($str)
	{
		switch($str) {
			case "0":
				return Kohana::lang('ivr_api.no');
				break;
			case "1":
				return Kohana::lang('ivr_api.yes');
				break;
			case "2":
				return Kohana::lang('ivr_api.na');
				break;
		}
	}
	
	
   
}
