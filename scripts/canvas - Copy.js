$(document).ready(canvasApp);
var ratedVal = 0; var oldRate = 0;

function hangApp()
{
	if(!Modernizr.canvas)
	{
		return;
	}
	else
	{
		var canvas = document.getElementsByClassName('rateCanvas1');
		for(i = 0; i < 10; i++) //canvas.length
		{
			
			var canvas = document.getElementsByClassName('rateCanvas1');
			var context = canvas[i].getContext('2d');
			context.strokeStyle = "#000000";
			context.strokeRect(0, 0, 31, 16);
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			var $elem = $('.rateCanvas1');
			var top = $elem.eq(i).position().top;//css('top');
			var left = $elem.eq(i).position().left;//css('left'); console.log(left);
			$elem.eq(i).position({'top': top, 'left': left});
			var a = $elem.eq(i).parent().attr('id');//mouseout(function(){rateClear()});
			if(a.indexOf(',')!= 0) 
			{
				var array = a.split(','); //console.log(array);
				hoverRemove(array[0], array[3], array[2]);
			}
			else{hoverRemove(0, array[3], array[2]);}
			
			var canvas = document.getElementsByClassName('rateCanvas2');
			var context = canvas[i].getContext('2d');
			context.strokeStyle = "#000000";
			context.strokeRect(0, 0, 31, 16);
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			left += 20;
			var $elem = $('.rateCanvas2');
			$elem.eq(i).position({'top': top, 'left': left}); //'position': 'absolute', 
		
			var canvas = document.getElementsByClassName('rateCanvas3');
			var context = canvas[i].getContext('2d');
			context.strokeStyle = "#000000";
			context.strokeRect(0, 0, 31, 16);
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			left += 20;
			var $elem = $('.rateCanvas3');
			$elem.eq(i).position({'top': top, 'left': left});
		
			var canvas = document.getElementsByClassName('rateCanvas4');
			var context = canvas[i].getContext('2d');
			context.strokeStyle = "#000000";
			context.strokeRect(0, 0, 31, 16);
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			left += 30;
			var $elem = $('.rateCanvas4');
			$elem.eq(i).position({'top': top, 'left': left});
		
			var canvas = document.getElementsByClassName('rateCanvas5');
			var context = canvas[i].getContext('2d');
			context.strokeStyle = "#000000";
			context.strokeRect(0, 0, 31, 16);
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			left += 30;
			var $elem = $('.rateCanvas5');
			$elem.eq(i).position({'top': top, 'left': left});
		}
	}
}

