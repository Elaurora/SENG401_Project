<?php
$globalDBIP = '104.198.102.12';
echo file_get_contents('http://' . $globalDBIP . '/?type=unsubscribe');