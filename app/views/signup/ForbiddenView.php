<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title><?php print($this->get('title')); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=0.1">
        <meta name="description" content="CS24 SERVER MONITORING SYSTEM SIGNUP PAGE">
        <meta name="author" content="Kenji KUMABUCHI">
        <link rel="shortcut icon" href="<?php print($this->get('url')); ?>/public/images/favicon.ico">
        <link rel="stylesheet" type="text/css" href="<?php print($this->get('url')); ?>/public/stylesheets/bootstrap.css" >
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="<?php print($this->get('url')); ?>/public/stylesheets/html5.js"></script>
        <![endif]-->
    </head>

    <body>
        <div id="signup-modal" class="modal fade">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 id="modal-title">FORBIDDEN!!</h3>
            </div>
            <div class="modal-body" id="modal-contents">
                <p class="lead">This setting page isn't available on external network.</p>
                <blockquote>
                    <p>Please access from cs24 network.</br></p>
                    <p>研究室内のネットワークから再度アクセスしてください。<br/></p>
                </blockquote>
            </div>
            <div class="modal-footer">
                <a href="<?php print($this->get('url')); ?>"  class="btn">BACK</a>
            </div>
        </div>

        <script src="<?php print($this->get('url')); ?>/public/javascripts/jquery.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/bootstrap.js"></script>
        <script>
            $("#signup-modal").modal('show');
            $("#signup-modal").on('hidden', function(){
                $("#signup-modal").modal('show');
            });
        </script>
    </body>
</html>