function hoverRemove(n, type, id)
{
	var doc; n = parseFloat(n); //console.log(type);
	if(type == 'status')
	{
		for(i = 0; i < document.getElementsByTagName('span').length; i++)
		{
			for(j = 0; j < document.getElementsByClassName(id).length; j++)
			{
				if(document.getElementsByClassName(id)[j] == document.getElementsByTagName('span')[i])
				{
					doc = document.getElementsByClassName(id)[j]; //console.log(j);
					break;
				}
			}
		}
	}
	else
	{ 
		for(i = 0; i < document.getElementsByTagName('div').length; i++)
		{
			for(j = 0; j < document.getElementsByClassName(id).length; j++)
			{
				if(document.getElementsByClassName(id)[j] == document.getElementsByTagName('div')[i])
				{
					doc = document.getElementsByClassName(id)[j]; //console.log(j);
					break;
				}
			}
		}
	}
	doc = doc.childNodes; ratedVal = n;
	if(n > 1)
	{
		if(n > 2)
		{
			if(n > 3)
			{
				if(n > 4)
				{
					var canvas = doc.item(9); 
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC"; n = n - 4;
					context.fillRect(1,1, 30*n, 15);
					var canvas = doc.item(7);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(5);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(3);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(1);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC";
					context.fillRect(1,1, 30, 15);
				}
				else
				{
					var canvas = doc.item(9); 
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(7);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC";n = n - 3;
					context.fillRect(1,1, 30*n, 15);
					var canvas = doc.item(5);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(3);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(1);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC";
					context.fillRect(1,1, 30, 15);
				}
			}
			else
			{
				var canvas = doc.item(9);
				var context = canvas.getContext('2d');
				context.fillStyle = "#FFFFFF";
				context.fillRect(1,1, 30, 15);
				var canvas = doc.item(7);
				var context = canvas.getContext('2d');
				context.fillStyle = "#FFFFFF";
				context.fillRect(1,1, 30, 15);
				var canvas = doc.item(5);
				var context = canvas.getContext('2d');
				context.fillStyle = "#FFFFFF";
				context.fillRect(1,1, 30, 15);
				context.fillStyle = "#FFFF00"; n = n - 2;
				context.fillRect(1,1, 30*n, 15);
				var canvas = doc.item(3);
				var context = canvas.getContext('2d');
				context.fillStyle = "#FFFFFF";
				context.fillRect(1,1, 30, 15);
				context.fillStyle = "#FF6600";
				context.fillRect(1,1, 30, 15);
				var canvas = doc.item(1);
				var context = canvas.getContext('2d');
				context.fillStyle = "#FFFFFF";
				context.fillRect(1,1, 30, 15);
				context.fillStyle = "#FF0000";
				context.fillRect(1,1, 30, 15);
			}
		}
		else
		{
			var canvas = doc.item(9);
			var context = canvas.getContext('2d');
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			var canvas = doc.item(7);
			var context = canvas.getContext('2d');
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			var canvas = doc.item(5);
			var context = canvas.getContext('2d');
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			var canvas = doc.item(3);
			var context = canvas.getContext('2d');
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			context.fillStyle = "#FF6600"; n = n - 1;
			context.fillRect(1,1, 30*n, 15);
			var canvas = doc.item(1);
			var context = canvas.getContext('2d');
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			context.fillStyle = "#FF0000";
			context.fillRect(1,1, 30, 15);
		}
	}
	else
	{
		var canvas = doc.item(9);
		var context = canvas.getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		var canvas = doc.item(7);
		var context = canvas.getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		var canvas = doc.item(5);
		var context = canvas.getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		var canvas = doc.item(3);
		var context = canvas.getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		var canvas = doc.item(1);
		var context = canvas.getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		context.fillStyle = "#FF0000";
		context.fillRect(1,1, 30*n, 15);
	}
}

