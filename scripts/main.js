$(document).ready(onLoadFunc);
//All social network related functions redirected to index.php

function onLoadFunc()
{
	$('#no-script').remove();
	//CSSJobs();
	subForm();
}

function rescaleValue(h1, w1, h2, w2)
{
	var l1 = h1/h2; var l2 = w1/w2;
	if(l1 > l2) {return l2;} else {return l1;}
}

function CSSJobs()
{
	//frame where the data is displayed
	var height = $(document).height(); var fheight = Math.floor(height * 0.10); var mheight = height - fheight; mheight -= 1;
	var width = $(document).width(); var fwidth = Math.floor(width * 0.15); var mwidth = width - fwidth; fheight -= 1;
	var fh = fheight.toString() + "px"; var fw = (fwidth-2).toString() + "px"; var mh = mheight.toString() + "px"; var mw = mwidth.toString() + "px";//var n = fh +","+ fw +","+ mh +","+ mw; alert(n);
	$("#frame1").css({"position": "fixed", "top": "0px", "left": fw, "display": "block"}); //position: absolute; top: 0px; left: 159px; width: 105px; "width": mw, "height": fh
	$("#frame2").css({"position": "fixed", "left": "10px", "top": fh, "display": "block"}); //position: absolute; top: 105px; left: 0px; width: 159px; "width": fw, "height": mh,
	$("#mainFrame").attr({"height": mh, "width": mw});
	$("#mainFrame").css({"position": "fixed", "top": fh, "left": fw, "width": mw });//, "height": mh});
	//Size of logo
	var lwidth = $('img#logo').width(); var lheight = $('img#logo').height(); var rescale = rescaleValue(lheight, lwidth, fheight, fwidth); var lw = (lwidth/rescale).toString() + "px"; var lh = (lheight/rescale).toString() + "px";
	$('img#logo').css({"position": "fixed", "top": "0px", "left": "0px"}); $('img#logo').attr({"height": lh, "width": lw});
	/*//Position of menus
	var hoMenuPos =  $('#hoMenu').position(); var soHomePos = $('#soHome').position(); var logMenuPos = $('#logMenu').position();
	var hoMenuPosTop = hoMenuPos.top + 'px'; var soHomePosTop = soHomePos.top + 'px'; var menuWidth = $('#soHome').width(); var mew = menuWidth.toString() + 'px';
	$('#soHome').css({"position": "absolute", "top": hoMenuPosTop, "left": mew, "width": mew});
	$('#logMenu').css({"position": "absolute", "top": soHomePosTop, "left": mew, "width": mew});
	$('#regMenu').css({"position": "absolute", "top": soHomePosTop, "left": mew, "width": mew});*/
}

function highLightMenu()
{
	//var url = location.href;
	//if(url.match('index.html')!=null)
	var title = document.getElementsByTagName('Title')[0].innerHTML;
	alert(title);
}

function subForm()
{
	//$("form#Searchbox").validate({	rules: {		srchTxt: {			required: true}, }, });
	if($("#srchTxt").length > 0) {	if($("#srchTxt").val().length != 0){	$("form#Searchbox").submit();	}}
	//if($("#del_comment").length > 0) {	deleteComment($("#del_comment"), $("#"))}
}

//Hints for searches
function showHint(e)
{
	if(!e)
	{
		e = window.event;
	}
	var search = $("#srchTxt").val();
	if(search != null) {
		var ajax = $.ajax({url: "search.php", type: "get", data: {srchTxt: search}, success: srchRes2});
		ajax.done(); //ajax.fail(alert("Some error with ajax. Sorry "));	
		//$("#srchTxt").ajaxSubmit({url: "search.php", type: "get", dataType: "html", data: {srchTxt: $("#srchTxt").val()}, success: srchRes});
		//$("#srchTxt").ajaxForm({target:"#searchList",success: srchRes2, url: "search.php", type: "get"});
		//$("#searchResponse").load("search.php",{srchTxt: search}); //		$("#searchResponse").slideDown();
		return false; }
	else {$("#searchResponse").slideUp();}
}

