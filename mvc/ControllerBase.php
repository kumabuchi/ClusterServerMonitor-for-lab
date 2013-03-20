<?php

abstract class ControllerBase {

	protected $config;
	protected $controller;
    protected $action;
    protected $controller_origin;
    protected $action_origin;
	protected $model;
	protected $view;
	protected $template;
	protected $request;
    protected $ini;

    // 認証ユーザ名
    protected $user = null;
    protected $lan  = false;

	// コンストラクタ
	public function __construct(){
		$this->request = new Request();
	}

	// 各設定情報をセット
	public function setConfig($config){
		$this->config = $config;
    }

	// ユーザルーティング前のコントローラーとアクションの文字列設定
	public function setOriginControllerAction($controller_origin, $action_origin){
		$this->controller_origin = $controller_origin;
		$this->action_origin = $action_origin;
	}

	// コントローラーとアクションの文字列設定
	public function setControllerAction($controller, $action){
		$this->controller = $controller;
		$this->action = $action;
	}

	// Model, Viewの初期化、設定ファイル読み込み
	public function initialize(){
		$this->initializeModel();
		$this->initializeView();
		$appConfigFile = dirname(__FILE__).'/../config/application.conf';
		if( file_exists($appConfigFile) ){
            $this->ini = parse_ini_file($appConfigFile, true);
		}
	}

	// モデルインスタンス生成処理
	protected function initializeModel(){
		$className = ucfirst($this->controller).'Model';
		$classFile = sprintf('%s/app/models/%s.php', $this->config["DIR"]["root"], $className);
		if (false == file_exists($classFile)) {
			return;
		}
        require_once $classFile;
		if (false == class_exists($className)) {
			return;
		}        
		$this->model = new $className();
		$this->model->initDb($this->config["DATABASE"]);
	}

	// ビューの初期化
	protected function initializeView(){
		$this->view = new ViewBase();
		$this->template = sprintf('%s/app/views/%s/%sView.php', $this->config["DIR"]["root"], $this->controller, ucfirst($this->action));
	}


	// 処理実行
	public function run(){
		try {

			// 共通前処理
			$this->preAction();

			// アクションメソッド
			$method = $this->action;
			$this->$method();            

			// 表示
			include_once $this->template; 

		} catch (Exception $e) {
			// ログ出力等の処理を記述
            require_once(dirname(__FILE__).'/../lib/functions.php');
            writeLog(1, 'ENVALID REQUEST : '.getHostAddr());
		}
	}

	// 共通前処理
    protected function preAction(){
        require_once(dirname(__FILE__).'/../lib/functions.php');
        // 認証処理
        $this->user = null;
        if( isset($this->ini['ip'][getHostAddr()]) ){
            $this->user = $this->ini['ip'][getHostAddr()];
            $this->lan = true;
        }
        if( isset($_SESSION['user']) ){
            $this->user = $_SESSION['user'];
        }
        // 設定ファイルの内部変数を展開
        $this->ini['type']['mac']   = explode(",", $this->ini['type']['mac']);
        $this->ini['type']['linux'] = explode(",", $this->ini['type']['linux']);
	}

	// view変数取得ラッパー関数
	protected function get($key){
		return $this->view->getVariable($key);
	}

	// view変数設置ラッパー関数
	protected function set($key, $val){
		$this->view->setVariable($key, $val);
	}

}
