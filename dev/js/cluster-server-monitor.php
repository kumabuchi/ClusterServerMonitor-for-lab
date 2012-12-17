<?php
header("Content-type: application/x-javascript");
require_once("../config.php");

print('
var panel = 0;
var ready = 0;
var url = "'.$url.'/sys";
var sortorder = 1;
var sortorderio = 1;
var iostatData = null;
var infosData = null;
var topinfo = null;
var serverName = null;
var loadTime = 0;
var timeHandle = null;
var hist_date = getDate();
var hist_data = null;
var hist_mode = "lavg1";
var observers = null;
var alerts = null;
var userName = null;
var mailAddr = null;

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

		infosData = data;

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

	foreach( $servs as $name => $comm ){
		print('if( data["'.$name.'"] ) $("#'.$name.'-io").html("<h4>'.$name.'</h4>"+ buildIOstatHtml(\''.$name.'\',data["'.$name.'"] ? data["'.$name.'"] :null) ); else  $("#'.$name.'-io").html("");');
	}

	print('
		function buildIOstatHtml(server, cluster ){
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
	$("#chart").fadeOut();
	$("#chart-control").fadeOut(0);
	$("#chart-title").fadeOut(0);
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
			serverName = "'.$name.'";
			break;
		');
	}
	print('}
	');
	print('
	$.getJSON(url+"/top.php?c="+cluster, function(datas){
		topinfo = datas;
		var html = "<table id=\"topinfo\" class=\"table table-striped\"><thead><tr><th><a href=\"javascript:sortTop(\'PID\');\">PID</a></th><th><a href=\"javascript:sortTop(\'USER\');\">USER</a></th><th><a href=\"javascript:sortTop(\'PR\');\">PR</a></th><th><a href=\"javascript:sortTop(\'NI\');\">NI</a></th><th><a href=\"javascript:sortTop(\'VIRT\');\">VIRT</a></th><th><a href=\"javascript:sortTop(\'RES\');\">RES</a></th><th><a href=\"javascript:sortTop(\'SHR\');\">SHR</a></th><th><a href=\"javascript:sortTop(\'S\');\">S</a></th><th><a href=\"javascript:sortTop(\'%CPU\');\">%CPU</a></th><th><a href=\"javascript:sortTop(\'%MEM\');\">%MEM</a></th><th><a href=\"javascript:sortTop(\'TIME\');\">TIME+</a></th><th><a href=\"javascript:sortTop(\'COMMAND\');\">COMMAND</a.</th><tr></thead><tbody>";
		if( !!datas ){
			for( var i=0; i<datas.length; i++){
				var data = datas[i];
				html += "<tr><td><a href=\"javascript:am(\'"+data.PID+"\',\'"+data.USER+"\',\'"+data.COMMAND+"\');\">"+data.PID+"</a></td><td>"+data.USER+"</td><td>"+data.PR+"</td><td>"+data.NI+"</td><td>"+data.VIRT+"</td><td>"+data.RES+"</td><td>"+data.SHR+"</td><td>"+data.S+"</td><td>"+data["%CPU"]+"</td><td>"+data["%MEM"]+"</td><td>"+data.TIME+"</td><td>"+data.COMMAND+"</td></tr>";
			}
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
	var html = "<table id=\"topinfo\" class=\"table table-striped\"><thead><tr><th><a href=\"javascript:sortTop(\'PID\');\">PID</a></th><th><a href=\"javascript:sortTop(\'USER\');\">USER</a></th><th><a href=\"javascript:sortTop(\'PR\');\">PR</a></th><th><a href=\"javascript:sortTop(\'NI\');\">NI</a></th><th><a href=\"javascript:sortTop(\'VIRT\');\">VIRT</a></th><th><a href=\"javascript:sortTop(\'RES\');\">RES</a></th><th><a href=\"javascript:sortTop(\'SHR\');\">SHR</a></th><th><a href=\"javascript:sortTop(\'S\');\">S</a></th><th><a href=\"javascript:sortTop(\'%CPU\');\">%CPU</a></th><th><a href=\"javascript:sortTop(\'%MEM\');\">%MEM</a></th><th><a href=\"javascript:sortTop(\'TIME\');\">TIME+</a></th><th><a href=\"javascript:sortTop(\'COMMAND\');\">COMMAND</a></th><tr></thead><tbody>";
	if( !!datas ){
      		for( var i=0; i<datas.length; i++){
                	var data = datas[i];
                	html += "<tr><td><a href=\"javascript:am(\'data.PID\',\'data.USER\',\'data.COMMAND\');\">"+data.PID+"</a></td><td>"+data.USER+"</td><td>"+data.PR+"</td><td>"+data.NI+"</td><td>"+data.VIRT+"</td><td>"+data.RES+"</td><td>"+data.SHR+"</td><td>"+data.S+"</td><td>"+data["%CPU"]+"</td><td>"+data["%MEM"]+"</td><td>"+data.TIME+"</td><td>"+data.COMMAND+"</td></tr>";
        	}
	}
        html += "</tbody></table>";
        $("#each").append(html);
        $(".bar").fadeOut(0);
}

function reload(){
	if( ready == 1 )
		return;
	refresh();
	switch(panel){
	case 0:
		infosData = null;
		iostatData = null;
		init();
		setTimeInfo(0);
		break;
	case 1:
	case 2:
	case 3:
	case 4:
		break;
	case 5:
		$("#about").click();
		break;	
	case 6:
		history(hist_mode,hist_date);
		break;
	case 7:
		$("#alert-center").click();
		break;
	default :
		loadTop(panel);
		break;
	}
}

function intervalLoad(){
	infosData = null;
	iostatData = null;
	refresh();
	init();
	setTimeInfo(0);
}

function setTimeInfo(time){
	if( time >= 10 ){ return; }
	if( time == 0 ){ clearInterval(timeHandle); timeHandle = setInterval("setTimeInfo(loadTime)",60000); loadTime = 0;  $("#time-info").html("just now");}
	else{ $("#time-info").html(time+" min ago"); }
	if( time >= 8 ){ $("#time-info").attr({"class":"lead text-warning"});}
	else if( time >= 5 ){ $("#time-info").attr({"class":"lead text-success"});}
	else { $("#time-info").attr({"class":"lead text-info"});}
	loadTime++;
}

function history(mode,date){
	panel = 6;
	ready = 1;
	$("#control").addClass("disabled");
	if( date == undefined ) date = hist_date;
	if( mode == undefined ) mode = hist_mode;
	if( hist_data == null || hist_date != date ){
		$.getJSON(url+"/infos_hist.php?date="+date,buildChart);
	}else{
		buildChart(hist_data);
	}
	hist_date = date;
	hist_mode = mode;

	function buildChart(data){
		$("#chart").fadeIn(0);
		$("#chart-title").fadeIn(0);
		$("#chart-title").html("<h3>"+date.substring(0,4)+"/"+date.substring(4,6)+"/"+date.substring(6,8)+"\'s history</h3>");
		if( !data ){
			$("#chart").html("<h3>NO DATA</h3>");
			$("#chart-control").fadeIn(500);
			ready=0;
			return;
		}
		hist_data = data;
		var title = null;
		switch(mode){
		case "lavg1":
			title = "1 min LOAD AVERAGE";
			break;
		case "lavg5":
			title = "5 min LOAD AVERAGE";
			break;
		case "lavg15":
			title = "15 min LOAD AVERAGE";
			break;
		case "proc_r":
			title = "PROCESS RUNNING NUM";
			break;
		case "proc_b":
			title = "PROCESS BLOCKED NUM";
			break;
		default:
			title = "UNKNOWN REQUEST";
			break;
		}
		var category = new Array();
		var linedata = new Array();
		var ini = true;
		for( time in data ){
			category.push(time.substring(0,2)+":"+time.substring(2,4));
			for( serv in data[time] ){
				if( ini ){
					linedata[serv] = new Array();
				}
				linedata[serv].push(data[time][serv][mode]);	
			}
			ini = false;
		}
		var argdata = new Array();
		for( name in linedata ){
			var linecon = new Array();
			linecon["name"] = name;
			linecon["data"] = NumberInArray(linedata[name]);
			argdata.push(linecon);
		}
	
		$(function () {
                    var chart;
                        chart = new Highcharts.Chart({
                            chart: {
                                renderTo: \'chart\',
                                type: \'line\',
                                marginRight: 130,
                                marginBottom: 25
                            },
                            title: {
                                text: title,
                                x: -20
                            },
                            subtitle: {
                                text: \'history chart ver.alpha\',
                                x: -20
                            },
                            xAxis: {
                                categories: category
                            },
                            yAxis: {
                                title: {
                                    text: \'measurement log values\'
                                },
                                plotLines: [{
                                    value: 0,
                                    width: 1,
                                    color: \'#808080\'
                                }]
                            },
                            tooltip: {
                                formatter: function() {
                                        return \'<b>\'+ this.series.name +\'</b><br/>\'+
                                        \'time \'+this.x +\'<br/>value \'+ this.y;
                                }
                            },
                            legend: {
                                layout: \'vertical\',
                                align: \'right\',
                                verticalAlign: \'top\',
                                x: -10,
                                y: 100,
                                borderWidth: 0
                            },
                            series: argdata
                        });
                });
	}
	$("#chart-control").fadeIn(0);
        ready = 0;	
}

function NumberInArray(inArray){
	var retArray = new Array();
	for( var i=0; i<inArray.length; i++){
		retArray.push(Number(inArray[i]));
	}
	return retArray;
}

function getDate(fix){
	var d = null;
	if( fix == undefined ) d = new Date();
	if( fix == 0 ) d = new Date(hist_date.substring(0,4),Number(hist_date.substring(4,6))-1,Number(hist_date.substring(6,8)));
	if( fix == 1 ) d = new Date(hist_date.substring(0,4),Number(hist_date.substring(4,6))-1,Number(hist_date.substring(6,8))+1);
	if( fix == -1 )d = new Date(hist_date.substring(0,4),Number(hist_date.substring(4,6))-1,Number(hist_date.substring(6,8))-1);
        var month  = d.getMonth() + 1;
        var day    = d.getDate();
        if (month < 10) {month = "0" + month;}
        if (day < 10) {day = "0" + day;}
        return d.getFullYear()+""+month+""+day;
}

function setObserver(){
	var obstmp = new Array();
	$.getJSON(url+"/observers.php", function(data){
		$("#observers").empty();
		var guest = null;
		for( var obs in data ){
			if( obs == "guest" ){
				obstmp.push(obs+" ("+data[obs]+")");
				guest = obs+" ("+data[obs]+")";
			}else{
				obstmp.push(obs);
				$("#observers").append("<li>"+obs+"</li>");
			}
		}
		if( guest != null ){
			$("#observers").append("<li>"+guest+"</li>");
		}
		observers = obstmp;	
	});	
}

function ar(pid,usr,cmd,server,mail){
	var alertUrl = url+"/alert.php";
	var params = "comm="+cmd+"&user="+usr+"&server="+server+"&pid="+pid+"&mailto="+mail+"&del=true";
	console.log(alertUrl+"?"+params);
	$.getJSON(alertUrl+"?"+params,function(status){
		if( status.delete == "success" ){
			$("#alert-"+pid).fadeOut(500);
			for( var i=0; i<alerts.length; i++ ){
				if( alerts[i]["PID"] == pid && alerts[i]["SERVER"] == server ){
					alerts.splice(i,1);
				}
			}
		}else{
			$("#alert-body").html("<strong>ERROR!</strong> Cannot delete your alert. Please contact administrator.");
			$(".alert").fadeIn(300);
		}
	});


}

function am(pid,usr,cmd){
	if( userName == null || mailAddr == null ){
		$("#alert-body").html("<strong>Sorry!</strong> We cannot identify you, so you cannot use alert center.");
		$(".alert").fadeIn(300);
		return;
	}
	$("#save-alert").fadeIn(0);
	$("#modal-error").empty();
	$("#modal-success").empty();
	$("#server").html(serverName);
	$("#pid").html(pid);
	$("#comm").html(cmd);
	$("#commuser").html(usr);
	$("#username").html(userName);
	$("#mailto").val(mailAddr);
	$("#myModal").modal("show");
}

function saveAlert(){
	var alertUrl = url+"/alert.php";
	var params = "comm="+$(\'#comm\').html()+"&user="+$(\'#commuser\').html()+"&server="+$(\'#server\').html()+"&pid="+$(\'#pid\').html()+"&mailto="+$(\'#mailto\').val();
	$.getJSON(alertUrl+"?"+params,function(status){
		if( status.set == "success" ){
			$("#modal-success").html("OK! Mail Alert is registered.");
			$("#save-alert").fadeOut(300);
		}else{
			$("#save-alert").fadeOut(0);
			$("#modal-error").html("ERROR!! Perhaps, this request may have already been registerd.");
		}
	});
}

function dismissAlert(){
	$(".alert").fadeOut(300);
}

function sortAlert(row){
	if( alerts == null ){
		return;
	}
	$("#about-indo").empty();
	alerts.sort(function(a, b) {
		return ( a[row] < b[row]  ? sortorder : sortorder*(-1));
	});
	sortorder *= -1;
	var html = "<h3>Mail Alert Center</h3><p class=\"lead\">Your alert list.</p>";
	var alertTable = "<table id=\"alert-info\" class=\"table table-striped\"><thead><tr><th><a href=\"javascript:sortAlert(\'PID\');\">PID</a></th><th><a href=\"javascript:sortAlert(\'SERVER\');\">SERVER</a></th><th><a href=\"javascript:sortAlert(\'COMMAND\');\">COMMAND</a></th><th><a href=\"javascript:sortAlert(\'COMMUSER\');\">COMMAND USER</a></th><th><a href=\"javascript:sortAlert(\'MAILTO\');\">MAIL TO</a></th><th><a href=\"#\">delete</a></th></tr></thead><tbody>";
	if( alerts.length != 0 ){
		for( var i=0; i<alerts.length; i++ ){
			var server = alerts[i].SERVER;
			var pid = alerts[i].PID;
			var command = alerts[i].COMMAND;
			var commuser = alerts[i].COMMUSER;
			var mailto = alerts[i].MAILTO;
        		alertTable += "<tr id=\"alert-"+pid+"\"><td>"+pid+"</td><td>"+server+"</td><td>"+command+"</td><td>"+commuser+"</td><td>"+mailto+"</td><td><a href=\"javascript:ar(\'"+pid+"\',\'"+commuser+"\',\'"+command+"\',\'"+server+"\',\'"+mailto+"\');\">&times;</a></td></tr>";
		}
	}else{
		alertTable = "<p class=\"text-info\">Your alert list is empty.";
	}
	$("#about-info").html(html+alertTable);
}

function identifyUser(){
	var idUrl = url+"/alert.php?identify=true";
	$.getJSON(idUrl,function(json){
		if( json.identify = "true" ){
			userName = json.user_name;
			mailAddr = json.mail_addr;
		}
	});
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

$("#history").click(function(){
	refresh();
	history("lavg1",getDate(0));
});

$("#prevday").click(function(){
	history(hist_mode,getDate(-1));
});

$("#nextday").click(function(){
	history(hist_mode,getDate(1));
});

$("#1lavg").click(function(){
	history("lavg1",hist_date);
});

$("#5lavg").click(function(){
	history("lavg5",hist_date);
});

$("#15lavg").click(function(){
	history("lavg15",hist_date);
});

$("#runproc").click(function(){
	history("proc_r",hist_date);
});

$("#blkproc").click(function(){
	history("proc_b",hist_date);
});

$("#today").click(function(){
	history(hist_mode,getDate());
});

$(".opener").click(function() {
        $("#side-menu").animate({width: "toggle"}, 300);
});

$("#side-menu").click(function(){
        $("#side-menu").animate({width: "toggle"}, 300);
});


$("#datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yymmdd"
});

$("#dp").click(function(){
        $("#datepicker").datepicker("show");
});

$("#datepicker").change(function(){
        history(hist_mode,$("#datepicker").val())
});

$("#myModal").modal({
 	keyboard: true
});

$("#save-alert").click(function(){
	saveAlert();
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
	var html = "<h3>about this application</h3><p class=\"lead\">Cluster Server Monitor can only be accessed from the network in kobe-u.<br/>Please feedback and request about this application...</p>";
	$("#about-info").html(html);
});

$("#alert-center").click(function(){
	panel = 7;
	if( userName == null || mailAddr == null ){
		$("#alert-body").html("<strong>Sorry!</strong> We cannot identify you, so you cannot use alert center.");
		$(".alert").fadeIn(300);
		return;
	}
	refresh();
	$("#control").addClass("disabled");
	$("#about-info").fadeIn(500);
	alerts = [];
        $(".bar").fadeIn(0);
	var html = "<h3>Mail Alert Center</h3><p class=\"lead\">Your alert list.</p>";
	$.getJSON(url+"/alert.php",function(data){
		var alertTable = "<table id=\"alert-info\" class=\"table table-striped\"><thead><tr><th><a href=\"javascript:sortAlert(\'PID\');\">PID</a></th><th><a href=\"javascript:sortAlert(\'SERVER\');\">SERVER</a></th><th><a href=\"javascript:sortAlert(\'COMMAND\');\">COMMAND</a></th><th><a href=\"javascript:sortAlert(\'COMMUSER\');\">COMMAND USER</a></th><th><a href=\"javascript:sortAlert(\'MAILTO\');\">MAIL TO</a></th><th><a href=\"#\">delete</a></th></tr></thead><tbody>";
		if( data.count != 0 ){
			for( var key in data.alert ){
				var keyContents = key.split(":");
				var valContents = (data.alert[key]).split(":");
				var server = keyContents[1];
				var pid = keyContents[2];
				var command = valContents[0];
				var commuser = valContents[1];
				var mailto = valContents[2];
				alerts[alerts.length] = { PID:pid,SERVER:server,COMMAND:command,COMMUSER:commuser,MAILTO:mailto };
                		alertTable += "<tr id=\'alert-"+pid+"\'><td>"+pid+"</td><td>"+server+"</td><td>"+command+"</td><td>"+commuser+"</td><td>"+mailto+"</td><td><a href=\"javascript:ar(\'"+pid+"\',\'"+commuser+"\',\'"+command+"\',\'"+server+"\',\'"+mailto+"\');\">&times;</a></td></tr>";
        		}
		}else{
			alertTable = "<p class=\"text-info\">Your alert list is empty.";
		}
        	$(".bar").fadeOut(0);
		$("#about-info").html(html+alertTable);
	});
});

$(function(){
     $(\'#slider\').slider({
     showControls : true, 
     autoplay     : false,
     fade         : 500, 
     direction    : \'left\'
     });
});


$("#myModal").modal("hide");

timeHandle = setInterval("setTimeInfo(loadTime)",60000);

setInterval("intervalLoad()",600000);

setInterval("setObserver()",10000);

setObserver();

identifyUser();
');

?>
