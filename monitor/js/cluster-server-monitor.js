/*
 * Cluster Server Monitor
 * visualize 'w', 'vmstat' and 'top' command.
 * version 1.1
 */

var panel = 0;
var ready = 0;
var url = "monitor's url";

function init(){
	if( ready == 1 )
		return;
	panel = 0;
	ready = 1;
	$("#cpu").fadeIn(0);
	$("#mem").fadeIn(0);
	$("#tables").fadeIn(0);
	$(".bar").fadeIn(0);
	$.get(url+"/infos.php", function(data){

	 	var loadAvgHtml = "<table class=\"table table-striped\"><thead><tr><th></th><th>1m</th><th>5m</th><th>15m</th><tr></thead><tbody>";
	 	if( !!data["niagara"] )
			loadAvgHtml += 	"<tr><td>niagara</td><td>"+data["niagara"].lavg1+"</td><td>"+data["niagara"].lavg5+"</td><td>"+data["niagara"].lavg15+"</td></tr>";
		if( !!data["sarajevo"] )
			loadAvgHtml += 	"<tr><td>sarajevo</td><td>"+data["sarajevo"].lavg1+"</td><td>"+data["sarajevo"].lavg5+"</td><td>"+data["sarajevo"].lavg15+"</td></tr>";
		if( !!data["endevour"] )
			loadAvgHtml += 	"<tr><td>endevour</td><td>"+data["endevour"].lavg1+"</td><td>"+data["endevour"].lavg5+"</td><td>"+data["endevour"].lavg15+"</td></tr>";
		if( !!data["phoenix"] )
			loadAvgHtml += 	"<tr><td>phoenix</td><td>"+data["phoenix"].lavg1+"</td><td>"+data["phoenix"].lavg5+"</td><td>"+data["phoenix"].lavg15+"</td></tr>";
		loadAvgHtml += 	"</tbody></table>";

		var procsHtml   = "<table class=\"table table-striped\"><thead><tr><th></th><th>running</th><th>blocked</th><tr></thead><tbody>";
		if( !!data["niagara"] )
			procsHtml += "<tr><td>niagara</td><td>"+data["niagara"].proc_r+"</td><td>"+data["niagara"].proc_b+"</td></tr>";
		if( !!data["sarajevo"] )
			procsHtml += "<tr><td>sarajevo</td><td>"+data["sarajevo"].proc_r+"</td><td>"+data["sarajevo"].proc_b+"</td></tr>";
		if( !!data["endevour"] )
			procsHtml += "<tr><td>endevour</td><td>"+data["endevour"].proc_r+"</td><td>"+data["endevour"].proc_b+"</td></tr>";
		if( !!data["phoenix"] )
			procsHtml += "<tr><td>phoenix</td><td>"+data["phoenix"].proc_r+"</td><td>"+data["phoenix"].proc_b+"</td></tr>";
		procsHtml += "</tbody></table>";

		// LOAD AVERAGE
        	$("#lavg").append(loadAvgHtml);

		// PROCESS INFORMATION
		$("#procs").append(procsHtml);

		// MEMORY PERCENTAGE
		$("#niagara-mem").append( buildMemHtml(data["niagara"]) );
		$("#sarajevo-mem").append( buildMemHtml(data["sarajevo"]) );
		$("#endevour-mem").append( buildMemHtml(data["endevour"]) );
		$("#phoenix-mem").append( buildMemHtml(data["phoenix"]) );
		
		// CPU PERCENTAGE
		$("#niagara-cpu").append( buildCpuHtml(data["niagara"]) );
		$("#sarajevo-cpu").append( buildCpuHtml(data["sarajevo"]) );
		$("#endevour-cpu").append( buildCpuHtml(data["endevour"]) );
		$("#phoenix-cpu").append( buildCpuHtml(data["phoenix"]) );

	    	$(".bar").fadeOut(0);
		ready = 0;


		function buildCpuHtml(clusterArr){
			if( !clusterArr )
				return;
			var html = "<img src=\"http://chart.apis.google.com/chart?cht=p3&chd=t:";
			html += clusterArr["cpu_us"]/100+",";
			html += clusterArr["cpu_sy"]/100+",";
			html += clusterArr["cpu_id"]/100+",";
			html += clusterArr["cpu_wt"]/100+"";
			html += "&chs=250x110&chl=user|system|idle|iowait&chco=FF0000|FFA500|00FF00|0000FF\">";
			return html;
		}
		
		function buildMemHtml(clusterArr){
			if( !clusterArr )
				return;
			var totalMem = 80000000;
			var html = "<img src=\"http://chart.apis.google.com/chart?cht=p3&chd=t:";
			html += clusterArr["mem_sw"]/totalMem+",";
			html += clusterArr["mem_fr"]/totalMem+",";
			html += clusterArr["mem_bf"]/totalMem+",";
			html += clusterArr["mem_ch"]/totalMem+"";
			html += "&chs=250x110&chl=swpd|free|buff|cache&chco=FFA500|00FF00|FF0000|0000FF\">";
			return html;
		}
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

function intervalLoad(){
	refresh();
	init();
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

setInterval("intervalLoad()",600000); //10分ごとに自動更新