function srchRes(responseText, jqXHR, textStatus)//(responseText, statusText, xhr, $form) with ajaxForm
{
	//alert(responseText + textStatus);
	if(textStatus == "[object Object]")
	{
	if(responseText != "")
		{$("#searchResponse").slideDown(); //Slide down for options
		$("#searchResponse").html(responseText); //Show the options list
		}
	else $("#searchResponse").slideUp();
	}	else alert("Some error in ajax: " + textStatus);//alert(textStatus);
}

function srchRes2(responseText, jqXHR, textStatus)//(responseText, statusText, xhr, $form) with ajaxForm
{
	$("#searchList").html(responseText); //Show the options list
}

function deleteComment(siteurl, comment)
{
	//var element = 'Delete_'+ $('#id_comment').val();
	var ajax = $.ajax({url: siteurl, type: "post", data: {del_comment: comment, deleteComment: 'Delete'}, success: status});
	ajax.done();
	//event.preventDefault();
}

function addComment(siteurl, stat)
{
	var a = "#usr_comment_"+String(stat);//$("#status_id").val();//alert(a);//alert($(a).val());
	if($(a).val() != "")
	{
		var ajax = $.ajax({url: siteurl, type: "post", data: {usr_comment: $(a).val(), addComment: 'Comment'}, success: status});
		ajax.done();
		//event.preventDefault();
	}
	else
	{
		alert('Empty Comment?');
	}
}

function deleteStatus(siteurl)
{
	var ajax = $.ajax({url: siteurl, type: "post", data: {deleteStatus: 'Delete'}, success: status});
	ajax.done();
	//event.preventDefault();
}

function addStatus(siteurl, id)
{
	$('form#newStatus').submit(function(event)
	{
		var formData = new FormData($(this)[0]);
		console.log(siteurl); console.log(formData);
		var ajax = $.ajax({url: siteurl, type: 'POST', data: formData, async: false, success: function(responseText, jqXHR, textStatus){addStuff(responseText, jqXHR, textStatus, id);}, cache: false, contentType: false, processData: false});
		ajax.done();
		event.preventDefault();
	});
	$("input[type='text']").val('');
	canvasApp();
	/*url: siteurl, type: "post", processData: false, contentType: 'multipart/form-data', beforeSend: function (x) {
            if (x && x.overrideMimeType) {
                x.overrideMimeType("multipart/form-data");
            }
        },
        mimeType: 'multipart/form-data',data: formData, success: function(responseText, jqXHR, textStatus){addStuff(responseText, jqXHR, textStatus, id);}});*/
	//$('form#newStatus').submit(function(event){event.preventDefault();});
	//event.preventDefault();
}

function moreStatus(siteurl, id)
{
	var ajax = $.ajax({url: siteurl, type: "post", data: {moreStatus: 'Yes'}, success: function(responseText, jqXHR, textStatus){addStuff(responseText, jqXHR, textStatus, id)}});
	ajax.done();
}

function logOut(siteurl)
{
	var ajax = $.ajax({url: siteurl, success: status});
	ajax.done();
}

function status(responseText, jqXHR, textStatus, id)
{
	if(responseText!="" && textStatus == "[object Object]")
	{
		if(responseText.search("Not") < 0) 
		{ 
			//location.reload(); 
			//$('#mainFrame').reload();
		}
		else
		{
			console.log(responseText);
		}
	}
	else
	{
		console.log('ajax Failed');
	}
}

function addStuff(responseText, jqXHR, textStatus, id)
{
	console.log(responseText);
	if(responseText!="" && textStatus == "[object Object]")
	{
		if(responseText.search("stream404") >= 0){
			$('#moreStream').hide();
			console.log('No more stream');
		}
		else if(id=='moreStream')
		{
			$('#'+id).prepend(responseText);
		}
		else
		{
			if(id == 'newStatus')
			{
				$('ul.stream').prepend(responseText);
			}
		}
	}
	else
	{
		console.log('ajax Failed');
	}
}