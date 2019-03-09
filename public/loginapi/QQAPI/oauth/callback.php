<?php

require_once("../qqConnectAPI.php");
$qc = new QC();
echo $qc->qq_callback();
echo $qc->get_openid();
