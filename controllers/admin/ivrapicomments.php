<?php defined('SYSPATH') or die('No direct script access.');
/**
 * IVR API comments Controller
 * @written by John Etherton <john@ethertontech.com>
 */

class ivrapicomments_Controller extends Admin_Controller
{
	
	/**
	 * Automatically render the views
	 * @var bool
	 */
	public $auto_render = TRUE;

	/**
	 * Name of the view template for this controller
	 * @var string
	 */
	public $template = 'json';
	
	
    function __construct()
    {
        parent::__construct();
    }


    /**
    * add a comment
    */
    function add_comment()
    {

		$output = array();
		
        // If user doesn't have access, redirect to dashboard
        if ( ! admin::permissions($this->user, "reports_edit"))
        {
				$output['status'] = 'error';
				$output['messages'] = array('error'=>'you don\'t have permissions to do this');
				echo json_encode($output);
				exit;
        }
        
        //export that data
        if ($_SERVER['REQUEST_METHOD']=='POST')        
        {
			
			// Instantiate Validation, use $post, so we don't overwrite $_POST fields with our own things
			$post = Validation::factory($_POST);

			 //	 Add some filters
			$post->pre_filter('trim', TRUE);
			
			$post->add_rules('reporter_name','length[0,255]');
			$post->add_rules('reporter_position','length[0,255]');
			$post->add_rules('other_text','length[0,255]');
			$post->add_rules('refered_to','length[0,255]');
			$post->add_rules('refered_to_date','date_mmddyyyy');
			$post->add_rules('entered_by','length[0,255]');
			$post->add_rules('added_by_date','date_mmddyyyy');
			
			
			if ($post->validate())
			{
				
				$comment = ORM::factory('ivrapi_data_comments');
				//get the data
				$comment->reporter_name = $post['reporter_name'];
				$comment->reporter_position = $post['reporter_position'];
				$comment->summary = $post['summary'];
				$comment->tech_hand_pump = $post['tech_hand_pump'] == 'true' ? 1 : 0;
				$comment->tech_other = $post['tech_other'] == 'true' ? 1 : 0;
				$comment->water_qual = $post['water_qual'] == 'true' ? 1 : 0;
				$comment->water_table = $post['water_table'] == 'true' ? 1 : 0;
				$comment->mechanic_awol = $post['mechanic_awol'] == 'true' ? 1 : 0;
				$comment->financial = $post['financial'] == 'true' ? 1 : 0;
				$comment->vandalism = $post['vandalism'] == 'true' ? 1 : 0;
				$comment->call_error = $post['call_error'] == 'true' ? 1 : 0;
				$comment->unknown = $post['unknown'] == 'true' ? 1 : 0;
				$comment->other = $post['other'] == 'true' ? 1 : 0;
				$comment->other_text = $post['other_text'];
				$comment->action_taken = $post['action_taken'];
				$comment->refered_to = $post['refered_to'];
				$comment->refered_to_date = date('Y-m-d G:i:s', strtotime($post['refered_to_date']));
				$comment->entered_by = $post['entered_by'];
				$comment->added_on_date = date('Y-m-d G:i:s');
				$comment->ivr_data_id = $post['ivr_data_id'];
				$comment->save();
				
				$output['status'] = 'success';
				$view = View::factory('ivr_api/ivr_view_comments');
				$view->comment = $comment;				
				//capture this into a variable;
				ob_start();
				$view->render(TRUE);
				
				$output['html'] = ob_get_contents();
				ob_end_clean();
				
				echo json_encode($output);
				exit;
			}
			else
			{
				//send out the errors
				$output['status'] = 'error';
				$errors = $post->errors();
				$error_val = "";
				$error_key = "";
				foreach($errors as $key=>$val)
				{
					$error_val = $val;
					$error_key = $key;
					break;
				}
				//translate to more human readable
				if($error_val == 'length')
				{
					$error_val = Kohana::lang('ivr_api.comment_field_length');
				}
				
				$output['messages'] = Kohana::lang('ivr_api.comment_field') . ' "'. $error_key. '" '. $error_val;
				echo json_encode($output);
				exit;
			}
		}
	}//end function add comment
}
	
	
   

