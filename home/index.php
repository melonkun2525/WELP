<?php

header('HTTP/1.0 404 Not Found');
exit;

require_once('../init.php');
require_once('./timeline.php');

$timeline = get_posts(10);
var_dump($timeline);
