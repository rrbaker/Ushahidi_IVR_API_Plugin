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
		var dateStr = new Date().toLocaleDateString();
		$("#ivr_table_"+id).append('<tr id="comment_' + id + '_s1" style="display:none;"><td><?php echo Kohana::lang('ivr_api.comments'); ?></td><td colspan="2"><textarea style="width:600px; height:100px;"></textarea><br/><br/>More action required: <input type="checkbox"/> &nbsp;&nbsp; Well Working: <input type="checkbox"/><br/><br/>By: <?php echo $user->name; ?><br/><br/>Date: '+dateStr+'<br/><br/><a class="button" href="#" onclick="saveComment('+id+');return false;">Save</a> &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;<a class="button" href="#" onclick="cancelComment(\''+id+'_s1\');return false;">Cancel</a></td></tr>');
		$("#comment_"+id+"_s1").show('slow');
	}
	
	function saveComment(id)
	{
		alert('This functionality is still under development and doesn\'t yet work');
	}
	
	function cancelComment(id)
	{
		$("#comment_"+id).hide('slow');
	}
</script>
