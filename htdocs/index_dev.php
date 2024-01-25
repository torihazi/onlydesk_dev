<?php
ini_set('display_errors', true);

require '../bootstrap.php';
require '../DeskApplication.php';
require '../conf/const.php';
require '../utility/utility.php';
require '../utility/pager.php';

$app = new DeskApplication(true);
$app->run();