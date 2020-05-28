<?php

namespace App\modelosExcel;

require_once 'Scl.php';

$scl = new Scl("2019-09-11", "1");
print_r($scl->prepararDatos());