function hoverResult(n, type, id)
{
	var doc;
	if(type == 'status')
	{
		for(i = 0; i < document.getElementsByTagName('span').length; i++)
		{
			for(j = 0; j < document.getElementsByClassName(id).length; j++)
			{
				if(document.getElementsByClassName(id)[j] == document.getElementsByTagName('span')[i])
				{
					doc = document.getElementsByClassName(id)[j]; //console.log(j);
					break;
				}
			}
		}
	}
	else
	{
		for(i = 0; i < document.getElementsByTagName('div').length; i++)
		{
			for(j = 0; j < document.getElementsByClassName(id).length; j++)
			{
				if(document.getElementsByClassName(id)[j] == document.getElementsByTagName('div')[i])
				{
					doc = document.getElementsByClassName(id)[j]; //console.log(j);
					break;
				}
			}
		}
	}//console.log(doc);
	doc = doc.childNodes; 
	if(n > 1)
	{
		if(n > 2)
		{
			if(n > 3)
			{
				if(n > 4)
				{ratedVal = 5;
					var canvas = doc.item(9); 
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(7);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(5);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(3);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(1);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#00FFCC";
					context.fillRect(1,1, 30, 15);
				}
				else
				{ratedVal = 4;
					var canvas = doc.item(9); 
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(7);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#66FF33";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(5);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#66FF33";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(3);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#66FF33";
					context.fillRect(1,1, 30, 15);
					var canvas = doc.item(1);
					var context = canvas.getContext('2d');
					context.fillStyle = "#FFFFFF";
					context.fillRect(1,1, 30, 15);
					context.fillStyle = "#66FF33";
					context.fillRect(1,1, 30, 15);
				}
			}
			else
			{ratedVal = 3;
				var canvas = doc.item(9); 
				var context = canvas.getContext('2d');
				context.fillStyle = "#FFFFFF";
				context.fillRect(1,1, 30, 15);
				var canvas = doc.item(7); 
				var context = canvas.getContext('2d');
				context.fillStyle = "#FFFFFF";
				context.fillRect(1,1, 30, 15);
				var canvas = doc.item(5);
				var context = canvas.getContext('2d');
				context.fillStyle = "#FFFFFF";
				context.fillRect(1,1, 30, 15);
				context.fillStyle = "#FFFF00";
				context.fillRect(1,1, 30, 15);
				var canvas = doc.item(3);
				var context = canvas.getContext('2d');
				context.fillStyle = "#FFFFFF";
				context.fillRect(1,1, 30, 15);
				context.fillStyle = "#FFFF00";
				context.fillRect(1,1, 30, 15);
				var canvas = doc.item(1);
				var context = canvas.getContext('2d');
				context.fillStyle = "#FFFFFF";
				context.fillRect(1,1, 30, 15);
				context.fillStyle = "#FFFF00";
				context.fillRect(1,1, 30, 15);
			}
		}
		else
		{ratedVal = 2;
			var canvas = doc.item(9); 
			var context = canvas.getContext('2d');
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			var canvas = doc.item(7); 
			var context = canvas.getContext('2d');
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			var canvas = doc.item(5); 
			var context = canvas.getContext('2d');
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			var canvas = doc.item(3);
			var context = canvas.getContext('2d');
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			context.fillStyle = "#FF6600";
			context.fillRect(1,1, 30, 15);
			var canvas = doc.item(1);
			var context = canvas.getContext('2d');
			context.fillStyle = "#FFFFFF";
			context.fillRect(1,1, 30, 15);
			context.fillStyle = "#FF6600";
			context.fillRect(1,1, 30, 15);
		}
	}
	else
	{ratedVal = 1;
		var canvas = doc.item(9);
		var context = canvas.getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		var canvas = doc.item(7);
		var context = canvas.getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		var canvas = doc.item(5);
		var context = canvas.getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		var canvas = doc.item(3);
		var context = canvas.getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		var canvas = doc.item(1);
		var context = canvas.getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		context.fillStyle = "#FF0000";
		context.fillRect(1,1, 30, 15);
	}
}

function rateClear()
{
	ratedVal = 0;
	var doc = document.getElementsByClassName('rateCanvas1');
	for(i = 0; i < doc.length; i++)
	{
		var canvas = document.getElementsByClassName('rateCanvas1');
		var context = canvas[i].getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		var canvas = document.getElementsByClassName('rateCanvas2');
		var context = canvas[i].getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		var canvas = document.getElementsByClassName('rateCanvas3');
		var context = canvas[i].getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		var canvas = document.getElementsByClassName('rateCanvas4');
		var context = canvas[i].getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
		var canvas = document.getElementsByClassName('rateCanvas5');
		var context = canvas[i].getContext('2d');
		context.fillStyle = "#FFFFFF";
		context.fillRect(1,1, 30, 15);
	}
}

function rateApp(data, rate, e, type, id)
{
	if(typeof e != 'undefined')
	{
		//var x = e.clientX - 242; 		x = x/($('.rateCanvas1').width()); 		ratedVal = x; 		hoverResult(x, type, id);
		if(data.indexOf(',')!= 0) {array = data.split(','); oldRate = parseInt(array[0]);} else{oldRate = 0;}
		if(rate == 0) {ratedVal = oldRate; hoverRemove(oldRate, type, id);} else{ ratedVal = rate; hoverResult(rate, type, id);}
		if(type == 'status')
		{
			for(i = 0; i < document.getElementsByTagName('span').length; i++)
			{
				for(j = 0; j < document.getElementsByClassName(id).length; j++)
				{
					if(document.getElementsByClassName(id)[j] == document.getElementsByTagName('span')[i])
					{
						doc = document.getElementsByClassName(id)[j];
						break;
					}
				}
			}
		}
		else
		{
			for(i = 0; i < document.getElementsByTagName('div').length; i++)
			{
				for(j = 0; j < document.getElementsByClassName(id).length; j++)
				{
					if(document.getElementsByClassName(id)[j] == document.getElementsByTagName('div')[i])
					{
						doc = document.getElementsByClassName(id)[j];
						break;
					}
				}
			}
		}
		if(doc.length != 0)
		{
			for(i = 1; i < doc.length; i= i+2)
			{
				doc = doc.childNodes.item(i);
				if(doc != null || typeof doc != 'undefined')
				{
					break;
				}
			}
		}
	}
}

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

