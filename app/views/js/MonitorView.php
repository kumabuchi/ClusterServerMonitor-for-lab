<?php
header("Content-type: application/x-javascript");
$url = $this->get('url');
$servs = $this->get('servs');
print('
/*
 * Cluster Server Monitor ver.beta
 * --server monitoring system--
 *
 * written by Kenji KUMABUCHI
 * k.kumabuchi@gmail.com
 *
 */



/*
 *************************
 *** global variables  ***     
 *************************
 */

/* rendering panel number */
var panel = 0;
/* processing flag */
var ready = 0;
/* url for ajax */
var url = "'.$url.'";
/* sort order for top asc(1) or desc(-1) */
var sortorder = 1;
/* sort order for iostat */
var sortorderio = 1;
/* iostat data array */
var iostatData = null;
/* infos data array */
var infosData = null;
/* top data array */
var topinfo = null;
/* server name shown in top */
var serverName = null;
/* elapsed time from last load */
var loadTime = 0;
/* interval function handle for loadTime */
var timeHandle = null;
/* showing date for history */
var hist_date = getDate();
/* history data array */
var hist_data = null;
/* showing mode for history */
var hist_mode = "lavg1";
/* observers list array */
var observers = null;
/* users alert list array */
var alerts = null;
/* username for identification */
var userName = null;
/* mailaddr for identification */
var mailAddr = null;
/* df data array */
var dfData = null;
/* showing server in top */
var top_server = 0;




/*
 ****************************
 *** rendering functions  ***     
 ****************************
 */



/*
 * initialize and rendering infos page
 */
function init(useCache){
    if( ready == 1 )
        return;
    useCache = useCache == true ? "/refresh" : "";    
    panel = 0;
    ready = 1;
    $("#cpu").fadeIn(0);
    $("#mem").fadeIn(0);
    $("#tables").fadeIn(0);
    $(".bar").fadeIn(0);
    $("#control").removeClass("disabled");


    /*
     * load infos ( CPU, MEMORY, LOADAVERAGE, PROCESS )
     */
    if( infosData == null ){
    	infosData = new Array();
    ');
    foreach( $servs as $name ){
    	print('
    	$.get(url+"/status/show/'.$name.'"+useCache, infosCallback);
    	');
    }
    print('
    }else{
    	infosRendering(infosData);
    }
    
    // callback function for infos
    function infosCallback(json){
    	infosData[json.server] = json.data;
    	if( countArray(infosData) == '.count($servs).' ){
    		infosRendering();
    	}
    }	

    // HTML rendering function for infos( CPU, MEMORY, LOADAVERAGE, PROCESS )
    function infosRendering(){
    
    	data = infosData;
    
     	var loadAvgHtml = "<table class=\"table table-striped\"><thead><tr><th></th><th>1m</th><th>5m</th><th>15m</th><tr></thead><tbody>";
    ');

    foreach( $servs as $name ){
    	print('
    	if( !!data["'.$name.'"] )
    		loadAvgHtml += "<tr><td>'.$name.'</td><td>"+data["'.$name.'"].lavg1+"</td><td>"+data["'.$name.'"].lavg5+"</td><td>"+data["'.$name.'"].lavg15+"</td></tr>";
    	');
    }
    print('
    	loadAvgHtml += "</tbody></table>";
    	var procsHtml   = "<table class=\"table table-striped\"><thead><tr><th></th><th>running</th><th>blocked</th><tr></thead><tbody>";
     ');
    foreach( $servs as $name ){
    	print('
    	if( !!data["'.$name.'"] )
    		procsHtml += "<tr><td>'.$name.'</td><td>"+data["'.$name.'"].proc_r+"</td><td>"+data["'.$name.'"].proc_b+"</td></tr>";
    	');
    }	
	print('
        procsHtml += "</tbody></table>";
        $("#lavg").append(loadAvgHtml);
        $("#procs").append(procsHtml);
	');
	
	foreach( $servs as $name ){
		print('
        $("#'.$name.'-mem").append( buildMemHtml(data["'.$name.'"]) );
		');
	}
		
	foreach( $servs as $name ){
		print('
        $("#'.$name.'-cpu").append( buildCpuHtml(data["'.$name.'"]) );
		');
	}
	
	print('
        // display on ( loading off )
        $(".bar").fadeOut(0);
        $("#slider").fadeIn(0);
        $(".tooltips").tooltip();
        ready = 0;
        
        // HTML rendering function for CPU
        function buildCpuHtml(clusterArr){
            if( !clusterArr )
                return;
            var html = "<img src=\"http://chart.apis.google.com/chart?cht=p3&chd=t:";
            html += clusterArr["cpu_us"]/100+",";
            html += clusterArr["cpu_sy"]/100+",";
            html += clusterArr["cpu_id"]/100+",";
            html += clusterArr["cpu_wt"]/100+"";
            html += "&chs=250x110&chl=user|system|idle|iowait&chco=FF0000|FFA500|00FF00|0000FF\">";
            html  = "<a class=\"tooltips\" href=\"#\" rel=\"tooltip\" html=\"true\"  data-placement=\"top\" title=\"running "+clusterArr["days"]+" days, "+clusterArr["users"]+" users login\">"+html+"</a>";
            return html;
        }
        
        // HTML rendering function for MEMORY
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
            html  = "<a class=\"tooltips\" href=\"#\" rel=\"tooltip\" html=\"true\"  data-placement=\"top\" title=\"swapin "+clusterArr["swap_i"]+" kbyte/s<br/>swapout "+clusterArr["swap_o"]+" kbyte/s\">"+html+"</a>";
            return html;
        }
    }
	
    /*
     * load Input/Output status
     */
    if( iostatData == null ){
    	iostatData = {};
    	');
    foreach( $servs as $name ){
    	print('
    	$.get(url+"/io/show/'.$name.'"+useCache, iostatCallback);
    	');
    }
    	print('
    }else{
    	iostatRendering(iostatData);
    }
    
    // callback function for I/O
    function iostatCallback(json){
    	iostatData[json.server] = json.data;
        if( countArray(iostatData) == '.count($servs).' ){
            iostatRendering();
    	}
    }
    
    // iostat rendering function
    function iostatRendering(){
    
        var data = iostatData;
    
    ');
    
    foreach( $servs as $name ){
    	print('
        if( data["'.$name.'"] ){ 
            $("#'.$name.'-io").html("<h4>'.$name.'</h4>"+ buildIOstatHtml(\''.$name.'\',data["'.$name.'"] ? data["'.$name.'"] :null) ); 
        }else{ 
            $("#'.$name.'-io").remove();
        }
    	');
    }
    
    print('
    	// HTML rendering function for I/O
    	function buildIOstatHtml(server, cluster ){
            iostatData[server] = cluster;
            cluster.sort(function(a,b){
                return a.device > b.device ? 1 : -1;
            });
            //var html = "<table class=\"table table-striped\"><thead><tr><th><a href=\"javascript:sortIO(\'"+server+"\',\'device\');\">Device</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'tps\');\">tps</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'blkr_s\');\">Blk_read/s</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'blkw_s\');\">Blk_write/s</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'blkr\');\">Blk_read</a></th><th><a href=\"javascript:sortIO(\'"+server+"\',\'blkw\');\">Blk_write</a></th><tr></thead><tbody>";
            var html = "<table class=\"table table-striped\"><thead><tr><th>Device</th><th>tps</th><th>Blkread/s</th><th>Blkwrite/s</th><th>Blkread</th><th>Blkwrite</th><tr></thead><tbody>";
            for( var i=0; i<cluster.length; i++)
            	html +=	"<tr><td>"+cluster[i].device+"</td><td>"+cluster[i].tps+"</td><td>"+cluster[i].blkr_s+"</td><td>"+cluster[i].blkw_s+"</td><td>"+cluster[i].blkr+"</td><td>"+cluster[i].blkw+"</td></tr>";
            html += "</tbody></table>";
            return html;
            }
        }
    
        /*
         * load DISK SPACE
         */
        if( dfData == null ){
            dfData = new Array();
            ');
            foreach( $servs as $name ){
        	print('
            $.get(url+"/df/show/'.$name.'", dfCallback);
            ');
            }
            print('
        }else{
            dfRendering();
        }
        
        function dfCallback(json){
            dfData[json.server] = json.data;
            if( countArray(dfData) == '.count($servs).' ){
                dfRendering();
            }  
        }
        
        // callback function for DISK SPACE
        function dfRendering(){
        ');
        
        foreach( $servs as $name ){
            print('
        if( !!dfData["'.$name.'"] )
            $("#'.$name.'-df").html("<h4>'.$name.'</h4>"+ buildDfHtml(\''.$name.'\', dfData["'.$name.'"]) ); 
        else
            $("#'.$name.'-df").html("");
            ');
        }
        
    print('
    	// HTML rendering function for DISK SPACE
        function buildDfHtml(server, cluster ){
            var html = "<table class=\"table table-striped\"><thead><tr><th>Filesystem</th><th>Size</th><th>Used</th><th>Avail</th><th>Use%</th><th>MoutedOn</th><tr></thead><tbody>";
            for( var i=0; i<cluster.length; i++){
                var sp = cluster[i].filesystem.split("/");
                var filesystem = sp[sp.length-1] == "" ? sp[sp.length-2] : sp[sp.length-1];
                html +=	"<tr><td title=\""+cluster[i].filesystem+"\">"+filesystem+"</td><td>"+cluster[i].size+"</td><td>"+cluster[i].used+"</td><td>"+cluster[i].avail+"</td><td>"+cluster[i]["use%"]+"</td><td title=\""+cluster[i].mount+"\">"+cluster[i].mount+"</td></tr>";
            }
            html +=	"</tbody></table>";
            return html;
        }
    
    }
    
}



/*
 * sort function for iostat
 * ( not using )
 */
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
    $(".bar").fadeOut(0);
}



/*
 * sort function for DISK SPACE
 * ( not using )
 */
function sortDf( server,row ){
    if( dfData == null ){
        return ;
    }
    $(".bar").fadeIn(0);
    DfData[server].sort(function(a, b) {
        return ( a[row] > b[row] ? sortorder : sortorder*(-1));
    });
    sortorder *= -1;
    var cluster = dfData[server];
    var html = "<table class=\"table table-striped\"><thead><tr><th>Filesystem</th><th>Size</th><th>Used</th><th>Avail</th><th>Use%</th><th>Mouted on</th><tr></thead><tbody>";
    for( var i=0; i<cluster.length; i++){
        var sp = cluster[i].filesystem.split("/");
        var filesystem = sp[sp.length-1];
        html +=	"<tr><td title=\""+cluster[i].filesystem+"\">"+filesystem+"</td><td>"+cluster[i].size+"</td><td>"+cluster[i].used+"</td><td>"+cluster[i].avail+"</td><td>"+cluster[i]["use%"]+"</td><td title=\""+cluster[i].mount+"\">"+cluster[i].mount+"</td></tr>";
    }
    html += "</tbody></table>";
    $("#"+server+"-df").html("<h4>"+server+"</h4>"+html);
    $(".bar").fadeOut(0);
}



/*
 * refresh all pages ( all contents fadeOut )
 */
function refresh(){
    sortorder = 1;
    sortorderio = 1;
    topinfo = null;
    $(".span12").fadeOut(0);
    $("#each").empty();
    $("#about-info").empty();
    $("img").remove();
    $("table").remove();
    $("#chart").fadeOut(0);
    $("#chart-control").fadeOut(0);
    $("#chart-title").fadeOut(0);
    $("#df").fadeOut(0);
}



/*
 * load and rendering top page
 */
function loadTop( cluster ){
    ready = 1;
    panel = cluster;
    $("#each").fadeIn(0);
    $(".bar").fadeIn(0);
    $("#control").addClass("disabled");
    switch(cluster){
    ');
    foreach( $servs as $name ){
        print('
        case "'.$name.'":
            $("#each").append("<h3>'.$name.'\'s top</h3>");
            serverName = "'.$name.'";
            break;
    	');
    }
    print('
    }
    $.getJSON(url+"/top/show/"+cluster, function(datas){
        topinfo = datas.data;
        
        // HTML renderging for TOP INFO
        var html = "<table id=\"topinfo\" class=\"table table-striped\"><thead><tr><th><a href=\"javascript:sortTop(\'PID\');\">PID</a></th><th><a href=\"javascript:sortTop(\'USER\');\">USER</a></th><th><a href=\"javascript:sortTop(\'PR\');\">PR</a></th><th><a href=\"javascript:sortTop(\'NI\');\">NI</a></th><th><a href=\"javascript:sortTop(\'VIRT\');\">VIRT</a></th><th><a href=\"javascript:sortTop(\'RES\');\">RES</a></th><th><a href=\"javascript:sortTop(\'SHR\');\">SHR</a></th><th><a href=\"javascript:sortTop(\'S\');\">S</a></th><th><a href=\"javascript:sortTop(\'%CPU\');\">%CPU</a></th><th><a href=\"javascript:sortTop(\'%MEM\');\">%MEM</a></th><th><a href=\"javascript:sortTop(\'TIME\');\">TIME+</a></th><th><a href=\"javascript:sortTop(\'COMMAND\');\">COMMAND</a.</th><tr></thead><tbody>";
        if( !!topinfo ){
            for( var i=0; i<topinfo.length; i++){
                var data = topinfo[i];
                html += "<tr><td><a href=\"javascript:am(\'"+data.PID+"\',\'"+data.USER+"\',\'"+data.COMMAND+"\');\">"+data.PID+"</a></td><td>"+data.USER+"</td><td>"+data.PR+"</td><td>"+data.NI+"</td><td>"+data.VIRT+"</td><td>"+data.RES+"</td><td>"+data.SHR+"</td><td>"+data.S+"</td><td>"+data["%CPU"]+"</td><td>"+data["%MEM"]+"</td><td>"+data.TIME+"</td><td>"+data.COMMAND+"</td></tr>";
            }
        }
        html += "</tbody></table>";
        
        // display on
        $("#each").append(html);	
        $(".bar").fadeOut(0);
        ready = 0;
    });
}



/*
 * sort function for top
 */
function sortTop( row ){
    if( topinfo == null ){
        return;
    }
    // display off
    $("#topinfo").remove();
    $(".bar").fadeIn(0);
    // sort execution
    topinfo.sort(function(a, b) {
        if( !isNaN(a[row]) && !isNaN(b[row]) ){
            return  ( Number(a[row]) > Number(b[row]) ? sortorder : sortorder*(-1));
        }
        return ( a[row] > b[row] ? sortorder : sortorder*(-1));
    });
    sortorder *= -1;
    var datas = topinfo;
    // HTML rendering for TOP INFO
    var html = "<table id=\"topinfo\" class=\"table table-striped\"><thead><tr><th><a href=\"javascript:sortTop(\'PID\');\">PID</a></th><th><a href=\"javascript:sortTop(\'USER\');\">USER</a></th><th><a href=\"javascript:sortTop(\'PR\');\">PR</a></th><th><a href=\"javascript:sortTop(\'NI\');\">NI</a></th><th><a href=\"javascript:sortTop(\'VIRT\');\">VIRT</a></th><th><a href=\"javascript:sortTop(\'RES\');\">RES</a></th><th><a href=\"javascript:sortTop(\'SHR\');\">SHR</a></th><th><a href=\"javascript:sortTop(\'S\');\">S</a></th><th><a href=\"javascript:sortTop(\'%CPU\');\">%CPU</a></th><th><a href=\"javascript:sortTop(\'%MEM\');\">%MEM</a></th><th><a href=\"javascript:sortTop(\'TIME\');\">TIME+</a></th><th><a href=\"javascript:sortTop(\'COMMAND\');\">COMMAND</a></th><tr></thead><tbody>";
    if( !!datas ){
        for( var i=0; i<datas.length; i++){
            var data = datas[i];
            html += "<tr><td><a href=\"javascript:am(\'"+data.PID+"\',\'"+data.USER+"\',\'"+data.COMMAND+"\');\">"+data.PID+"</a></td><td>"+data.USER+"</td><td>"+data.PR+"</td><td>"+data.NI+"</td><td>"+data.VIRT+"</td><td>"+data.RES+"</td><td>"+data.SHR+"</td><td>"+data.S+"</td><td>"+data["%CPU"]+"</td><td>"+data["%MEM"]+"</td><td>"+data.TIME+"</td><td>"+data.COMMAND+"</td></tr>";
        }
    }
    html += "</tbody></table>";
    // display on
    $("#each").append(html);
    $(".bar").fadeOut(0);
}



/*
 * reload function ( called when refresh button is pushed )
 */
function reload(){
    if( ready == 1 )
        return;
    refresh();
    switch(panel){
    case 0:
        infosData = null;
        iostatData = null;
        dfData = null;
        init(false); // use cache
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
    case 8:
        $("#setting").click();
        break;
    default :
        loadTop(panel);
        break;
    }
}



/*
 * interval load for infos page
 */
function intervalLoad(){
    infosData = null;
    iostatData = null;
    refresh();
    init();
    setTimeInfo(0);
}



/*
 * rendering time information
 */
function setTimeInfo(time){
    if( time >= 10 ){ return; }
    if( time == 0 ){ clearInterval(timeHandle); timeHandle = setInterval("setTimeInfo(loadTime)",60000); loadTime = 0;  $("#time-info").html("just now");}
    else{ $("#time-info").html(time+" min ago"); }
    if( time >= 8 ){ $("#time-info").attr({"class":"lead text-warning"});}
    else if( time >= 5 ){ $("#time-info").attr({"class":"lead text-success"});}
    else { $("#time-info").attr({"class":"lead text-info"});}
    loadTime++;
}



/*
 * load and rendering history page
 */
function history(mode,date){
    panel = 6;
    ready = 1;
    $("#control").addClass("disabled");
    if( date == undefined ) date = hist_date;
    if( mode == undefined ) mode = hist_mode;
    if( hist_data == null || hist_date != date ){
    	$.getJSON(url+"/history/show/"+date,buildChart);
    }else{
    	buildChart(hist_data);
    }
    hist_date = date;
    hist_mode = mode;
    
    // chart building function 
    function buildChart(json){
        data = json.data;
    	$("#chart").fadeIn(0);
    	$("#chart-title").fadeIn(0);
        $("#chart-title").html("<h3>"+date.substring(0,4)+"/"+date.substring(4,6)+"/"+date.substring(6,8)+"\'s history</h3>");
    	if( json.status != "OK" ){
    	    $("#chart").html("<h3>NO DATA</h3>");
    	    $("#chart-control").fadeIn(500);
    	    ready=0;
    	    return;
    	}
    	hist_data = json;
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
        ');
        foreach( $servs as $name ){
            print('
        linedata["'.$name.'"] = new Array();
            ');
        }
        print('
        for( var each in data ){
            var d = data[each];
            if( !!linedata[d.server] ){
                var dispdate = d.datetime.substring(11,16);
                if( -1 == $.inArray(dispdate, category) ){
                    category.push(dispdate);
                }
                linedata[d.server].push(Number(d[mode]));	
            }
        }
        var argdata = new Array();
        for( var s in linedata ){
            var tmp = new Array();
            tmp["name"] = s;
            tmp["data"] = linedata[s];
            for( var l=linedata[s].length; l<category.length; l++){
                tmp["data"].unshift(0);
            }
            argdata.push(tmp);
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



/*
 * convert string to number in array contents
 */
function NumberInArray(inArray){
    var retArray = new Array();
    for( var i=0; i<inArray.length; i++){
        retArray.push(Number(inArray[i]));
    }
    return retArray;
}



/*
 * get date string
 * @param fix : previous day(-1) or next day(1) 
 *              no change (0) or today()(undefined)
 */
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



/*
 * load and rendering observers
 */
function setObserver(){
    var obstmp = new Array();
    $.getJSON(url+"/observer/show", function(json){
        $("#observers").empty();
        if( json.status != "OK" ){
            $("#observers").append("<li>ERROR</li>");
            return;
        }
    	for( var obs in json.data ){
    	    obstmp.push(json.data[obs].user);
    	    $("#observers").append("<li>"+json.data[obs].user+"</li>");
    	}
    	observers = obstmp;	
    });	
}



/*
 * remove alert from list
 */
function ar(server,pid,mail,rand){
    var params = server+"/"+pid+"/"+mail+"/"+rand;
    $.getJSON(url+"/alert/del/"+params,function(json){
        if( json.status == "OK" ){
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



/*
 * rendering alert modal
 */
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



/*
 * save alert to list
 */
function saveAlert(){
    var inputmail = $(\'#mailto\').val();
    if (!inputmail.match(/^[A-Za-z0-9]+[\w-]+@[\w\.-]+\.\w{2,}$/)){
        $("#modal-error").html("ERROR!! Invalid email address.");
        return false;
    }
    var alertUrl = url+"/alert/add/";
    var params = $(\'#server\').html()+"/"+$(\'#pid\').html()+"/"+$(\'#mailto\').val()+"/"+$(\'#comm\').html()+"/"+$(\'#commuser\').html();
    $.getJSON(alertUrl+params,function(json){
        if( json.status == "OK" ){
            $("#modal-success").html("OK! Mail Alert is registered.");
            $("#save-alert").fadeOut(300);
        }else{
            $("#save-alert").fadeOut(0);
            $("#modal-error").html("ERROR!! Perhaps, this request may have already been registerd.");
        }
    });
}



/*
 * dismiss site notification alert
 */
function dismissAlert(){
    $(".alert").fadeOut(300);
}



/*
 * sort function for alert list
 */
function sortAlert(row){
    if( alerts == null ){
        return;
    }
    $("#about-indo").empty();
    // sort execution
    alerts.sort(function(a, b) {
        if( !isNaN(a[row]) && !isNaN(b[row]) ){
            return  ( Number(a[row]) > Number(b[row]) ? sortorder : sortorder*(-1));
        }
        return ( a[row] < b[row]  ? sortorder : sortorder*(-1));
    });
    sortorder *= -1;
    // HTML rendering for alert list
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
    // display on
    $("#about-info").html(html+alertTable);
}



/*
 * user identification function
 */
function identifyUser(){
    var idUrl = url+"/auth/show";
    $.getJSON(idUrl,function(json){
        if( json.status == "OK" ){
    	    userName = json.data.name;
    	    mailAddr = json.data.mail;
        }
    });
}



/*
 * count object array length
 */
function countArray( array ){
    var num = 0;
    for( i in array ){
        num++;
    }
    return num;
}


/*
 *********************
 ***  button links ***     
 *********************
 */



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

$("#servers").click(function(){
    switch( top_server ){
	');
$cnt = 0;
$defaultserv = null;
foreach( $servs as $name ){
	if( $cnt == 0 ){
		$defaultserv = $name;	
	}
print('
        case '.$cnt.':
            refresh();
            loadTop("'.$name.'");
            break;
');	
$cnt++;
}
print('
        default :
            refresh();
            loadTop("'.$defaultserv.'");
            top_server=0;
            break;		
        }
        top_server++;
});
');

foreach( $servs as $name ){
    print('
$("#'.$name.'").click(function(){
    refresh();
    loadTop("'.$name.'");
});
    ');
}
print('


/*
 * rendering about page
 */
$("#about").click(function(){
    panel = 5;
    refresh();
    $("#control").addClass("disabled");
    $("#about-info").fadeIn(500);
    var html = "<h3>about this application</h3><p class=\"lead\">Cluster Server Monitor can only be accessed from the network in CS24.<br/>Please feedback and request about this application...</p>";
    html+= "<blockquote><h3>Mail Alert Center</h3><p class=\"lead\">We identify you by your IP address.<br/>You can set mail alerts to your alert list.<br/>All alerts are checked whether the program is running or not, periodically.</p></blockquote>";
    html+= "<blockquote><h3>Links for tablets or smart-phones</h3><p class=\"lead\"><a href=\"javascript:refresh();history(\'lavg1\',getDate(0));\">History</a><br/><a href=\"javascript:renderSetting();\">Setting</a><br/><a href=\"'.$url.'/docs/show\">Documents</a><br/><a href=\"'.$url.'\">Other Servers</a><br/><a href=\"'.$url.'/signup/signout\">Signout</a></p></blockquote>";
    html+= "<blockquote><h3>Request or Report bugs</h3><p class=\"lead\">If you have any request about this application, or find bugs, please tell me.<br/>Email : <a href=\"mailto:server.monitor.cs24@gmail.com\">server.monitor.cs24@gmail.com</a></p></blockquote>";
    $("#about-info").html(html);
});


/*
 * rendering setting page
 */
$("#setting").click(renderSetting);
function renderSetting(){
    panel = 8;
    refresh();
    var html = "<h3>User Settings</h3>";
    html+= "<blockquote><h3><a target=\"_blank\" href=\"'.$url.'/signup/edit\">Edit default information</a></h3><p class=\"lead\">You can edit your default information, Username and Email address.</p></blockquote>";
    html+= "<blockquote><h3><a target=\"_blank\" href=\"'.$url.'/signup/setting\">Set password to use from external network</a></h3><p class=\"lead\">To use this application from out of cs24 network, please set your password.</p></blockquote>";
    html+= "<blockquote><h3><a target=\"_blank\" href=\"'.$url.'/signup/alert\">Server Down Mail Alert System( for server-administrator )</a></h3><p class=\"lead\">If the server is down, Server-Monitoring-System send Email to you.</p></blockquote>";
    $("#about-info").html(html);
    $("#about-info").fadeIn(500);
};


/*
 * rendering alert center page
 */
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
    
    // load alert list
    $.getJSON(url+"/alert/show",function(json){
    	// rendering alert center
    	var alertTable = "<table id=\"alert-info\" class=\"table table-striped\"><thead><tr><th><a href=\"javascript:sortAlert(\'PID\');\">PID</a></th><th><a href=\"javascript:sortAlert(\'SERVER\');\">SERVER</a></th><th><a href=\"javascript:sortAlert(\'COMMAND\');\">COMMAND</a></th><th><a href=\"javascript:sortAlert(\'COMMUSER\');\">COMMAND USER</a></th><th><a href=\"javascript:sortAlert(\'MAILTO\');\">MAIL TO</a></th><th><a href=\"#\">delete</a></th></tr></thead><tbody>";
    	if( countArray(json.data) != 0 ){
            for( var key in json.data ){
                var listC = json.data[key];
            	var server = listC.server;
            	var pid = listC.pid;
            	var command = listC.command;
            	var commuser = listC.commuser;
                var mailto = listC.mail;
                var rand = listC.rand;
            	alerts[alerts.length] = { PID:pid,SERVER:server,COMMAND:command,COMMUSER:commuser,MAILTO:mailto,RAND:rand };
               	alertTable += "<tr id=\'alert-"+pid+"\'><td>"+pid+"</td><td>"+server+"</td><td>"+command+"</td><td>"+commuser+"</td><td>"+mailto+"</td><td><a href=\"javascript:ar(\'"+server+"\',\'"+pid+"\',\'"+mailto+"\',\'"+rand+"\');\">&times;</a></td></tr>";
            }
    	}else{
    	    alertTable = "<p class=\"text-info\">Your alert list is empty.";
    	}
    	// display on
        $(".bar").fadeOut(0);
    	$("#about-info").html(html+alertTable);
    });
});



/*
 * initialize slider
 */
$(function(){
    $(\'#slider\').slider({
    showControls : true, 
    autoplay     : false,
    fade         : 500, 
    direction    : \'left\'
    });
});



/*
 *  detect Enterkey down
 */
function detectEnter(){
    if (event.keyCode == 13){
        $("#save-alert").click();
    }
}



/*
 *********************
 *** exec function ***     
 *********************
 */



/*
 * hide modal
 */
$("#myModal").modal("hide");



/*
 * set load time interval
 */
timeHandle = setInterval("setTimeInfo(loadTime)",60000);



/*
 * set infos load interval
 */
setInterval("intervalLoad()",600000);



/*
 * set observer check interval
 */
setInterval("setObserver()",10000);



/*
 * first rendering observers
 */
setObserver();



/*
 * user identification
 */
identifyUser();



/*
 * recalc width when window resized
 */
$(window).resize(function(){
    $("#slider").css("width", $(window).width());
});

');

