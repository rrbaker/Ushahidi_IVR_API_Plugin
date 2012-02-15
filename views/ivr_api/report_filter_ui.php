<h3 id="ivr_api_filter_header">
	<a href="#" class="small-link-button f-clear reset" onclick="ivrApiRemoveParameterKey('dm', 'fl-ivrapi_time');">
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
	
	
</div>
