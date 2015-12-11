$(document).ready(canvasApp);
var ratedVal = 0; var oldRate = 0;

function rateSend(type, siteurl, id, r)
{
	var ajax = $.ajax({url: siteurl, type: "post", data: {rate_type: type, rate_value: r, rate_id: id}, success: canvStatus});
	ajax.done();
}

function canvStatus(responseText, jqXHR, textStatus)
{
	if(responseText!="" && textStatus == "[object Object]")
	{
		console.log(responseText);
		if(responseText.search("Not") < 0 && responseText.search("rated") < 0) 
		{ 
			for(i = 0; i < document.getElementsByTagName('canvas').length; i++)
			{
				//document.getElementsByTagName('canvas').item(i).reload();
				location.reload();
			}
		}
		else
		{
			alert(responseText);
		}
	}
	else
	{
		console.log('ajax Failed');
	}
}

function canvasApp()
{
	if(!Modernizr.canvas)
	{
		return;
	}
	else
	{
		var canvas = $('.rateCanvas1');
		var n = parseInt(canvas.length);
		for(i=0; i < n; i++)
		{
			var a = canvas.eq(i).parent().attr('id');
			defaultRate(a);
		}
	}
}
function defaultRate(s)
{
	//console.log(data[0].search('-'));
	var data = s.split('_');
	if(data[0].search('-') == 1)
	{
		oldRate = data[0].replace('-','.'); oldRate = parseFloat(oldRate); 
	}
	else
	{
		oldRate = parseFloat(data[0]);
	}
	fillBar(oldRate, data);
	//hoverRemove(data[0], data[3], data[2]);
	//hoverResultProgressBar(oldRate, type, id);
}

function hoverRate(s, r)
{
	var data = s.split('_');
	fillBar(r, data);
}

function fillBar(r, data)
{
	//console.log(r);
	var id = data[0]+'_'+data[1]+'_'+data[2]+'_'+data[3];//+'.'+data[2]; '#'+
	switch(leastInteger(r))
	{
		case 0:
			//Less than 1 - Red FF0000
			//console.log(0+data);
			var ctx = document.getElementById(id).childNodes.item(1).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx.fillStyle = "#FF0000"; r = parseInt(30*r);
			ctx.fillRect(1,1, r, 15);
			ctx = document.getElementById(id).childNodes.item(3).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(5).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(7).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(9).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			break;
		case 1:
			//Less than 2 - Orange
			//console.log(1+data);
			var ctx = document.getElementById(id).childNodes.item(1).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FF6600";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(3).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx.fillStyle = "#FF6600";r = parseInt(30*(r-1));
			ctx.fillRect(1,1, r, 15);
			ctx = document.getElementById(id).childNodes.item(5).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(7).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(9).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			break;
		case 2:
			//Less than 3 - Yellow
			//console.log(2+data);
			var ctx = document.getElementById(id).childNodes.item(1).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFF00";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(3).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFF00";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(5).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx.fillStyle = "#FFFF00";r = parseInt(30*(r-2));
			ctx.fillRect(1,1, r, 15);
			ctx = document.getElementById(id).childNodes.item(7).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(9).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			break;
		case 3:
			//Less than 4 - Green
			//console.log(3+data);
			var ctx = document.getElementById(id).childNodes.item(1).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#66FF33";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(3).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#66FF33";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(5).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#66FF33";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(7).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx.fillStyle = "#66FF33";r = parseInt(30*(r-3));
			ctx.fillRect(1,1, r, 15);
			ctx = document.getElementById(id).childNodes.item(9).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			break;
		case 4:
			//Less than 5 - Blue
			//if(r==4.5){console.log(r);}
			var ctx = document.getElementById(id).childNodes.item(1).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#66FF33";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(3).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#66FF33";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(5).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#66FF33";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(7).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#66FF33";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(9).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx.fillStyle = "#66FF33"; r = parseInt(30*(r-4)); 
			ctx.fillRect(1,1, r, 15);
			break;
			
		default:
			//5
			var ctx = document.getElementById(id).childNodes.item(1).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(3).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(5).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(7).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			ctx = document.getElementById(id).childNodes.item(9).getContext('2d');
			ctx.strokeStyle = "#000000";
			ctx.strokeRect(0,0,31,16);
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(1,1, 30, 15);
			break;
	}
}

function leastInteger(n)
{
	return (n>parseInt(n))? parseInt(n): parseInt(n) - 1;
}