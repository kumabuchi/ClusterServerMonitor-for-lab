/*
 * Cluster Server Monitor
 * visualize 'w', 'vmstat' and 'top' command.
 * version 1.0 
 */

var panel = 0;
var ready = 0;
var url = "###";

function init(){
	if( ready == 1 )
		return;
	panel = 0;
	ready = 1;
	$("#cpu").fadeIn(0);
	$("#mem").fadeIn(0);
	$("#tables").fadeIn(0);
	$(".bar").fadeIn(0);
	$.get(url, function(data){
		var spData = data.split(/\s+/g);
		var totalMem = 80000000;
		var loadAvgHtml= "<table class=\"table table-striped\"><thead><tr><th></th><th>1m</th><th>5m</th><th>15m</th><tr></thead><tbody>";
		var procsHtml = "<table class=\"table table-striped\"><thead><tr><th></th><th>running</th><th>blocked</th><tr></thead><tbody>";
		var memoryHtmlN = "<img src=\"http://chart.apis.google.com/chart?cht=p3&chd=t:";
		var memoryHtmlS = "<img src=\"http://chart.apis.google.com/chart?cht=p3&chd=t:";
		var memoryHtmlE = "<img src=\"http://chart.apis.google.com/chart?cht=p3&chd=t:";
		var memoryHtmlP = "<img src=\"http://chart.apis.google.com/chart?cht=p3&chd=t:";
		var cpuHtmlN = "<img src=\"http://chart.apis.google.com/chart?cht=p3&chd=t:";
		var cpuHtmlS = "<img src=\"http://chart.apis.google.com/chart?cht=p3&chd=t:";
		var cpuHtmlE = "<img src=\"http://chart.apis.google.com/chart?cht=p3&chd=t:";
		var cpuHtmlP = "<img src=\"http://chart.apis.google.com/chart?cht=p3&chd=t:";
		for(var i=1; i<spData.length; i++ ){
		    //console.log(i+"   "+spData[i]); //debug
		    var contentsNo = i%50;
		    switch(contentsNo){
		    case 1:
			loadAvgHtml += "<tr>";
			procsHtml += "<tr>";
			break;
		    case 10:
			if( i<=50 )
			    loadAvgHtml += "<td>niagara</td>";
			else if( i<=100 )
			    loadAvgHtml += "<td>sarajevo</td>";
			else if( i<=150 )
			    loadAvgHtml += "<td>endevour</td>";
			else if( i<=200 )
			    loadAvgHtml += "<td>phoenix</td>";
		    case 11:
		    case 12:
			loadAvgHtml += "<td>"+spData[i].replace(",","")+"</td>";
			break;
		    case 35:
			if( i<=50 )
			    procsHtml += "<td>niagara</td>";
			else if( i<=100 )
			    procsHtml += "<td>sarajevo</td>";
			else if( i<=150 )
			    procsHtml += "<td>endevour</td>";
			else if( i<=200 )
			    procsHtml += "<td>phoenix</td>";
		    case 36:
			procsHtml +="<td>"+spData[i]+"</td>";
			break;
		    case 37:
		    case 38:
		    case 39:
			if( i<=50 )
			    memoryHtmlN += spData[i]/totalMem+",";
			else if( i<=100 )
			    memoryHtmlS += spData[i]/totalMem+",";
			else if( i<=150 )
			    memoryHtmlE += spData[i]/totalMem+",";
			else if( i<=200 )
			    memoryHtmlP += spData[i]/totalMem+",";	
			break;
		    case 40:
			if( i<=50 )
			    memoryHtmlN += spData[i]/totalMem;
			else if( i<=100 )
			    memoryHtmlS += spData[i]/totalMem;
			else if( i<=150 )
			    memoryHtmlE += spData[i]/totalMem;
			else if( i<=200 )
			    memoryHtmlP += spData[i]/totalMem;	
			break;
		    case 47:
		    case 48:
		    case 49:
			if( i<=50 )
			    cpuHtmlN += spData[i]/100+",";
			else if( i<=100 )
			    cpuHtmlS += spData[i]/100+",";
			else if( i<=150 )
			    cpuHtmlE += spData[i]/100+",";
			else if( i<=200 )
			    cpuHtmlP += spData[i]/100+",";	
			break;
		    case 0:
			if( i<=50 )
			    cpuHtmlN += String(spData[i]/100);
			else if( i<=100 )
			    cpuHtmlS += String(spData[i]/100);
			else if( i<=150 )
			    cpuHtmlE += String(spData[i]/100);
			else if( i<=200 )
			    cpuHtmlP += String(spData[i]/100);
			loadAvgHtml += "</tr>";
			procsHtml += "</tr>";
			break;
		    }
		}
		loadAvgHtml += "</tbody></table>";
		procsHtml += "</tbody></table>";
		memoryHtmlN += "&chs=250x110&chl=swpd|free|buff|cache&chco=FFA500|00FF00|FF0000|0000FF\">";
		memoryHtmlS += "&chs=250x110&chl=swpd|free|buff|cache&chco=FFA500|00FF00|FF0000|0000FF\">";
		memoryHtmlE += "&chs=250x110&chl=swpd|free|buff|cache&chco=FFA500|00FF00|FF0000|0000FF\">";
		memoryHtmlP += "&chs=250x110&chl=swpd|free|buff|cache&chco=FFA500|00FF00|FF0000|0000FF\">";
		cpuHtmlN += "&chs=250x110&chl=user|system|idle|iowait&chco=FF0000|FFA500|00FF00|0000FF\">";
		cpuHtmlS += "&chs=250x110&chl=user|system|idle|iowait&chco=FF0000|FFA500|00FF00|0000FF\">";
		cpuHtmlE += "&chs=250x110&chl=user|system|idle|iowait&chco=FF0000|FFA500|00FF00|0000FF\">";
		cpuHtmlP += "&chs=250x110&chl=user|system|idle|iowait&chco=FF0000|FFA500|00FF00|0000FF\">";
		
		// LOAD AVERAGE
        	$("#lavg").append(loadAvgHtml);

		// PROCESS INFORMATION
		$("#procs").append(procsHtml);

		// MEMORY PERCENTAGE
		$("#niagara-mem").append(memoryHtmlN);
		$("#sarajevo-mem").append(memoryHtmlS);
		$("#endevour-mem").append(memoryHtmlE);
		$("#phoenix-mem").append(memoryHtmlP);
		
		// CPU PERCENTAGE
		$("#niagara-cpu").append(cpuHtmlN);
		$("#sarajevo-cpu").append(cpuHtmlS);
		$("#endevour-cpu").append(cpuHtmlE);
		$("#phoenix-cpu").append(cpuHtmlP);

	    	$(".bar").fadeOut(0);
		ready = 0;
	    });
}

