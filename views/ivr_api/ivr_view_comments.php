<tr class="ivr_comment" id="comment_<?php echo $comment->id; ?>" >
	<td>
		<?php echo Kohana::lang('ivr_api.comment'); ?><br/><br/>
		<?php echo Kohana::lang('ivr_api.by'); ?>: <?php echo $comment->entered_by; ?> <br/><br/>
		<?php echo Kohana::lang('ivr_api.date'); ?>: <?php echo $comment->added_on_date; ?>
	</td>
	<td colspan="2">
		<span class="notBold"><?php echo Kohana::lang('ivr_api.reporter_name'); ?>:</span> <?php echo $comment->reporter_name; ?><br/><br/>
		<span class="notBold"><?php echo Kohana::lang('ivr_api.reporter_position'); ?>:</span> <?php echo $comment->reporter_position; ?><br/><br/>
		<span class="notBold"><?php echo Kohana::lang('ivr_api.summary'); ?>:</span><br/><p><?php echo $comment->summary; ?></p><br/><br/>
		<span class="notBold"><?php echo Kohana::lang('ivr_api.conditions'); ?>:</span><br/>
		
		<table class="ivr_comment_inner_conditions">
			<tr>
				<td>
					<?php echo Kohana::lang('ivr_api.tech_hand'); ?></td><td><input disabled="disabled" <?php echo $comment->tech_hand_pump ? 'checked="checked"': '' ?> type="checkbox"/>
				</td>
				<td>
					<?php echo Kohana::lang('ivr_api.financial'); ?></td><td><input disabled="disabled" <?php echo $comment->financial ? 'checked="checked"': '' ?> type="checkbox"/>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo Kohana::lang('ivr_api.tech_other'); ?></td><td><input disabled="disabled" <?php echo $comment->tech_other ? 'checked="checked"': '' ?> type="checkbox"/>
				</td>
				<td>
					<?php echo Kohana::lang('ivr_api.vandalism'); ?></td><td><input disabled="disabled" <?php echo $comment->vandalism ? 'checked="checked"': '' ?> type="checkbox"/>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo Kohana::lang('ivr_api.water_quality'); ?></td><td><input disabled="disabled" <?php echo $comment->water_qual ? 'checked="checked"': '' ?> type="checkbox"/>
				</td>
				<td>
					<?php echo Kohana::lang('ivr_api.call_in_error'); ?></td><td><input disabled="disabled" <?php echo $comment->call_error ? 'checked="checked"': '' ?> type="checkbox"/>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo Kohana::lang('ivr_api.water_table'); ?></td><td><input disabled="disabled" <?php echo $comment->water_table ? 'checked="checked"': '' ?> type="checkbox"/>
				</td>
				<td>
					<?php echo Kohana::lang('ivr_api.unknown'); ?></td><td><input disabled="disabled" <?php echo $comment->unknown ? 'checked="checked"': '' ?> type="checkbox"/>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo Kohana::lang('ivr_api.mechanic_not_available'); ?></td><td><input disabled="disabled" <?php echo $comment->mechanic_awol ? 'checked="checked"': '' ?> type="checkbox"/>
				</td>
				<td>
					<?php echo Kohana::lang('ivr_api.other'); ?>: <?php echo $comment->other_text; ?>
				</td>
				<td>
					<input onclick="toggleOtherText(\''+id+'\'); " disabled="disabled" <?php echo $comment->other ? 'checked="checked"': '' ?> type="checkbox"/>
				</td>
			</tr>
		</table>
		<br/><br/>
		<table class="ivr_comment_inner">
			<tr>
				<td>
					<span class="notBold"><?php echo Kohana::lang('ivr_api.action_taken'); ?>:</span><br/>
					<p class="ivr_bold"><?php echo $comment->action_taken; ?></p>
				</td>
				<td>
					<span class="notBold"><?php echo Kohana::lang('ivr_api.referred_to'); ?>:</span><br/><span class="ivr_bold"><?php echo $comment->refered_to; ?></span>
					<br/><br/>
					<span class="notBold"><?php echo Kohana::lang('ivr_api.date'); ?>:</span><br/><span class="ivr_bold"><?php echo $comment->refered_to_date; ?></span>
				</td>
			</tr>
		</table>
	</td>
</tr>
