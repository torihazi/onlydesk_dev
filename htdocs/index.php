<?php
require '../bootstrap.php';
require '../DeskApplication.php';
require '../conf/const.php';
require '../utility/utility.php';
require '../utility/pager.php';

$app = new DeskApplication(false);
$app->run();