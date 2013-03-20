<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title><?php print($this->get('title')); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=0.1">
        <meta name="description" content="CS24 SERVER MONITORING SYSTEM SETTING PAGE">
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
                    <h3 id="modal-title">SIGNUP PASSWORD SETTING</h3>
                </div>
                <div class="modal-body" id="modal-contents">
                    <p class="lead">Password is required to signup from external network.</p>
                    <form class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="inputPassword">OLD PASSWORD</label>
                            <div class="controls">
                                <input type="password" id="old" placeholder="OLD PASSWORD">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputPassword">NEW PASSWORD </label>
                            <div class="controls">
                                <input type="password" id="password1" placeholder="NEW PASSWORD">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputPassword">INPUT AGAIN </label>
                            <div class="controls">
                                <input type="password" id="password2" placeholder="NEW PASSWORD">
                            </div>
                        </div>
                    </form>
                    <blockquote>
                        <p>If it is first setting, old-password is empty.</p>
                        <p>The password will be sent encrypted by SHA512.</br></p>
                        <p>Password is NOT stored on the server directly.</p>
                        <p>パスワード設定が初めての場合は古いパスワードは空でOKです。</p>
                        <p>入力されたパスワードはSHA512により暗号化されて送信されます。</p>
                        <p>パスワードがそのままサーバ上に保存されることはありません。</p>
                    </blockquote>
                    <p id="modal-error" class="text-error"></p>
                </div>
                <div class="modal-footer">
                    <a href="<?php print($this->get('url')); ?>"  class="btn">BACK</a>
                    <a id="signup" class="btn btn-primary">SEND</a>
                </div>
            </div>


        <input type="hidden" id="token" value="<?php print($this->get('token')); ?>"></input>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/jquery.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/bootstrap.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/sha512.js"></script>

        <script>
                var isSend = false;
                $("#signup-modal").modal('show');
                $("#old").focus();
                $("#signup-modal").on('hidden', function(){
                    $("#signup-modal").modal('show');
                });
                $("#signup").click(function(){
                    if( isSend == true ){
                        return;
                    }
                    var o = $("#old").val(), p1 = $("#password1").val(), p2 = $("#password2").val();
                    if( p1 == "" || p2 == "" ){
                        $("#modal-error").html("ERROR : There is a empty column.");        
                        return;
                    }
                    if( p1 != p2 ){
                        $("#modal-error").html("ERROR : New passwords are mismatch!!");        
                        return; 
                    }
                    if( p1.length < 4 ){
                        $("#modal-error").html("ERROR : Password must be at least 4 chars!!");        
                        return; 
                    }
                    isSend = true;
                    var shaObj = new jsSHA(p1, "ASCII");
                    var code = shaObj.getHash("SHA-512", "HEX");
                    var shaObj2 = new jsSHA(o, "ASCII");
                    var ocode = shaObj2.getHash("SHA-512", "HEX");
                    Auth(ocode,code,$("#token").val()).done(function(data){
                        if( data.status == 'OK' ){
                            location.href = "<?php print($this->get('url')); ?>";
                        }else{
                            isSend = false;
                            console.log(data);
                            $("#old").val("");
                            $("#password1").val("");
                            $("#password2").val("");
                            $("#old").focus();
                            $("#modal-error").html("ERROR : Password setting failure.");
                            console.log(data.old_password_enc);
                            console.log(data.old_password);
                        }
                    });
                });
                var Auth = function(un, ps, tk) {
                    var defer = $.Deferred();
                    $.ajax({
                        url: "<?php print($this->get('url')); ?>/signup/pass",
                            type: "POST",
                            data: {
                                oldpass: un,
                                newpass: ps,
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


