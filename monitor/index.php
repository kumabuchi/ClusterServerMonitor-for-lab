<?php

require_once("config.php");

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Cluster Server Monitor</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="This program visualizes the command 'sar'. Server response Json format file.">
        <meta name="author" content="Kenji KUMABUCHI">
        <link href="css/bootstrap.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="css/loading-bar.css"/>
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

    <body onload="init()">

        <div class="container">

            <div id="top" class="row">

		<div class="span6">
		   <!-- TITLE -->
         	   <h1 id="page-title">
         	       <u>HPC CLUSTER SERVER</u>
         	   </h1>
		</div>

		<div class="span5">
		    <!-- page buttons -->
       		    <div class="btn-toolbar" style="margin-top:20px;text-align:right">
                        <a class="btn btn-primary" href="#"><i class="icon-refresh icon-white"></i> Refresh</a>
	                <div class="btn-group" style="text-align:left;">
		           <a class="btn btn-success" href="#"><i class="icon-th-large icon-white"></i> Clusters</a>
		           <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
		           <ul class="dropdown-menu">
		        	  <li><a href="#" id="all-clusters"><i class="icon-th-large"></i> all clusters</a></li>
		        	  <li class="divider"></li>
		        	  <li><a href="#" id="niagara"><i class="icon-ok"></i> niagara</a></li>
		        	  <li><a href="#" id="sarajevo"><i class="icon-ok"></i> sarajevo</a></li>
		        	  <li><a href="#" id="endevour"><i class="icon-ok"></i> endevour</a></li>
		           	  <li><a href="#" id="phoenix"><i class="icon-ok"></i> phoenix</a></li>
		        </div>
 		        <a class="btn btn-warning" href="#" id="about"><i class="icon-hand-right icon-white"></i> about</a>
		    </div>
		</div>

                <div id="cpu" class="span12">
                    <!-- CPU PERCENTAGE -->
                    <h3>CPU PERCENTAGE</h3>
                    <div class="row">
			<div id="niagara-cpu" class="span3"><h4>niagara</h4></div>
			<div id="sarajevo-cpu" class="span3"><h4>sarajevo</h4></div>
			<div id="endevour-cpu" class="span3"><h4>endevour</h4></div>
			<div id="phoenix-cpu" class="span3"><h4>phoenix</h4></div>
		    </div>
		</div>

		<div id="mem" class="span12">
                    <!-- MEMORY PERCENTAGE -->
                    <h3>MEMORY PERCENTAGE</h3>
                    <div class="row">
			<div id="niagara-mem" class="span3"><h4>niagara</h4></div>
			<div id="sarajevo-mem" class="span3"><h4>sarajevo</h4></div>
			<div id="endevour-mem" class="span3"><h4>endevour</h4></div>
			<div id="phoenix-mem" class="span3"><h4>phoenix</h4></div>
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

            </div> <!-- end row -->

	    <div id="each" class="span12">
	    </div>

	    <div id="about-info" class="span12">
	    </div>

	    <div class="bar" style="display : none;">
		<span></span>
  	    </div>

        </div> <!-- end container -->

        <!-- load javascript -->
        <!--
        <script src="js/bootstrap-transition.js"></script>
        <script src="js/bootstrap-alert.js"></script>
        <script src="js/bootstrap-modal.js"></script>
        <script src="js/bootstrap-dropdown.js"></script>
        <script src="js/bootstrap-scrollspy.js"></script>
        <script src="js/bootstrap-tab.js"></script>
        <script src="js/bootstrap-tooltip.js"></script>
        <script src="js/bootstrap-popover.js"></script>
        <script src="js/bootstrap-button.js"></script>
        <script src="js/bootstrap-collapse.js"></script>
        <script src="js/bootstrap-carousel.js"></script>
        <script src="js/bootstrap-typeahead.js"></script>
        -->
        <script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>"
        <script src="js/cluster-server-monitor.js"></script>
    </body>
</html>


