<?php

$d1 = new DateTime(date("Y-m-d H:i:s"));
$d2 = new DateTime("1982-10-06 00:00:00.000000");
$res = $d1->diff($d2);


echo $res->y;