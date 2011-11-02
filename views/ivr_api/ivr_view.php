<!-- <div class="custom_div" style="position: absolute; left: 452px; top: 10px; "> -->
<div class="report-custom report-block">
	<?php 
		$count = 0;
		foreach($ivr_datas as $ivr_data){
		$count++;
		if($count == 2)
		{
			echo '<div id="show_old_ivr_history"><a href="#" class="ivr_log button">'.Kohana::lang('ivr_api.show_old').'</a></div>';
			echo '<div id="old_ivr_history">';
		}
		
		$time_str = date('d M Y, H:i', strtotime($ivr_data->time_received));
	?>
	
	<h2><?php 
			if($count == 1){echo Kohana::lang("ivr_api.current_status");}
			else{echo Kohana::lang("ivr_api.status_on"). ' ' . $time_str;}
		?>
	</h2>
	<div class="report-custom-forms-text">
	<p><em><?php echo Kohana::lang("ivr_api.ivr_code");?>: <?php echo $ivr_data->ivr_code?></em></p>
	<table>
		<tbody>
			<tr>
				<td>
					<?php echo Kohana::lang("ivr_api.date");?>:
				</td>
				<td><?php echo $time_str; ?></td>
				<td class="question">
					<?php echo Kohana::lang("ivr_api.voice_message");?>:
				</td>
			</tr>
			<tr>
				<td>
					<?php echo Kohana::lang("ivr_api.phone_number");?>:
				</td>
				<td><?php echo $ivr_data->phone_number?></td>
				<td rowspan="4">
					<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
					    width="40"
					    height="40"
					    id="audio1">
					    <embed src="<?php echo url::base().'plugins/Ushahidi_IVR_API_Plugin/swf/';?>wavplayer.swf?gui=full&bg_color=0x888888&h=20&w=400&sound=<?php echo url::base(). Kohana::config('upload.relative_directory'). '/audio/'. $ivr_data->file_name;?>&"
					        bgcolor="#ffffff"
					        width="400"
					        height="40"
					        allowScriptAccess="always"
					        type="application/x-shockwave-flash"
					        pluginspage="http://www.macromedia.com/go/getflashplayer"
					    />
					</object>
					<p>If the player is not working or does not appear, please click here to play the file:<br/><a href="<?php echo url::base(). Kohana::config('upload.relative_directory'). '/audio/'. $ivr_data->file_name;?>" target="_blank"><?php echo $ivr_data->file_name;?></a></p>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo Kohana::lang("ivr_api.well_working");?>:
				</td>
				<td><?php echo $ivr_data->well_working == 1 ? Kohana::lang("ivr_api.yes") : Kohana::lang('ivr_api.no');?></td>
			</tr>
			<tr>
				<td>
					<?php echo Kohana::lang("ivr_api.mechanic_aware");?>:
				</td>
				<td><?php echo $ivr_data->mechanic_aware == 1 ? Kohana::lang("ivr_api.yes") : Kohana::lang('ivr_api.no');?></td>
			</tr>
			<tr>
				<td>
					<?php echo Kohana::lang("ivr_api.can_fix");?>:
				</td>
				<td><?php echo $ivr_data->can_fix == 1 ? Kohana::lang("ivr_api.yes") : Kohana::lang('ivr_api.no');?></td>
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