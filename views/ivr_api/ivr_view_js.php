<script type="text/javascript">
	$(document).ready(function() {
		
		$('a.ivr_log').toggle(function(){
			$('#old_ivr_history').slideDown('fast');
			$(this).html('Hide the IVR Call History');
			return false;
		}, function(){
			$('#old_ivr_history').slideUp('fast');
			$(this).html('View the IVR Call History');
		});
		
	});
	
	
	function addComment(id)
	{
		var widthMultiplier = <?php echo $on_admin ? '0.9': '2'; ?>;
		var summaryWidth = 300 * widthMultiplier;
		var actionTakenWidth = 150 * widthMultiplier;
		
		var dateStr = new Date().toLocaleDateString();
		var formHtml = '<tr class="ivr_comment" id="new_comment_'+id+'" style="display:none;" >';
		formHtml += '<td><?php echo Kohana::lang('ivr_api.comments'); ?></td>';
		formHtml += '<td colspan="2">';
		formHtml += '<?php echo Kohana::lang('ivr_api.reporter_name_js'); ?>: <input id="reporter_name_'+id+'" type="text"/> &nbsp;&nbsp;&nbsp;';
		formHtml += '<?php echo Kohana::lang('ivr_api.reporter_position'); ?>: <input id="reporter_position_'+id+'" type="text"/><br/><br/>';		
		formHtml += '<?php echo Kohana::lang('ivr_api.summary'); ?><br/><textarea id="summary_'+id+'" style="width:'+summaryWidth+'px; height:100px;"></textarea><br/><br/>';
		formHtml += '<?php echo Kohana::lang('ivr_api.conditions'); ?>:<br/><table class="ivr_comment_inner_conditions">';
		formHtml += '<tr><td><?php echo Kohana::lang('ivr_api.tech_hand'); ?></td><td><input id="tech_hand_pump_'+id+'" type="checkbox"/></td>';
		formHtml += '<td><?php echo Kohana::lang('ivr_api.financial'); ?></td><td><input id="financial_'+id+'" type="checkbox"/></td></tr>';
		formHtml += '<tr><td><?php echo Kohana::lang('ivr_api.tech_other'); ?></td><td><input id="tech_other_'+id+'" type="checkbox"/></td>';
		formHtml += '<td><?php echo Kohana::lang('ivr_api.vandalism'); ?></td><td><input id="vandalism_'+id+'" type="checkbox"/></td></tr>';
		formHtml += '<tr><td><?php echo Kohana::lang('ivr_api.water_quality'); ?></td><td><input id="water_qual_'+id+'" type="checkbox"/></td>';
		formHtml += '<td><?php echo Kohana::lang('ivr_api.call_in_error'); ?></td><td><input id="call_error_'+id+'" type="checkbox"/></td></tr>';
		formHtml += '<tr><td><?php echo Kohana::lang('ivr_api.water_table'); ?></td><td><input id="water_table_'+id+'" type="checkbox"/></td>'
		formHtml += '<td><?php echo Kohana::lang('ivr_api.unknown'); ?></td><td><input id="unknown_'+id+'" type="checkbox"/></td></tr>';
		formHtml += '<tr><td><?php echo Kohana::lang('ivr_api.mechanic_not_available'); ?></td><td><input id="mechanic_awol_'+id+'" type="checkbox"/></td>';
		formHtml += '<td><?php echo Kohana::lang('ivr_api.other'); ?> <input disabled="disabled" id="other_text_'+id+'" type="text"/></td><td><input onclick="toggleOtherText(\''+id+'\'); " id="other_'+id+'" type="checkbox"/></td></tr>';
		formHtml +='</table>'		
		formHtml += '<br/><br/><table class="ivr_comment_inner">';
		formHtml += '<tr><td><?php echo Kohana::lang('ivr_api.action_taken'); ?>:<br/><br/><textarea id="action_taken_'+id+'" style="width:'+actionTakenWidth+'px;height:100px;"></textarea></td>';
		formHtml += '<td><?php echo Kohana::lang('ivr_api.referred_to'); ?>:<br/><input id="refered_to_'+id+'" type="text"/><br/><br/>';
		formHtml += '<?php echo Kohana::lang('ivr_api.date'); ?>:<br/><input id="refered_to_date_'+id+'" type="text" name="referred_date" /></td></tr>';
		formHtml += '</table>';
		formHtml += '<br/><br/><?php echo Kohana::lang('ivr_api.by'); ?>: <?php echo $user->name; ?><br/><br/>';
		formHtml += '<?php echo Kohana::lang('ivr_api.date'); ?>: '+dateStr;
		formHtml += '<br/><br/><a class="button" id="saveButton_'+id+'" href="#" onclick="saveComment(\''+id+'\');return false;"><?php echo Kohana::lang('ivr_api.save'); ?></a> &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;';
		formHtml += '<a class="button" href="#" onclick="cancelComment(\''+id+'\');return false;"><?php echo Kohana::lang('ivr_api.cancel'); ?></a></td></tr>';		
		
		$("#ivr_table_"+id).append(formHtml);		
		$("#new_comment_"+id).show('slow');
		$("#refered_to_date_"+id).datepicker({
			showOn: "both",
			buttonImage: "<?php echo url::base(); ?>media/img/icon-calendar.gif",
			buttonImageOnly: true
		});
		
		$("#addCommentButton_"+id).hide('slow');
		

	}//end add comment
	
	function toggleOtherText(id)
	{
		if($("#other_"+id).attr("checked"))
		{
			$("#other_text_"+id).removeAttr('disabled');
		}
		else
		{
			$("#other_text_"+id).attr('disabled','disabled');
		}
	}
	
	function saveComment(id)
	{
		//get all the data you need
		var reporter_name_val = $("#reporter_name_"+id).val();
		var reporter_position_val = $("#reporter_position_"+id).val();
		var summary_val = $("#summary_"+id).val();
		var tech_hand_pump_val = $("#tech_hand_pump_"+id).attr("checked");
		var tech_other_val = $("#tech_other_"+id).attr("checked");
		var water_qual_val = $("#water_qual_"+id).attr("checked");
		var water_table_val = $("#water_table_"+id).attr("checked");
		var mechanic_awol_val = $("#mechanic_awol_"+id).attr("checked");
		var financial_val = $("#financial_"+id).attr("checked");
		var vandalism_val = $("#vandalism_"+id).attr("checked");
		var call_error_val = $("#call_error_"+id).attr("checked");
		var unknown_val = $("#unknown_"+id).attr("checked");
		var other_val = $("#other_"+id).attr("checked");
		var other_text_val = $("#other_text_"+id).val();
		var action_taken_val = $("#action_taken_"+id).val();
		var refered_to_val = $("#refered_to_"+id).val();
		var refered_to_date_val = $("#refered_to_date_"+id).val();
		var entered_by_val = "<?php echo $user->name; ?>";
		
		//make the spinner
		$("#saveButton_"+id).html('<?php echo Kohana::lang('ivr_api.save'); ?>&nbsp;&nbsp;<img src="<?php echo url::base();?>media/img/indicator.gif" />');
		
		$.post('<?php echo url::base(); ?>admin/ivrapicomments/add_comment',
			{'reporter_name': reporter_name_val,
			'reporter_position': reporter_position_val,
			'summary': summary_val,
			'tech_hand_pump': tech_hand_pump_val,
			'tech_other': tech_other_val,
			'water_qual': water_qual_val,
			'water_table': water_table_val,
			'mechanic_awol': mechanic_awol_val,
			'financial': financial_val,
			'vandalism': vandalism_val,
			'call_error': call_error_val,
			'unknown': unknown_val,
			'other': other_val,
			'other_text': other_text_val,
			'action_taken': action_taken_val,
			'refered_to': refered_to_val,
			'refered_to_date': refered_to_date_val,
			'entered_by': entered_by_val,
			'ivr_data_id' : id
			},
			function(data){
				if(data['status'] == 'error')
				{
					alert(data['messages']);
					$("#saveButton_"+id).html('<?php echo Kohana::lang('ivr_api.save'); ?>');
				}
				else
				{
					var commentId = data['id'];
					var addedOnDate = data['added_by_date'];
					
					cancelComment(id);
				
					var commentHtml = data['html'];
					$("#ivr_table_"+id).append(commentHtml);		
					$("#comment_"+commentId).show('slow');
				}
			},
			"json");
	}//end save comment
	
	function cancelComment(id)
	{
		$("#new_comment_"+id).hide('slow');
		$("#addCommentButton_"+id).show('slow');
		$("#new_comment_"+id).remove();
	}//end cancel comment
</script>
