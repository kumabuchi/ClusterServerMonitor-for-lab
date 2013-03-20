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
                    <h3 id="modal-title">Server Down Alert System Registration</h3>
                </div>
                <div class="modal-body" id="modal-contents">
                    <p class="lead">Registration Form.</p>
                    <form class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="inputEmail">USER </label>
                            <div class="controls">
                            <input type="text" id="user" readonly="readonly" value="<?php print($this->get('user')); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputPassword">EMAIL </label>
                            <div class="controls">
                            <input type="text" id="email" placeholder="<?php print($this->get('email')); ?>" value="<?php print($this->get('email')); ?>">
                            </div>
                        </div>
                    </form>
                    <blockquote>
                        <p>When the server is down, we send above email address.</br></p>
                        <p>いづれかのサーバがダウンした場合、上記のメールアドレスに対して<br/>アラートメールを送信します。登録の削除及び変更の場合は、<br/>EMAIL欄に空文字もしくは新しいアドレスを入力してください。<br/></p>
                    </blockquote>
                    <p id="modal-error" class="text-error"></p>
                </div>
                <div class="modal-footer">
                    <a href="<?php print($this->get('url')); ?>"  class="btn">BACK</a>
                    <a id="signup" class="btn btn-primary">OK</a>
                </div>
            </div>


        <input type="hidden" id="token" value="<?php print($this->get('token')); ?>"></input>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/jquery.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/bootstrap.js"></script>
        <script>
                var isSend = false;
                $("#signup-modal").modal('show');
                $("#email").focus();
                $("#signup-modal").on('hidden', function(){
                    $("#signup-modal").modal('show');
                });
                $("#signup").click(function(){
                    if( isSend == true ){
                        return;
                    }
                    var u = $("#user").val(), p = $("#email").val();
                    if( u == "" || p == "" ){
                        $("#modal-error").html("ERROR : The username or email is empty.");        
                        return;
                    }
                    $("#modal-error").html("<span style='color:green;'>Please wait, request sending ...<span/>");
                    isSend = true;
                    Auth(u,p,$("#token").val()).done(function(data){
                        if( data.status == 'OK' ){
                            location.href = "<?php print($this->get('url')); ?>";
                        }else{
                            isSend = false;
                            console.log(data);
                            $("#modal-error").html("ERROR : Sorry, please wait and retry later...");
                        }
                    });
                });
                var Auth = function(un, ps, tk) {
                    var defer = $.Deferred();
                    $.ajax({
                        url: "<?php print($this->get('url')); ?>/signup/alertajax",
                            type: "POST",
                            data: {
                                user: un,
                                email: ps,
                                token: tk 
                            },
                            dataType: 'json',
                            success: defer.resolve,
                            error: defer.reject
                    });
                    return defer.promise();
                };
                window.document.onkeydown = function(evt){
                    if (evt){
                        var kc = evt.keyCode;
                    }else{
                        var kc = event.keyCode;
                    }
                    if( kc == 13 ) 
                        $("#signup").click();
                }
        </script>
    </body>
</html>


