<?php
header("Content-type: text/html");
require_once("config.php");

access_control();

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Server Monitor</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="This program visualizes the command 'sar'. Server response Json format file.">
        <meta name="author" content="Kenji KUMABUCHI">
        <link href="css/bootstrap.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="css/loading-bar.css"/>
	<link href="css/jquery.slider.css" rel="stylesheet">
        <style>
            body {
                padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
            }
        </style>
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>

        <div class="container">

            <div id="top" class="row">

		<div class="span5">
		   <!-- TITLE -->
         	   <h1 id="page-title">
         	       <u><?php print($title); ?></u>
         	   </h1>
		</div>

		<div class="span2" style="margin-top:22px;text-align:right;">
		   <p class="text-info lead" id="time-info"></p>
		</div>

		<div class="span5">
		    <!-- page buttons -->
       		    <div class="btn-toolbar" style="margin-top:20px;text-align:right">
                        <a class="btn btn-primary" href="#"><i class="icon-refresh icon-white"></i> Refresh</a>
 		        <a class="btn btn-danger" href="#" id="control"><i class="icon-arrow-left icon-white"></i>  NextPage</a>
	                <div class="btn-group" style="text-align:left;">
		           <a class="btn btn-success" href="#"><i class="icon-th-large icon-white"></i> Servers</a>
		           <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
		           <ul class="dropdown-menu">
		        	  <li><a href="#" id="all-clusters"><i class="icon-th-large"></i> all servers</a></li>
		        	  <li><a href="#" id="history"><i class="icon-time"></i> history</a></li>
		        	  <li class="divider"></li>
				  <?php
					foreach( $servs as $name => $comm ){
						print('<li><a href="#" id="'.$name.'"><i class="icon-ok"></i> '.$name.'</a></li>');
					}
				  ?>
			  </ul>
		        </div>
 		        <a class="btn btn-warning" href="#" id="about"><i class="icon-hand-right icon-white"></i> about</a>
		    </div>
		</div>

		<div id="slider" class="span12" style="height:700px;">  <!-- SLIDER -->

		<div class="slide"> <!-- slide1 -->

                <div id="cpu" class="span12">
                    <!-- CPU PERCENTAGE -->
                    <h3>CPU PERCENTAGE</h3>
                    <div class="row">
			<?php
				$span = "span". (12/count($servs));
				foreach( $servs as $name => $comm ){
					print('<div id="'.$name.'-cpu" class="'.$span.'"><h4>'.$name.'</h4></div>');
				}
			?>
		    </div>
		</div>

		<div id="mem" class="span12">
                    <!-- MEMORY PERCENTAGE -->
                    <h3>MEMORY PERCENTAGE</h3>
                    <div class="row">
			<?php
				$span = "span". (12/count($servs));
				foreach( $servs as $name => $comm ){
					print('<div id="'.$name.'-mem" class="'.$span.'"><h4>'.$name.'</h4></div>');
				}
			?>
		    </div>
                </div>

                <div id="tables" class="span12">

		    <div class="row">

			<div class="span6">
                    	    <!-- LOAD AVERAGE -->
                    	    <h3>LOAD AVERAGE</h3>
                    	    <div id="lavg"></div>
			</div>

			<div class="span6">
                    	    <!-- PROCESS INFORMATION -->
                   	    <h3>PROCESS INFORMATION</h3>
                    	    <div id="procs"></div>
			</div>

	 	    </div>

                </div>

		</div>

		<div class="slide"> <!-- slide2 -->

                <div id="iostat1" class="span12">
                    <!-- IO STATUS -->
                    <h3>Input/Output STATUS</h3>
                    <div class="row">
			<?php
				$span = count($servs) == 1 ? "span12" : "span6";
				foreach( $servs as $name => $comm ){
					print('<div id="'.$name.'-io" class="'.$span.'"><h4>'.$name.'</h4></div>');
				}
			?>
		    </div>
		</div>
		</div>

		</div> <!-- END SLIDER -->

            </div> <!-- end row -->

	    <div id="each" class="span12">
	    </div>

	    <div id="about-info" class="span12">
	    </div>

	    <div id="chart-title"style="mim-height:50px;"></div>
	    <div id="chart" style="min-height:500px;display:none;"></div>
	    <div class="row" id="chart-control" style="margin:50px 0px;display:none;">
		<div class="span3" style="text-align:right;min-width:220px;">
		<div class="btn-group">
                <a class="btn btn-inverse" id="today" href="#">date <i class="icon-arrow-right icon-white"></i></a>
		<a class="btn" href="#" id="prevday">prevday</a>
 		<a class="btn"  href="#" id="nextday">nextday</a>
		</div>
		</div>
		<div class="span4" style="text-align:center;min-width:300px;">
		<div class="btn-group">
                <a class="btn btn-inverse" href="#">load average <i class="icon-arrow-right icon-white"></i></a>
		<a class="btn btn-warning" href="#" id="1lavg">1min</a>
		<a class="btn btn-warning" href="#" id="5lavg">5min</a>
		<a class="btn btn-warning" href="#" id="15lavg">15min</a>
		</div>
		</div>
		<div class="span3" style="text-align:left;min-width:220px;">
		<div class="btn-group">
                <a class="btn btn-inverse" href="#">process <i class="icon-arrow-right icon-white"></i></a>
		<a class="btn btn-primary" href="#" id="runproc">runninng</a>
		<a class="btn btn-primary" href="#" id="blkproc">blocked</a>
		</div>
		</div>
	    </div>

	    <div class="bar" style="display : none;">
		<span></span>
  	    </div>

        </div> <!-- end container -->
        <script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.slider.min.js"></script>
        <script src="js/cluster-server-monitor.php"></script>
        <script src="http://code.highcharts.com/highcharts.js"></script>
        <script src="http://code.highcharts.com/modules/exporting.js"></script>
    </body>
</html>

