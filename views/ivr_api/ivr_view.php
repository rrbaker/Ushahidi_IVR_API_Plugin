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
		
		//figure out what optional fields won't be there and calculate rowspan
		$row_span = 4;
		if($ivr_data->mechanic_aware == 2)
		{
			$row_span--;
		}
		if($ivr_data->can_fix == 2)
		{
			$row_span--;
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
		<tbody id="ivr_table_<?php echo $ivr_data->id;?>">
			<tr>
				<td class="ivr_key">
					<?php echo Kohana::lang("ivr_api.date");?>:
				</td>
				<td><?php echo $time_str; ?></td>
				<td class="question">
					
					<?php if($ivr_data->file_name) {echo Kohana::lang("ivr_api.voice_message").":";}
					else{ echo Kohana::lang("ivr_api.no_message");}
					 ?>
				</td>
			</tr>
			<tr>
				<td class="ivr_key">
					<?php echo Kohana::lang("ivr_api.phone_number");?>:
				</td>
				<td><?php echo $ivr_data->phone_number?></td>
				<td rowspan="<?php echo $row_span; ?>" <?php if(!$ivr_data->file_name) { echo 'style="width:500px;"'; }?>>
				<?php if($ivr_data->file_name) { ?>
					<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
					    width="400"
					    height="40"
					    id="audio1">
					    <param name="movie" value="<?php echo url::base().'plugins/ivr_api/swf/';?>wavplayer.swf?gui=full&bg_color=0xFFFFFF&h=20&w=400&sound=<?php echo url::base(). Kohana::config('upload.relative_directory'). '/audio/'. $ivr_data->file_name;?>&" />
					    <param name="allowScriptAccess" value="always" />
					    <param name="quality" value="high" />
					    <param name="scale" value="noscale" />
					    <embed src="<?php echo url::base().'plugins/ivr_api/swf/';?>wavplayer.swf?gui=full&bg_color=0xFFFFFF&h=20&w=400&sound=<?php echo url::base(). Kohana::config('upload.relative_directory'). '/audio/'. $ivr_data->file_name;?>&"
					        bgcolor="#ffffff"
					        width="400"
					        height="20"
					        name="audio1"
					        scale="noscale"
					        allowScriptAccess="always"
					        type="application/x-shockwave-flash"
					        pluginspage="http://www.macromedia.com/go/getflashplayer"
					    />
					</object>
					<p><?php echo Kohana::lang("ivr_api.player_not_working"); ?>:<br/><a href="<?php echo url::base(). Kohana::config('upload.relative_directory'). '/audio/'. $ivr_data->file_name;?>" target="_blank"><?php echo $ivr_data->file_name;?></a></p>
					<?php } ?>
				</td>
			</tr>
			<?php if($ivr_data->can_fix != 2) {?>
			<tr>
				<td class="ivr_key">
					<?php echo Kohana::lang("ivr_api.can_fix");?>:
				</td>
				<td><?php echo $ivr_data->can_fix == 1 ? Kohana::lang("ivr_api.yes") : Kohana::lang('ivr_api.no');?></td>
			</tr>
			<?php }?>
			<tr>
				<td>
					
				</td>
				<td class="add_ivr_comment" >
					<a href="#" onclick="addComment(<?php echo $ivr_data->id; ?>); return false;"><?php echo Kohana::lang("ivr_api.add_comment");?> </a>
				</td>
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
