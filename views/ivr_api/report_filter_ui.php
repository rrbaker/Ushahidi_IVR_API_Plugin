<h3 id="ivr_api_filter_header">
	<a href="#" class="small-link-button f-clear reset" onclick="ivrCommentsRemoveParameterKey('dm', 'fl-ivrapi_time');">
		<?php echo Kohana::lang('ui_main.clear'); ?>
	</a>
	<a class="f-title" href="#"><?php echo Kohana::lang('ivr_api.ivr_comments'); ?></a>
</h3>
<div class="f-simpleGroups-box" id="ivr_api_filter_body">
	<strong><?php echo Kohana::lang('ivr_api.time');?>:</strong>
	<ul class="filter-list fl-ivrapi_time">
		<li>
			<?php print form::radio('ivr_api_time', '0', false,"onchange=\"ivrApiTimeFilterToggle('0');\" id=\"ivrApiTime0\""); echo  " ".Kohana::lang('ivr_api.current'); ?>
		</li>
		<li>
			<?php print form::radio('ivr_api_time', '1', false, "onchange=\"ivrApiTimeFilterToggle('1');\"  id=\"ivrApiTime1\"");echo " ".Kohana::lang('ivr_api.past'); ?>			
		</li>
		<li>
			<?php print form::radio('ivr_api_time', '2', false, "onchange=\"ivrApiTimeFilterToggle('2');\"  id=\"ivrApiTime2\"");echo " ".Kohana::lang('ivr_api.all'); ?>			
		</li>
	</ul>
	<br/>
	<strong><?php echo Kohana::lang('ivr_api.conditions');?>:</strong>
	<ul class="filter-list fl-ivrapi_time">
		<li>
			<?php print form::checkbox('ivrApiCondtion0', '0', false, "onchange=\"ivrApiConditionsFilterToggle('0');\"  id=\"ivrApiCondtion0\"");echo  " ".Kohana::lang('ivr_api.tech_hand'); ?>
		</li>
		<li>
			<?php print form::checkbox('ivrApiCondtion1', '1', false, "onchange=\"ivrApiConditionsFilterToggle('1');\"  id=\"ivrApiCondtion1\"");echo " ".Kohana::lang('ivr_api.tech_other'); ?>			
		</li>
		<li>
			<?php print form::checkbox('ivrApiCondtion2', '2', false, "onchange=\"ivrApiConditionsFilterToggle('2');\"  id=\"ivrApiCondtion2\"");echo " ".Kohana::lang('ivr_api.financial'); ?>			
		</li>
		<li>
			<?php print form::checkbox('ivrApiCondtion3', '3', false, "onchange=\"ivrApiConditionsFilterToggle('3');\"  id=\"ivrApiCondtion3\"");echo " ".Kohana::lang('ivr_api.vandalism'); ?>			
		</li>
		<li>
			<?php print form::checkbox('ivrApiCondtion4', '4', false, "onchange=\"ivrApiConditionsFilterToggle('4');\"  id=\"ivrApiCondtion4\"");echo " ".Kohana::lang('ivr_api.water_quality'); ?>			
		</li>
		<li>
			<?php print form::checkbox('ivrApiCondtion5', '5', false, "onchange=\"ivrApiConditionsFilterToggle('5');\"  id=\"ivrApiCondtion5\"");echo " ".Kohana::lang('ivr_api.call_in_error'); ?>			
		</li>
		<li>
			<?php print form::checkbox('ivrApiCondtion6', '6', false, "onchange=\"ivrApiConditionsFilterToggle('6');\"  id=\"ivrApiCondtion6\"");echo " ".Kohana::lang('ivr_api.water_table'); ?>			
		</li>
		<li>
			<?php print form::checkbox('ivrApiCondtion7', '7', false, "onchange=\"ivrApiConditionsFilterToggle('7');\"  id=\"ivrApiCondtion7\"");echo " ".Kohana::lang('ivr_api.mechanic_not_available'); ?>			
		</li>
		<li>
			<?php print form::checkbox('ivrApiCondtion10', '10', false, "onchange=\"ivrApiConditionsFilterToggle('10');\"  id=\"ivrApiCondtion10\"");echo " ".Kohana::lang('ivr_api.mechanic_no_fix'); ?>			
		</li>
		<li>
			<?php print form::checkbox('ivrApiCondtion8', '8', false, "onchange=\"ivrApiConditionsFilterToggle('8');\"  id=\"ivrApiCondtion8\"");echo " ".Kohana::lang('ivr_api.unknown'); ?>			
		</li>
		<li>
			<?php print form::checkbox('ivrApiCondtion9', '9', false, "onchange=\"ivrApiConditionsFilterToggle('9');\"  id=\"ivrApiCondtion9\"");echo " ".Kohana::lang('ivr_api.other'); ?>			
		</li>
	</ul>
	<br/>
	<strong><?php echo Kohana::lang('ivr_api.condition_operator');?>:</strong>
	<ul class="filter-list fl-ivrapi_op">
		<li>
			<?php print form::radio('ivr_api_op', '0', false, "onchange=\"ivrApiOpFilterToggle('0');\"  id=\"ivrApiOp0\"");echo " ".Kohana::lang('ivr_api.and'); ?>			
		</li>
		<li>
			<?php print form::radio('ivr_api_op', '1', false, "onchange=\"ivrApiOpFilterToggle('1');\"  id=\"ivrApiOp1\"");echo " ".Kohana::lang('ivr_api.or'); ?>			
		</li>
	</ul>
	
	
</div>
