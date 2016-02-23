/**
 * jQuery events management controller
 * 
 * @author apidox vemovi.com
 */

$(document).ready(function()
{
	$('.content').anchorific({
		navigation : '.anchorific', // position of navigation
		speed : 200, // speed of sliding back to top
		anchorClass : 'anchor', // class of anchor links
		anchorText : '', // prepended or appended to anchor headings
		top : '.top', // back to top button or link class
		spy : true, // scroll spy
		position : 'append', // position of anchor text
		spyOffset : 0
	// specify heading offset for spy scrolling
	});
	
	//$("input[data-name='server']").on("keyup", onUpdateServer);
	//$("span[data-name='server']").each(onUpdateServer);

	$("[data-name='try']").on("click", onTryAction);

	$("pre[data-name='json-examples']").each(function(index, element)
	{
		var jsonString = $(element).html();
		var jsonObject = JSON.parse(jsonString);
		var jsonPretty = JSON.stringify(jsonObject, null, "   ");
		$(this).html("<code>" + jsonPretty + "</code>");
	});
});

function onUpdateServer()
{
	var url = $("input[data-name='server']").val().toLowerCase();
	if (url.indexOf("://") > 0)
	{
		var urlIndex = url.indexOf("://");
		var schema = url.substr(0, urlIndex + 3);
		var server = url.substr(urlIndex + 3, url.length - 1);
		server = server.replace(/\/+$/, '');
		$("[data-name='request-schema']").html(schema);
		$("[data-name='request-server']").html(server);
	}
	else if (url.length > 0)
	{
		url = url.replace(/\/+$/, '');
		$("[data-name='request-schema']").html($("#server").data("schema"));
		$("[data-name='request-server']").html(url);
	}
	else
	{
		$("[data-name='request-schema']").html($("#server").data("schema"));
		$("[data-name='request-server']").html($("#server").data("server"));
	}
}

function onTryAction()
{
	var context = $(this).parent().parent().parent();

	var dataLoader = context.find('.loader');
	dataLoader.removeClass('hidden');

	var dataSchema = context.find('#schema').data('schema');
	var dataServer = context.find('#server').data('server');
	var dataUri = context.find('#uri').data('uri');
	var dataType = context.find('#type').data('type');

	var dataParams = '';
	var dataJSON = {};
	context.find("[data-name='params']").each(function(index, element)
	{
		var param = $(element).find("[data-name='param']").html();
		var value = $(element).find("[data-name='value']").val();
		if (value == undefined)
		{
			value = $(element).find("[data-name='value'] option:selected").text();
		}
		if (value != undefined && value != null && value.length > 0)
		{
			dataParams += (dataParams.length > 0 ? '&' : '') + $.trim(param) + '=' + $.trim(value);
		}
		dataJSON[$.trim(param)] = $.trim(value);
	});

	var get = dataSchema + dataServer + dataUri + (dataParams.length > 0 ? '?' : '') + dataParams;
	var post = dataSchema + dataServer + dataUri;
	var type = dataType.toLowerCase();
	var params = JSON.stringify(dataJSON);

	jQuery.ajax({
		url : "apidox/app/Request.php",
		type : "POST",
		timeout : 10000,
		data : {
			get : get,
			post : post,
			type : type,
			params : params
		}
	}).always(function(response)
	{
		dataLoader.addClass('hidden');

	}).done(function(response)
	{
		var dataResponse = context.find("[data-name='response']");
		dataResponse.jJsonViewer($.trim(response));

	}).fail(function(response)
	{
		var dataResponse = context.find("[data-name='response']");
		dataResponse.jJsonViewer($.trim(response));
	});

}
