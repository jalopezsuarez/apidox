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
		spyOffset : 128
	// specify heading offset for spy scrolling
	});

	$("a[data-name='link']").each(function(index, element)
	{
		var url = $("input[data-name='service']").val().toLowerCase();
		if (url.indexOf("://") > 0)
		{
			var indexSchema = url.indexOf("://");
			var schema = url.substr(0, indexSchema + 3);
			var server = url.substr(indexSchema + 3, url.length - 1);
			server = server.replace(/\/+$/, '');
			var uri = schema + server + $(element).find("[data-name='server']").data("uri");
			$(element).find("[data-name='server']").data('server', schema + server);
			$(element).find("[data-name='server']").html(uri);
			$(element).attr("href", uri);
		}
		else if (url.length > 0)
		{
			var schema = $('#service').data('schema');
			var server = url.replace(/\/+$/, '');
			var uri = schema + server + $(element).find("[data-name='server']").data("uri");
			$(element).find("[data-name='server']").data('server', schema + server);
			$(element).find("[data-name='server']").html(uri);
			$(element).attr("href", uri);
		}
		else
		{
			var schema = $('#service').data('schema');
			var server = $('#service').data('server');
			var uri = schema + server + $(element).find("[data-name='server']").data("uri");
			$(element).find("[data-name='server']").data('server', schema + server);
			$(element).find("[data-name='server']").html(uri);
			$(element).attr("href", uri);
		}
	});

	$("[data-name='json-examples']").each(function(index, element)
	{
		var jsonString = $(element).html();
		var jsonObject = {};
		var jsonPretty = "";
		try
		{
			jsonObject = JSON.parse(jsonString);
		}
		catch (e)
		{
			jsonObject = {
				exception : '' + e + ''
			};
		}
		try
		{
			jsonPretty = JSON.stringify(jsonObject, null, "   ");
		}
		catch (e)
		{
			jsonPretty = "";
		}
		$(element).html("<code>" + jsonPretty + "</code>");
	});

	$("input[data-name='service']").on("keyup", onUpdateServer);
	$("[data-name='try']").on("click", onResourceAction);
});

function onUpdateServer(index, element)
{
	var url = $("input[data-name='service']").val().toLowerCase();
	if (url.indexOf("://") > 0)
	{
		var indexSchema = url.indexOf("://");
		var schema = url.substr(0, indexSchema + 3);
		var server = url.substr(indexSchema + 3, url.length - 1);
		server = server.replace(/\/+$/, '');
		$("[data-name='link']").each(function(index, element)
		{
			var uri = schema + server + $(element).find("[data-name='server']").data("uri") + $(element).find("[data-name='server']").data("param");
			$(element).find("[data-name='server']").data('server', schema + server);
			$(element).find("[data-name='server']").html(uri);
			$(element).attr("href", uri);
		});
	}
	else if (url.length > 0)
	{
		var schema = $('#service').data('schema');
		var server = url.replace(/\/+$/, '');
		var uri = schema + server;
		$("[data-name='link']").each(function(index, element)
		{
			var uri = schema + server + $(element).find("[data-name='server']").data("uri") + $(element).find("[data-name='server']").data("param");
			$(element).find("[data-name='server']").data('server', schema + server);
			$(element).find("[data-name='server']").html(uri);
			$(element).attr("href", uri);
		});
	}
	else
	{
		var schema = $('#service').data('schema');
		var server = $('#service').data('server');
		var uri = schema + server;
		$("[data-name='link']").each(function(index, element)
		{
			var uri = schema + server + $(element).find("[data-name='server']").data("uri") + $(element).find("[data-name='server']").data("param");
			$(element).find("[data-name='server']").data('server', schema + server);
			$(element).find("[data-name='server']").html(uri);
			$(element).attr("href", uri);
		});
	}
}

function onResourceAction()
{
	var context = $(this).parent().parent().parent();

	var dataLoader = context.find('.loader');
	dataLoader.removeClass('hidden');

	var dataServer = context.find("[data-name='server']").data('server');
	var dataUri = context.find("[data-name='server']").data('uri');
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

	var get = dataServer + dataUri + (dataParams.length > 0 ? '?' : '') + dataParams;
	var post = dataServer + dataUri;
	var type = dataType.toLowerCase();
	var params = JSON.stringify(dataJSON);

	context.find("[data-name='server']").html(get);
	context.find("[data-name='server']").data('param', (dataParams.length > 0 ? '?' : '') + dataParams);
	context.find('#link').attr("href", get);

	var requestTime = new Date().getTime();

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

		var responseTime = new Date().getTime() - requestTime;
		date = new Date(responseTime);
		var responseString = "Time ";
		if (date.getMinutes() > 0)
			responseString += date.getMinutes() + " min ";
		if (date.getSeconds() > 0)
			responseString += date.getSeconds() + " sec ";
		if (date.getMilliseconds() > 0)
			responseString += date.getMilliseconds() + " ms";
		context.find("[data-name='status']").html(responseString);

	}).done(function(response)
	{
		var dataResponse = context.find("[data-name='response']");
		dataResponse.jJsonViewer($.trim(response));

	}).fail(function(jqXHR, textStatus, errorThrown)
	{
		var dataResponse = context.find("[data-name='response']");
		var jsonResponse = {
			exception : 'ServerResponse status ' + textStatus
		};
		dataResponse.jJsonViewer(jsonResponse);
	});

}
