<?php

$d_host = "localhost";
$d_name = "db_wings365rtpb";
$d_password = "8cdzNpepsrJYrRGK";
$d_dbname = "db_wings365rtpb";

$data = mysqli_connect($d_host, $d_name, $d_password, $d_dbname);
date_default_timezone_set('Asia/Bangkok');
$today = date("Y-m-d H:i:s");
$todaydate = date("Y-m-d");


if(!$data) {
    echo "Error Connect Database";
}

?>