function trashCode()
{
	//$('.postedtime').eq(i).position().top;
			//top = top - pos1.top;
			//left = left - pos1.left;
			//var top = window.getComputedStyle(canvas[i],null).getPropertyValue("top");
			//var left = window.getComputedStyle(canvas[i],null).getPropertyValue("left");
			//$elem.eq(i).css("position", "absolute");
			//var pos1 = $('.frame1').css('top');
			//var top = pos.top;// - pos1;// - Math.floor($(document).height() * 0.10) - 12;
			//left = top[1];
			//$("#canvasLoc").html("");
			//console.log(x);
			//$("#canvasLoc").html(n);
	/*switch(parseInt(r))
	{
		case 0:
			//Less than 1 - Red
			//console.log(0+data);
			$(id+', .rateCanvas1').drawRect({ fillStyle: '#FF0000', width: 50*r, height: 15});
			$(id+', .rateCanvas2').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 15});
			$(id+', .rateCanvas3').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 15});
			$(id+', .rateCanvas4').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 15});
			$(id+', .rateCanvas5').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 15});
			break;
		case 1:
			//Less than 2 - Orange
			//console.log(1+data);
			$(id+', .rateCanvas1').drawRect({ fillStyle: '#FF6600', width: 50, height: 25}); var a = (r==1)? (r-1) : r;
			$(id+', .rateCanvas2').drawRect({ fillStyle: '#FF6600', width: 50*a, height: 25});
			$(id+', .rateCanvas3').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
			$(id+', .rateCanvas4').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
			$(id+', .rateCanvas5').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
			break;
		case 3:
			//Less than 3 - Yellow
			//console.log(2+data);
			$(id+', .rateCanvas1').drawRect({ fillStyle: '#FFFF00', width: 50, height: 25});
			$(id+', .rateCanvas2').drawRect({ fillStyle: '#FFFF00', width: 50, height: 25});var a = (r==2)? (r-2) : (r-1);
			$(id+', .rateCanvas3').drawRect({ fillStyle: '#FFFF00', width: 50*a, height: 25});
			$(id+', .rateCanvas4').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
			$(id+', .rateCanvas5').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
			break;
		case 4:
			//Less than 4 - Green
			//console.log(3+data);
			$(id+', .rateCanvas1').drawRect({ fillStyle: '#66FF33', width: 50, height: 25});
			$(id+', .rateCanvas2').drawRect({ fillStyle: '#66FF33', width: 50, height: 25});
			$(id+', .rateCanvas3').drawRect({ fillStyle: '#66FF33', width: 50, height: 25});var a = (r==3)? (r-3) : (r-2);
			$(id+', .rateCanvas4').drawRect({ fillStyle: '#66FF33', width: 50*a, height: 25});
			$(id+', .rateCanvas5').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
			break;
		case 5:
			//Less than 5 - Blue
			//console.log(5+data);
			$(id+', .rateCanvas1').drawRect({ fillStyle: '#00FF00', width: 50, height: 25});
			$(id+', .rateCanvas2').drawRect({ fillStyle: '#00FF00', width: 50, height: 25});
			$(id+', .rateCanvas3').drawRect({ fillStyle: '#00FF00', width: 50, height: 25});
			$(id+', .rateCanvas4').drawRect({ fillStyle: '#00FF00', width: 50, height: 25});var a = (r==4)? (r-4) : (r-3); 
			$(id+', .rateCanvas5').drawRect({ fillStyle: '#00FF00', width: 50*a, height: 25});
			break;
			
		default:
			break;
	}*/
	
	//var $elem = $(id);
	//$(id).find('canvas.ratecanvas1').drawRect({fillStyle: '#666', x: 150, y: 100, width: 200, height: 100, fromCenter: false});
	/*$(id).find('canvas.ratecanvas1').draw({fn:function(ctx){
		ctx.strokeStyle = '#000000';
		ctx.strokeRect(0, 0, 31, 16);
		ctx.fillStyle = '#FFFFFF';
		ctx.fillRect(1,1, 30, 15);
	}});*/
	//var el = $(id+', .rateCanvas1');//.next();//All('canvas').eq(0);
	//el.drawRect({ fillStyle: '#000', width: 30, height: 15});
	//console.log(el.get(0).getContext('2d'));
	//console.log(parseInt(r) +' '+data);
	
	if(r<=1 && r>0)
	{
		$(id+', .rateCanvas1').drawRect({ fillStyle: '#FF0000', width: 50*r, height: 25});
		$(id+', .rateCanvas2').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
		$(id+', .rateCanvas3').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
		$(id+', .rateCanvas4').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
		$(id+', .rateCanvas5').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
	}
	else if(r<=2 && r>1)  
	{
		$(id+', .rateCanvas1').drawRect({ fillStyle: '#66FF33', width: 50, height: 25});
		$(id+', .rateCanvas2').drawRect({ fillStyle: '#66FF33', width: 50, height: 25});
		$(id+', .rateCanvas3').drawRect({ fillStyle: '#66FF33', width: 50, height: 25}); var a = (r==3)? (r-3) : (r-2);
		$(id+', .rateCanvas4').drawRect({ fillStyle: '#66FF33', width: 50*a, height: 25});
		$(id+', .rateCanva s5').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
	}
	else if(r<=3 && r>2)
	{
		$(id+', .rateCanvas1').drawRect({ fillStyle: '#FFFF00', width: 50, height: 25});
		$(id+', .rateCanvas2').drawRect({ fillStyle: '#FFFF00', width: 50, height: 25});var a = (r==2)? (r-2) : (r-1);
		$(id+', .rateCanvas3').drawRect({ fillStyle: '#FFFF00', width: 50*a, height: 25});
		$(id+', .rateCanvas4').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
		$(id+', .rateCanvas5').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
	}
	else if(r<=4 && r>3)
	{
		$(id+', .rateCanvas1').drawRect({ fillStyle: '#FF6600', width: 50, height: 25}); var a = (r==1)? (r-1) : r;
		$(id+', .rateCanvas2').drawRect({ fillStyle: '#FF6600', width: 50*a, height: 25});
		$(id+', .rateCanvas3').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
		$(id+', .rateCanvas4').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
		$(id+', .rateCanvas5').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
	}
	else if(r<=5 && r>4)
	{
		$(id+', .rateCanvas1').drawRect({ fillStyle: '#00FF00', width: 50, height: 25});
		$(id+', .rateCanvas2').drawRect({ fillStyle: '#00FF00', width: 50, height: 25});
		$(id+', .rateCanvas3').drawRect({ fillStyle: '#00FF00', width: 50, height: 25});
		$(id+', .rateCanvas4').drawRect({ fillStyle: '#00FF00', width: 50, height: 25});var a = (r==4)? (r-4) : (r-3); 
		$(id+', .rateCanvas5').drawRect({ fillStyle: '#00FF00', width: 50*a, height: 25});
	}
	else
	{
		$(id+', .rateCanvas1').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
		$(id+', .rateCanvas2').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
		$(id+', .rateCanvas3').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
		$(id+', .rateCanvas4').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
		$(id+', .rateCanvas5').drawRect({ fillStyle: '#FFFFFF', width: 50, height: 25});
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