<script type="text/javascript">
/**
* Remove the IVR Comments filters from the reports query
*/
function ivrCommentsRemoveParameterKey()
{
	delete urlParameters['ivr_t'];
	delete urlParameters['ivr_c'];
	delete urlParameters['ivr_o'];
	$("input[id^='ivrApiTime']").removeAttr("checked");
	$("input[id^='ivrApiCondtion']").removeAttr("checked");
	$("input[id^='ivrApiOp']").removeAttr("checked");
}

/**
* Select the operators for combining more than one
* condition
*/
function ivrApiOpFilterToggle(id)
{
	urlParameters['ivr_o'] = id;
	
	//check if the time has been set. If not then set it
	if(!urlParameters['ivr_t'] || typeof urlParameters['ivr_t'] == 'undefined')
	{
		$("#ivrApiTime2").trigger('click');
	}
}



/**
* Select the time component of the IVR category filtering
*/
function ivrApiTimeFilterToggle(id)
{
	urlParameters['ivr_t'] = id;
	
	//check if the operator has been set. If not then set it
	if(!urlParameters['ivr_o'] || typeof urlParameters['ivr_o'] == 'undefined')
	{
		$("#ivrApiOp1").trigger('click');
	}
	
}

/**
* Select the time component of the IVR category filtering
*/
function ivrApiConditionsFilterToggle(id)
{
	if(!urlParameters['ivr_c'] || typeof urlParameters['ivr_c'] == 'undefined')
	{
		urlParameters['ivr_c'] = new Array();
	}
	if($("#ivrApiCondtion"+id).attr("checked"))
	{
		urlParameters['ivr_c'].push(id);
	}
	else
	{
		delete urlParameters['ivr_c'][id];
		for( key in urlParameters['ivr_c'])
		{
			if( urlParameters['ivr_c'][key] == id)
			{
				delete urlParameters['ivr_c'][key];
				break;
			}
		}
	}
	
	//check if the time has been set. If not then set it
	if(!urlParameters['ivr_t'] || typeof urlParameters['ivr_t'] == 'undefined')
	{
		$("#ivrApiTime2").trigger('click');
	}
	
	//check if the operator has been set. If not then set it
	if(!urlParameters['ivr_o'] || typeof urlParameters['ivr_o'] == 'undefined')
	{
		$("#ivrApiOp1").trigger('click');
	}
}


</script>
