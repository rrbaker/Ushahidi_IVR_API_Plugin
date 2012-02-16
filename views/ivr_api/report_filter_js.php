<script type="text/javascript">
/**
* Remove the IVR Comments filters from the reports query
*/
function ivrCommentsRemoveParameterKey()
{
	delete urlParameters['ivr_t'];
	delete urlParameters['ivr_c'];
	$("input[id^='ivrApiTime']").removeAttr("checked");
	$("input[id^='ivrApiCondtion']").removeAttr("checked");
}

/**
* Select the time component of the IVR category filtering
*/
function ivrApiTimeFilterToggle(id)
{
	urlParameters['ivr_t'] = id;

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
}


</script>
