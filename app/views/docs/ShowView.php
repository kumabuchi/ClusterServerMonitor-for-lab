<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Documents</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="SERVER MONITORING SYSTEM DOCUMENTS.">
        <meta name="author" content="Kenji KUMABUCHI">
        <link rel="shortcut icon" href="<?php print($this->get('url')); ?>/public/images/favicon.ico" >
        <link rel="stylesheet" href="<?php print($this->get('url')); ?>/public/stylesheets/bootstrap.doc.min.css">
        <link rel="stylesheet" href="<?php print($this->get('url')); ?>/public/stylesheets/bootstrap-responsive.doc.min.css">
        <link rel="stylesheet" href="<?php print($this->get('url')); ?>/public/stylesheets/bootswatch.css">
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body class="preview" data-spy="scroll" data-target=".subnav" data-offset="80">
        <!-- Navbar
        ================================================== -->
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">Documents</a>
                    <div class="nav-collapse" id="main-menu">
                        <ul class="nav" id="main-menu-left">
                            <li><a href="<?php print($this->get('url')); ?>">Server Monitor TOP</a></li>
                            <li><a href="<?php print($this->get('url')); ?>/hpc">HPC Cluster Server</a></li>
                            <li><a href="<?php print($this->get('url')); ?>/etc">Video Team Server</a></li>
                            <li><a href="<?php print($this->get('url')); ?>/comp">Web and Computation Server</a></li>
                            <li><a href="<?php print($this->get('url')); ?>/mac">Xserve</a></li>
                            <li><a href="http://www.ai.cs.kobe-u.ac.jp/~kumabuchi/demo/monitor">Demo system</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        <div class="container">

            <!-- Masthead
            ================================================== -->
            <header class="jumbotron subhead" id="overview">
            <div class="row">
                <div class="span12">
                    <h1>Server Monitor Documents</h1>
                    <p class="lead">How to use cs24 server monitoring system ?</p>
                </div>
            </div>
            <div class="subnav">
                <ul class="nav nav-pills">
                    <li><a href="#main">Main</a></li>
                    <li><a href="#alertcenter">AlertCenter</a></li>
                    <li><a href="#servers">Servers</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#qa">Q&A</a></li>
                </ul>
            </div>
            </header>




            <!-- Main
            ================================================== -->
            <section id="main">
            <div class="page-header">
                <h1>Main</h1>
            </div>
            <div class="row">
                <div class="span12">
                    <p class="lead">各サーバグループのトップページ</p>
                    <p>　トップページには各サーバの<span class="text-error">CPU利用率</span>、<span class="text-success">メモリ使用率</span>、<span class="text-warning">ロードアベレージ</span>、<span class="text-info">実行プロセス数</span>が表示されます。各情報はfosterが定期的に収集しており、最大10分前のものとなります。</p>
                    <!-- <p>　トップページには各サーバの<span class="text-error">CPU利用率</span>、<span class="text-success">メモリ使用率</span>、<span class="text-warning">ロードアベレージ</span>、<span class="text-info">実行プロセス数</span>が表示されます。各情報はfosterが定期的に収集しており、最大10分前のものとなります。最新の情報が知りたい場合は、メニュー一番左の Refresh ボタンを押してください。（リアルタイムで情報を収集するため、表示までに数秒かかります。混雑防止のため多用しないようにしてください。）</p>-->
                    <p>　またコンテンツ部分をクリックすると、ディスクの空き容量やデータの入出力に関する情報を見ることができます。左上のタイトル文字はこのトップページにリンクしています。
                    </p>
                </div>
                <ul class="thumbnails">
                    <li class="span6">
                    <a href="#main" class="thumbnail">
                        <img src="<?php print($this->get('url')); ?>/public/images/main.jpg" alt="">
                    </a>
                    </li>
                    <li class="span6">
                    <a href="#main" class="thumbnail">
                        <img src="<?php print($this->get('url')); ?>/public/images/main2.jpg" alt="">
                    </a>
                    </li>
                </ul>
            </div>
            </section>

            <!-- AlertCenter 
            ================================================== -->
            <section id="alertcenter">
            <div class="page-header">
                <h1>AlertCenter</h1>
            </div>
            <div class="row">
                <div class="span12">
                    <p class="lead">メールアラートセンター</p>
                    <p>　各ユーザはサーバのプロセス詳細ページから実行中のプロセスに対してメールアラートを設定することができます。<span class="text-success">アラートを設定すると、プロセスが終了した時にメールを受け取ることができます。</span>アラートセンターでは、現在設定されているアラートの一覧と、登録済アラートの削除を提供します。</p>
                    <p>　各アラートは10分間隔でチェックされています。よってアラートの受信には最大で10分のラグが発生します。アラートの追加時にデフォルトで表示されるメールアドレスは、Setting から変更することができます。実行に時間のかかるプログラムを動かして帰る際に是非ご活用ください。
                    </p>
                </div>
                <ul class="thumbnails">
                    </li>
                    <li class="span12">
                    <a href="#alertcenter" class="thumbnail">
                        <img src="<?php print($this->get('url')); ?>/public/images/alertcenter.jpg" alt="">
                    </a>
                    </li>
                </ul>
            </div>
            </section>


            <!-- Servers
            ================================================== -->
            <section id="servers">
            <div class="page-header">
                <h1>Servers</h1>
            </div>
            <div class="row">
                <div class="span12">
                    <p class="lead">各サーバのプロセス詳細ページ</p>
                     <p>　メニュー Servers のプルダウンから、各サーバで<span class="text-info">実行中のプロセス</span>を調べることができます。<span class="text-error">プロセスの PIDをクリックすることで、プロセスを自分の AlertCenter に追加することができます</span>（詳細は AlertCenter を参照）。プルダウンからはさらに 各サーバの情報を遡って見れる History や、各種情報を変更できる Setting ページなどへのリンクもあります。</p>
                    <p>　タブレット・スマートフォンではプルダウンメニューをクリックできないことがありますが、その場合は Servers ボタンを直接クリックすると各サーバが順に表示されます。
                    </p>
                </div>
                <ul class="thumbnails">
                    <li class="span6">
                    <a href="#servers" class="thumbnail">
                        <img src="<?php print($this->get('url')); ?>/public/images/servers.jpg" alt="">
                    </a>
                    </li>
                    <li class="span6">
                    <a href="#servers" class="thumbnail">
                        <img src="<?php print($this->get('url')); ?>/public/images/servers2.jpg" alt="">
                    </a>
                    </li>
                </ul>
            </div>
            </section>

            <!-- About
            ================================================== -->
            <section id="about">
            <div class="page-header">
                <h1>About</h1>
            </div>
            <div class="row">
                <div class="span12">
                    <p class="lead">Server Monitor について</p>
                    <p>　このページには、プルダウンをクリックできないスマートフォン及びタブレット用のリンクと、バグ及びフィードバック送信用のメールアドレスを表示しています。</p>
                    </p>
                </div>
            </div>
            </section>


            <!-- Q&A
            ================================================== -->
            <section id="qa">
            <div class="page-header">
                <h1>Q&A</h1>
            </div>
            <div class="row">
                <div class="span12">
                    <p class="lead">こんなときどうするの?</p>
                    <dl>
                        <dt>Q.　ユーザ名やメールアドレスを変更したい。</dt>
                        <dd>A.　setting &gt; Edit default information から変更できます。変更する項目を入力して OK をクリックしてください。ユーザ名を変更すると、アラートリストやパスワードが全て失われるのでご注意ください。また、設定変更の反映には多少時間がかかります。</dd>
                        <br/>
                        <dt>Q.　研究室のPC以外から利用したい。</dt>
                        <dd>A.　登録されているIPアドレス以外から利用する場合はまずパスワードの設定が必要になります。研究室の自分の PC から Server Monitor にアクセスし、setting &gt; Set password to use from external network からパスワードを設定してください。初回設定時は古いパスワードは空のままで OK です。外部から利用する際は、ユーザ名と設定したパスワードでサインインできます。ユーザ名は初期設定では研究室のメールアドレスの @ より前の部分です。</dd>
                        <br/>
                        <dt>Q.　サーバがダウンした場合にメールを受け取りたい。</dt>
                        <dd>A.　Server Monitor が監視しているサーバがダウンした場合にメールを受け取ることができます。setting &gt; Server Down Mail Alert System( for server-administrator ) からメールアドレスを登録してください。</dd>
                        <br/>
                        <dt>Q.　Historyで値がマイナスになっているサーバがある。</dt>
                        <dd>A.　その時間にサーバがダウンしていたことを表わしています。サーバがダウンすると、Historyでは値が全て -1 となります。</dd>
                        <br/>
                        <dt>Q.　バグを見つけた! or 使い方が良く分からない! or こんな機能を追加してほしい!
                        <dd>A.　アプリケーションに対するフィードバックは常に受け付けています。<a href="mailto:server.monitor.cs24@gmail.com"> server.monitor.cs24@gmail.com </a>まで!</dd>
                    </dl>
                </div>
            </div>
            </section>
            <br><br>
            <br><br>


            <!-- Footer
            ================================================== -->
            <hr>
            <footer id="footer">
            Server Monitoring System Documents for cs24
            </footer>

        </div><!-- /container -->


        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?php print($this->get('url')); ?>/public/javascripts/bsa.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/jquery.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/bootstrap.doc.min.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/application.js"></script>
        <script src="<?php print($this->get('url')); ?>/public/javascripts/bootswatch.js"></script>
    </body>
</html>
