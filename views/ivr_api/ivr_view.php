<!-- <div class="custom_div" style="position: absolute; left: 452px; top: 10px; "> -->
<div class="report-custom report-block">
	<?php 
		$count = 0;
		foreach($ivr_datas as $ivr_data){
		$count++;
		if($count == 2)
		{
			echo '<div id="show_old_ivr_history"><a href="#" onclick="toggle_ivr_history(); return false;">'.Kohana::lang('ivr_api.show_old').'</a></div>';
			echo '<div id="old_ivr_history" style="display:none">';
		}
		
		$time_str = date('Y-m-d H:i', strtotime($ivr_data->time_received));
	?>
	
	<h2><?php 
			if($count == 1){echo Kohana::lang("ivr_api.current_status");}
			else{echo Kohana::lang("ivr_api.status_on"). ' ' . $time_str;}
		?>
	</h2>
	<div class="report-custom-forms-text">
	<table>
		<tbody>
			<tr>
				<td>
					<strong><?php echo Kohana::lang("ivr_api.ivr_code");?>: </strong>
				</td>
				<td class="answer ivrcode"><?php echo $ivr_data->ivr_code?></td>
			</tr>
			<tr>
				<td>
					<strong><?php echo Kohana::lang("ivr_api.phone_number");?>: </strong>
				</td>
				<td class="answer"><?php echo $ivr_data->phone_number?></td>
			</tr>
			<tr>
				<td>
					<strong><?php echo Kohana::lang("ivr_api.well_working");?>: </strong>
				</td>
				<td class="answer"><?php echo $ivr_data->well_working == 1 ? Kohana::lang("ivr_api.yes") : Kohana::lang('ivr_api.no');?></td>
			</tr>
			<tr>
				<td>
					<strong><?php echo Kohana::lang("ivr_api.mechanic_aware");?>: </strong>
				</td>
				<td class="answer"><?php echo $ivr_data->mechanic_aware == 1 ? Kohana::lang("ivr_api.yes") : Kohana::lang('ivr_api.no');?></td>
			</tr>
			<tr>
				<td>
					<strong><?php echo Kohana::lang("ivr_api.can_fix");?>: </strong>
				</td>
				<td class="answer"><?php echo $ivr_data->can_fix == 1 ? Kohana::lang("ivr_api.yes") : Kohana::lang('ivr_api.no');?></td>
			</tr>
			<tr>
				<td>
					<strong><?php echo Kohana::lang("ivr_api.voice_message");?>: </strong>
				</td>
				<td class="answer">
					<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
					    width="40"
					    height="40"
					    id="audio1"
					    align="left">
					    <embed src="<?php echo url::base().'plugins/Ushahidi_IVR_API_Plugin/swf/';?>wavplayer.swf?gui=wide&h=20&w=300&sound=<?php echo url::base(). Kohana::config('upload.relative_directory'). '/audio/'. $ivr_data->file_name;?>&"
					        bgcolor="#ffffff"
					        width="40"
					        height="40"
					        allowScriptAccess="always"
					        type="application/x-shockwave-flash"
					        pluginspage="http://www.macromedia.com/go/getflashplayer"
					    />
					</object>
					<a href="<?php echo url::base(). Kohana::config('upload.relative_directory'). '/audio/'. $ivr_data->file_name;?>" target="_blank"><?php echo $ivr_data->file_name;?></a>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo Kohana::lang("ivr_api.date");?>: </strong>
				</td>
				<td class="answer"><?php echo $time_str; ?></td>
			</tr>
		</tbody>
	</table>
	</div>
	<?php }
		if($count > 1)
		{
			echo '</div>';
		}
	?>
</div>