function refresh(){
	$(".span12").fadeOut(0);
	$("#each").empty();
	$("#about-info").empty();
	$("img").remove();
	$("table").remove();
}

function loadTop( cluster ){
	ready = 1;
	panel = cluster;
	$("#each").fadeIn(0);
	$(".bar").fadeIn(0);
	switch(cluster){
		case 1:
			$("#each").append("<h3>niagara's top</h3>");
			break;
		case 2:
			$("#each").append("<h3>sarajevo's top</h3>");
			break;
		case 3:
			$("#each").append("<h3>endevour's top</h3>");
			break;
		case 4:
			$("#each").append("<h3>phoenix's top</h3>");
			break;
	}
	$.get(url+"/top.php?c="+cluster, function(data){
		var html = "<table class=\"table table-striped\"><thead><tr><th>PID</th><th>USER</th><th>PR</th><th>NI</th><th>VIRT</th><th>RES</th><th>SHR</th><th>S</th><th>%CPU</th><th>%MEM</th><th>TIME+</th><th>COMMAND</th><tr></thead><tbody>";
		var spData = data.split(/\n+/g);
		for( var i=1; i<spData.length-1; i++){
			var spLine = spData[i].split(/\s+/g,13);
			var fix = 0;
			if( spLine[0] == "" )
				fix = 1;
			if( spLine[1+fix] != "root" )
				html += "<tr><td>"+spLine[0+fix]+"</td><td>"+spLine[1+fix]+"</td><td>"+spLine[2+fix]+"</td><td>"+spLine[3+fix]+"</td><td>"+spLine[4+fix]+"</td><td>"+spLine[5+fix]+"</td><td>"+spLine[6+fix]+"</td><td>"+spLine[7+fix]+"</td><td>"+spLine[8+fix]+"</td><td>"+spLine[9+fix]+"</td><td>"+spLine[10+fix]+"</td><td>"+spLine[11+fix]+"</td></tr>";
		}
		html += "</tbody></table>";
		console.log(html);
		$("#each").append(html);	
		$(".bar").fadeOut(0);
		ready = 0;
	});
}

function reload(){
	if( ready == 1 )
		return;
	refresh();
	switch(panel){
	case 0:
		init();
		break;
	case 1:
	case 2:
	case 3:
	case 4:
		loadTop(panel);
		break;
	case 5:
		$("#about").click();
		break;	
	}
}

$("#page-title").click(function(){
	refresh();
	init();
});

$(".btn-primary").click(function(){
	refresh();
	reload();
});

$("#all-clusters").click(function(){
	refresh();
	init();
});

$("#niagara").click(function(){
	refresh();
	loadTop(1);
});

$("#sarajevo").click(function(){
	refresh();
	loadTop(2);
});

$("#endevour").click(function(){
	refresh();
	loadTop(3);
});

$("#phoenix").click(function(){
	refresh();
	loadTop(4);
});

$("#about").click(function(){
	panel = 5;
	refresh();
	$("#about-info").fadeIn(500);
	var html = "<h3>about this application</h3><p class=\"lead\">Cluster Server Monitor can only be accessed from the network in kobe-u.<br/>Please feedback and request about this application...</p>"
	$("#about-info").append(html);
});



setInterval("init()",600000); //10分ごとに自動更新

