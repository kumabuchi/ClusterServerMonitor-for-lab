<?php
header("Content-type: application/x-javascript");
require_once("../config.php");
print('var panel = 0;
var ready = 0;
var url = "'.$url.'/sys";
var sortorder = 1;
var sortorderio = 1;
var iostatData = null;
var infosData = null;
var topinfo = null;

function init(){
	if( ready == 1 )
		return;
	panel = 0;
	ready = 1;
	if( slideNo == 1 )
		$("#control").click();
	$("#cpu").fadeIn(0);
	$("#mem").fadeIn(0);
	$("#tables").fadeIn(0);
	$(".bar").fadeIn(0);
	$("#control").removeClass("disabled");
	if( infosData == null )
		$.get(url+"/infos.php", infosCallback);
	else
		infosCallback(infosData);
	
	function infosCallback(data){

	 	var loadAvgHtml = "<table class=\"table table-striped\"><thead><tr><th></th><th>1m</th><th>5m</th><th>15m</th><tr></thead><tbody>";
');

	foreach( $servs as $name => $comm ){
		print('	if( !!data["'.$name.'"] )loadAvgHtml += "<tr><td>'.$name.'</td><td>"+data["'.$name.'"].lavg1+"</td><td>"+data["'.$name.'"].lavg5+"</td><td>"+data["'.$name.'"].lavg15+"</td></tr>";');
	}
	print('loadAvgHtml += "</tbody></table>";

		var procsHtml   = "<table class=\"table table-striped\"><thead><tr><th></th><th>running</th><th>blocked</th><tr></thead><tbody>"; ');
	foreach( $servs as $name => $comm ){
		print('	if( !!data["'.$name.'"] )
			procsHtml += "<tr><td>'.$name.'</td><td>"+data["'.$name.'"].proc_r+"</td><td>"+data["'.$name.'"].proc_b+"</td></tr>";');
	}	
	print('	procsHtml += "</tbody></table>";');

	print('
        	$("#lavg").append(loadAvgHtml);
		$("#procs").append(procsHtml);
	');
	
	foreach( $servs as $name => $key ){
		print('$("#'.$name.'-mem").append( buildMemHtml(data["'.$name.'"]) );');
	}
		
	foreach( $servs as $name => $key ){
		print('$("#'.$name.'-cpu").append( buildCpuHtml(data["'.$name.'"]) );');
	}
	
	print('
	    	$(".bar").fadeOut(0);
		$("#slider").fadeIn(0);
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
			var totalMem =  clusterArr["mem_sw"] + clusterArr["mem_fr"] + clusterArr["mem_bf"] + clusterArr["mem_ch"];
			var html = "<img src=\"http://chart.apis.google.com/chart?cht=p3&chd=t:";
			html += clusterArr["mem_sw"]/totalMem+",";
			html += clusterArr["mem_fr"]/totalMem+",";
			html += clusterArr["mem_bf"]/totalMem+",";
			html += clusterArr["mem_ch"]/totalMem+"";
			html += "&chs=250x110&chl=swpd|free|buff|cache&chco=FFA500|00FF00|FF0000|0000FF\">";
			return html;
		}
	}
	
	if( iostatData == null ){
		iostatData = {};
		$.get(url+"/iostat.php", iostatCallback);
	}else{
		iostatCallback(iostatData);
	}
	
	function iostatCallback(data){
	');

	// IO STATE ONLY ONE SERVER	
	foreach( $servs as $name => $comm ){
		print('$("#'.$name.'-io").html("<h4>'.$name.'</h4>"+ buildIOstatHtml(\''.$name.'\',data["'.$name.'"] ? data["'.$name.'"] :null) );');
	}

	print('
		function buildIOstatHtml(server, cluster ){
			if( !cluster )
				return "NO STATE";
			iostatData[server] = cluster;
			cluster.sort(function(a,b){
				return a.device > b.device ? 1 : -1;
			});
 			var html = "<table class=\"table table-striped\"><thead><tr><th><a href=\"javascript:sortIO(\'"+server+"\',\'device\');\">Device</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'tps\');\">tps</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'blkr_s\');\">Blk_read/s</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'blkw_s\');\">Blk_write/s</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'blkr\');\">Blk_read</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'blkw\');\">Blk_write</a></th><tr></thead><tbody>";
			for( var i=0; i<cluster.length; i++)
				html +=	"<tr><td>"+cluster[i].device+"</td><td>"+cluster[i].tps+"</td><td>"+cluster[i].blkr_s+"</td><td>"+cluster[i].blkw_s+"</td><td>"+cluster[i].blkr+"</td><td>"+cluster[i].blkw+"</td></tr>";
			html +=	"</tbody></table>";
			return html;
		}

	}
}

function sortIO( server,row ){
        if( iostatData == null ){
		return ;
	}
        $(".bar").fadeIn(0);
	iostatData[server].sort(function(a, b) {
		return ( a[row] > b[row] ? sortorderio : sortorderio*(-1));
	});
	sortorderio *= -1;
	var cluster = iostatData[server];
 	var html = "<table class=\"table table-striped\"><thead><tr><th><a href=\"javascript:sortIO(\'"+server+"\',\'device\');\">Device</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'tps\');\">tps</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'blkr_s\');\">Blk_read/s</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'blkw_s\');\">Blk_write/s</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'blkr\');\">Blk_read</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'blkw\');\">Blk_write</a></th><tr></thead><tbody>";
		for( var i=0; i<cluster.length; i++)
			html +=	"<tr><td>"+cluster[i].device+"</td><td>"+cluster[i].tps+"</td><td>"+cluster[i].blkr_s+"</td><td>"+cluster[i].blkw_s+"</td><td>"+cluster[i].blkr+"</td><td>"+cluster[i].blkw+"</td></tr>";
	html += "</tbody></table>";
        $("#"+server+"-io").html("<h4>"+server+"</h4>"+html);
	');
	print('
        $(".bar").fadeOut(0);
}


function refresh(){
	sortorder = 1;
	sortorderio = 1;
	topinfo = null;
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
	$("#control").addClass("disabled");
	switch(cluster){
	');
	foreach( $servs as $name => $comm ){
		print('case "'.$name.'":
			$("#each").append("<h3>'.$name.'\'s top</h3>");
			break;
		');
	}
	print('}
	');
	print('
	$.getJSON(url+"/top.php?c="+cluster, function(datas){
		topinfo = datas;
		var html = "<table id=\"topinfo\" class=\"table table-striped\"><thead><tr><th><a href=\"javascript:sortTop(\'PID\');\">PID</a></th><th><a href=\"javascript:sortTop(\'USER\');\">USER</a></th><th><a href=\"javascript:sortTop(\'PR\');\">PR</a></th><th><a href=\"javascript:sortTop(\'NI\');\">NI</a></th><th><a href=\"javascript:sortTop(\'VIRT\');\">VIRT</a></th><th><a href=\"javascript:sortTop(\'RES\');\">RES</a></th><th><a href=\"javascript:sortTop(\'SHR\');\">SHR</a></th><th><a href=\"javascript:sortTop(\'S\');\">S</a></th><th><a href=\"javascript:sortTop(\'%CPU\');\">%CPU</a></th><th><a href=\"javascript:sortTop(\'%MEM\');\">%MEM</a></th><th><a href=\"javascript:sortTop(\'TIME\');\">TIME+</a></th><th><a href=\"javascript:sortTop(\'COMMAND\');\">COMMAND</a.</th><tr></thead><tbody>";
		for( var i=0; i<datas.length; i++){
			var data = datas[i];
			html += "<tr><td>"+data.PID+"</td><td>"+data.USER+"</td><td>"+data.PR+"</td><td>"+data.NI+"</td><td>"+data.VIRT+"</td><td>"+data.RES+"</td><td>"+data.SHR+"</td><td>"+data.S+"</td><td>"+data["%CPU"]+"</td><td>"+data["%MEM"]+"</td><td>"+data.TIME+"</td><td>"+data.COMMAND+"</td></tr>";
		}
		html += "</tbody></table>";
		$("#each").append(html);	
		$(".bar").fadeOut(0);
		ready = 0;
	});
}

function sortTop( row ){
        if( topinfo == null ){
		return;
	}
	$("#topinfo").remove();
        $(".bar").fadeIn(0);
	console.log(topinfo);
	topinfo.sort(function(a, b) {
		return ( a[row] > b[row] ? sortorder : sortorder*(-1));
	});
	sortorder *= -1;
	var datas = topinfo;
	var html = "<table id=\"topinfo\" class=\"table table-striped\"><thead><tr><th><a href=\"javascript:sortTop(\'PID\');\">PID</a></th><th><a href=\"javascript:sortTop(\'USER\');\">USER</a></th><th><a href=\"javascript:sortTop(\'PR\');\">PR</a></th><th><a href=\"javascript:sortTop(\'NI\');\">NI</a></th><th><a href=\"javascript:sortTop(\'VIRT\');\">VIRT</a></th><th><a href=\"javascript:sortTop(\'RES\');\">RES</a></th><th><a href=\"javascript:sortTop(\'SHR\');\">SHR</a></th><th><a href=\"javascript:sortTop(\'S\');\">S</a></th><th><a href=\"javascript:sortTop(\'%CPU\');\">%CPU</a></th><th><a href=\"javascript:sortTop(\'%MEM\');\">%MEM</a></th><th><a href=\"javascript:sortTop(\'TIME\');\">TIME+</a></th><th><a href=\"javascript:sortTop(\'COMMAND\');\">COMMAND</a.</th><tr></thead><tbody>";
        for( var i=0; i<datas.length; i++){
                var data = datas[i];
                html += "<tr><td>"+data.PID+"</td><td>"+data.USER+"</td><td>"+data.PR+"</td><td>"+data.NI+"</td><td>"+data.VIRT+"</td><td>"+data.RES+"</td><td>"+data.SHR+"</td><td>"+data.S+"</td><td>"+data["%CPU"]+"</td><td>"+data["%MEM"]+"</td><td>"+data.TIME+"</td><td>"+data.COMMAND+"</td></tr>";
        }
        html += "</tbody></table>";
        $("#each").append(html);
        $(".bar").fadeOut(0);
}

function reload(){
	if( ready == 1 )
		return;
	refresh();
	infosData = null;
	iostatData = null;
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
	infosData = null;
	iostatData = null;
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
');

foreach( $servs as $name => $comm ){
print('
$("#'.$name.'").click(function(){
	refresh();
	loadTop("'.$name.'");
});
');
}
print('
$("#about").click(function(){
	panel = 5;
	refresh();
	$("#control").addClass("disabled");
	$("#about-info").fadeIn(500);
	var html = "<h3>about this application</h3><p class=\"lead\">Cluster Server Monitor can only be accessed from the network in kobe-u.<br/>Please feedback and request about this application...</p>"
	$("#about-info").append(html);
});

$(function(){
     $(\'#slider\').slider({
     showControls : true, 
     autoplay     : false,
     fade         : 500, 
     direction    : \'left\'
     });
});

setInterval("intervalLoad()",600000);
');

?>
