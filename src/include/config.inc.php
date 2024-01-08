<?php
    @session_start();
    global $arrConfig;

    if($_SERVER['HTTP_HOST'] == 'localhost') {
        error_reporting(E_ALL);
    } else {
        error_reporting(0);
    }

    
    // acessos FrontOffice
    $arrConfig['url_site']='http://web.colgaia.local/12itm1XX/jobportal.pt';
    $arrConfig['dir_site']='http://localhost/DDOC/';


    // caminhos Docs e/ou fotografias
    $arrConfig['url_fotos']=$arrConfig['url_site'].'/upload';
    $arrConfig['dir_fotos']=$arrConfig['dir_site'].'/upload';
    $arrConfig['fotos_auth'] = array ('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
    $arrConfig['fotos_maxUpload'] = 3000000;

    // caminhos Ficheiros
    $arrConfig['files_auth'] = array ('application/pdf');
    $arrConfig['files_maxUpload'] = 10000000;

    // número de registo de página, para situações de paginação
    $arrConfig['num_reg_pagina'] = 25;

    require "functions/dbConnect.inc.php";
    include "functions/alerts.inc.php";
    include "functions/echohtml.inc.php";
    include "functions/SQLfunctions.inc.php";
    include "functions/validateLRForm.inc.php";
    include "functions/validateUpdateUser.inc.php";


?>  