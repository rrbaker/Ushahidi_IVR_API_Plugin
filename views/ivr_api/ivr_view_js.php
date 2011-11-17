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
</script>
