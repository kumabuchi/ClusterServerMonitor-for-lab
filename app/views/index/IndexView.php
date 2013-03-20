<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <title><?php print($this->get('title')); ?></title>
        <meta name="description" content="Server Monitoring System for CS24">
        <meta name="viewport" content="width=device-width, initial-scale=0.1">
        <link rel="stylesheet" type="text/css" href="<?php print($this->get('url')); ?>/public/stylesheets/style.css">
        <link rel="shortcut icon" href="<?php print($this->get('url')); ?>/public/images/favicon.ico">
    </head>

    <body>

        <article>

        <section id="examples">
        <div class="section-wrap">
            <h1 style="margin-left:20px;color:black;">CS24 Server Monitoring System</h1>

            <ul>

                <li>
                <a href="<?php print($this->get('url')); ?>/hpc" style="margin-left:20px;">
                    <div class="icon" style="text-align:center;padding:15px;">
                        <p style="color:black;font-size:24px;margin:8px;">niagara</p>
                        <p style="color:black;font-size:24px;margin:8px;">sarajevo</p>
                        <p style="color:black;font-size:24px;margin:8px;">endevour</p>
                        <p style="color:black;font-size:24px;margin:8px;">phoenix</p>
                    </div>
                    <h2>HPC Cluster Server</h2>
                </a>
                </li>

                <li>
                <a href="<?php print($this->get('url')); ?>/etc">
                    <div class="icon" style="text-align:center;padding:15px;">
                        <p style="color:black;font-size:24px;margin:8px;">miranda</p>
                        <p style="color:black;font-size:24px;margin:8px;">apollo</p>
                        <p style="color:black;font-size:24px;margin:8px;">nautilus</p>
                        <p style="color:black;font-size:24px;margin:8px;">victory</p>
                    </div>
                    <h2>Video Team Server</h2>
                </a>
                </li>

                <li>
                <a href="<?php print($this->get('url')); ?>/comp">
                    <div class="icon" style="text-align:center;padding:15px;">
                        <p style="color:black;font-size:24px;margin:8px;">foster</p>
                        <p style="color:black;font-size:24px;margin:8px;">hermes</p>
                        <p style="color:black;font-size:24px;margin:8px;">nova</p>
                        <!--<p style="color:black;font-size:24px;margin:8px;">rubicon</p>-->
                        <p style="color:black;font-size:24px;margin:8px;">jones</p>
                    </div>
                    <h2>Web and Computation</h2>
                </a>
                </li>

                <li>
                <a href="<?php print($this->get('url')); ?>/mac">
                    <div class="icon" style="text-align:center;padding:15px;">
                        <p style="color:black;font-size:24px;margin:8px;">sovereign</p>
                        <p style="color:black;font-size:24px;margin:8px;">handel</p>
                        <p style="color:black;font-size:24px;margin:8px;">raiders</p>
                        <p style="color:black;font-size:24px;margin:8px;">vader</p>
                    </div>
                    <h2>Xserve</h2>
                </a>
                </li>
<!--
                <li>
                <a href="http://www.ai.cs.kobe-u.ac.jp/~kumabuchi/monitor/docs/show">
                    <div class="icon" style="text-align:center;padding:15px;">
                        <p style="color:black;font-size:24px;margin:8px;">How</p>
                        <p style="color:black;font-size:24px;margin:8px;">to</p>
                        <p style="color:black;font-size:24px;margin:8px;">use</p>
                        <p style="color:black;font-size:24px;margin:8px;">?</p>
                    </div>
                    <h2>Documents</h2>
                </a>
                </li>
-->
            </ul>

            <!--<h3 style="text-align:right;margin-top:30px;">Access only cs.scitec.kobe-u network except Demo System.</h3>-->
            <h3 style="text-align:right;margin-top:30px;">Access only cs.scitec.kobe-u network except <a href="http://www.ai.cs.kobe-u.ac.jp/~kumabuchi/demo/monitor">Demo System.</a></h3>
        </div>
        </section>
        </article>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/jquery.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/waypoints.min.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/script.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/bootstrap.min.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/modernizr.custom.js"></script>
    </body>
</html>
