/**
 * jQuery events management controller
 * 
 * @author Asenit Technologies SL
 * @author www.asenit.com
 */

$(document).ready(function()
{
	$('ul.tabs li').click(function()
	{
		var tab_id = $(this).attr('data-tab');
		$(this).parent().parent().find('ul.tabs li').removeClass('current');
		$(this).parent().parent().find('.tab-content').removeClass('current');
		$(this).parent().parent().find(this).addClass('current');
		$(this).parent().parent().find("#" + tab_id).addClass('current');
	})

	$("[data-name='tryit']").on("click", onClickTryIt);

	var context = $(this);
	var endpoints = context.find("[data-name='endpoint']");
	endpoints.each(function()
	{
		var methods = $(this).find(".methods .method").length;
		$(this).find("[data-name='count-methods']").html(methods);
	});

	$("#select-version").change(function()
	{
		$("[data-id='frmVersion']").hide();
		context.find("[data-version='" + $(this).val() + "']").css("display", "block");
		countTotalMethods();
	});

	countTotalMethods();
	setDefaultVersion();
	setDefaultEnumerated();
});

function countTotalMethods()
{
	var endpoints = $("[data-name='endpoint']").filter(":visible");
	var methodsTotal = 0;

	endpoints.each(function()
	{
		var methods = $(this).find(".methods .method");
		methodsTotal += methods.length;
	});

	$("[data-name='total-methods']").html(methodsTotal);
}

function setDefaultVersion()
{
	var version = $("#vselect").val();
	$('option[value="' + version + '"]').prop('selected', 'selected');
}

function setDefaultEnumerated()
{
	$("option[data-default='Y']").each(function()
	{
		$(this).prop('selected', 'selected');
	});
}

/**
 * Usage Google Chrome on Apple: /Applications/Google\
 * Chrome.app/Contents/MacOS/Google\ Chrome --disable-web-security
 */
function onClickTryIt()
{
	$("[data-name='tryit-loading']").removeClass("tryit-oculto");

	var context = $(this).parent().parent();
	var uriQuery = $.trim(context.parent().find("[data-name='uri']").text().toLowerCase());
	var uriServer = $("[data-name='uri']").val();

	var dataQuery = '';
	context.find("[data-name='item']").each(function(index, element)
	{
		var param = $(element).find("[data-name='param']").html();
		var value = $(element).find("[data-name='value']").val();
		if (value == undefined)
			value = $(element).find("[data-name='select'] option:selected").text();
		if (value != undefined && value != null && value.length > 0)
		{
			var separator = (dataQuery.length > 0) ? "&" : "";
			dataQuery += separator + $.trim(param).toLowerCase() + '=' + $.trim(value);
		}
	});
	if (dataQuery.length > 0)
		dataQuery = '?' + dataQuery;

	var url = "http://" + uriServer + "/" + $.trim(uriQuery) + $.trim(dataQuery);
	jQuery.ajax(
	{
		url : "request.php",
		type : "POST",
		timeout : 10000,
		data :
		{
			rq : url
		}
	}).always(function(response)
	{
		$("[data-name='tryit-loading']").addClass("tryit-oculto");
		try
		{
			context.find('.call').html(url);
			context.find('.response').html(prettyPrintJSON($.trim(response)));
		}
		catch (ex)
		{
			context.find('.call').html(url);
			context.find('.response').html("Response JSON Error: " + ex.message + "\n\n" + $.trim(response));
		}

	}).done(function(response)
	{

	}).fail(function(response)
	{
		context.find('.response').html("Request Timeout Error: server response timeout");
	});
}

function prettyPrintJSON(jsonString)
{
	if (!library)
		var library =
		{};

	library.json =
	{
		replacer : function(match, pIndent, pKey, pVal, pEnd)
		{
			var key = '<span class=json-key>';
			var val = '<span class=json-value>';
			var str = '<span class=json-string>';
			var r = pIndent || '';
			if (pKey)
				r = r + key + pKey.replace(/[": ]/g, '') + '</span>: ';
			if (pVal)
				r = r + (pVal[0] == '"' ? str : val) + pVal + '</span>';
			return r + (pEnd || '');
		},
		prettyPrint : function(obj)
		{
			var jsonLine = /^( *)("[\w]+": )?("[^"]*"|[\w.+-]*)?([,[{])?$/mg;
			return JSON.stringify(obj, null, 3).replace(/&/g, '&amp;').replace(/\\"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(jsonLine, library.json.replacer);
		}
	};

	var jsonObj = JSON.parse(jsonString);
	return library.json.prettyPrint(jsonObj);
}
