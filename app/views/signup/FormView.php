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
                    <h3 id="modal-title">Sign up</h3>
                </div>
                <div class="modal-body" id="modal-contents">
                    <p class="lead">Requires user authentication.</p>
                    <form class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="inputEmail">USER </label>
                            <div class="controls">
                                <input type="text" id="user" placeholder="YOUR NAME">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputPassword">PASSWORD </label>
                            <div class="controls">
                                <input type="password" id="password" placeholder="PASSWORD">
                            </div>
                        </div>
                    </form>
                    <blockquote>
                        <p>The password will be sent encrypted by SHA512.</br></p>
                        <p>入力されたパスワードは乱数と組み合わせて、<br/>SHA512により暗号化されてサーバへ送信されます。<br/></p>
                    </blockquote>
                    <p id="modal-error" class="text-error"></p>
                </div>
                <div class="modal-footer">
                    <a href="<?php print($this->get('url')); ?>"  class="btn">BACK</a>
                    <a id="signup" class="btn btn-primary">SingUp</a>
                </div>
            </div>


        <input type="hidden" id="rand"  value="<?php print($this->get('rand')); ?>"></input>
        <input type="hidden" id="token" value="<?php print($this->get('token')); ?>"></input>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/jquery.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/bootstrap.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/sha512.js"></script>
        <script>
                var isSend = false;
                $("#signup-modal").modal('show');
                $("#user").focus();
                $("#signup-modal").on('hidden', function(){
                    $("#signup-modal").modal('show');
                });
                $("#signup").click(function(){
                    if( isSend == true ){
                        return;
                    }
                    var u = $("#user").val(), p = $("#password").val();
                    if( u == "" || p == "" ){
                        $("#modal-error").html("ERROR : The username or password is empty.");        
                        return;
                    }
                    isSend = true;
                    var shaObj = new jsSHA(p, "ASCII");
                    var hash = shaObj.getHash("SHA-512", "HEX");
                    var shaObj2 = new jsSHA(hash+$("#rand").val(), "ASCII");
                    var code = shaObj2.getHash("SHA-512", "HEX");
                    Auth(u,code,$("#token").val()).done(function(data){
                        if( data.status == 'OK' ){
                            location.href = "<?php print($this->get('url')); ?>";
                        }else{
                            isSend = false;
                            console.log(data);
                            $("#password").val("");
                            $("#password").focus();
                            $("#modal-error").html("ERROR : The username or password is NOT correct.");
                        }
                    });
                });
                var Auth = function(un, ps, tk) {
                    var defer = $.Deferred();
                    $.ajax({
                        url: "<?php print($this->get('url')); ?>/signup/authenticate",
                            type: "POST",
                            data: {
                                user: un,
                                password: ps,
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


