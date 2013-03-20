<?php
error_reporting( E_ERROR );
require_once(dirname(__FILE__).'/../mvc/Dispatcher.php');
$dispatcher = new Dispatcher();
$dispatcher->dispatch();

