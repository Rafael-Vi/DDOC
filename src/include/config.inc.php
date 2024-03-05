<?php
    @session_start();
    global $arrConfig;

    if($_SERVER['HTTP_HOST'] == 'localhost') {
        error_reporting(E_ALL);
    } else {
        error_reporting(0);
    }

    // acessos FrontOffice
    $arrConfig['url_site']='http://localhost/DDOC';
    $arrConfig['dir_site'] = "C:\\wamp64\\www\\DDOC";

    // caminhos Docs e/ou fotografias
    $arrConfig['dir_posts'] = $arrConfig['dir_site'].'/upload/posts/';
    $arrConfig['url_posts'] = $arrConfig['url_site'].'/upload/posts/';
    $arrConfig['dir_users'] = $arrConfig['dir_site'].'/upload/users/';
    $arrConfig['url_users'] = $arrConfig['url_site'].'/upload/users/';
    $arrConfig['fotos_auth'] = array ('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
    $arrConfig['fotos_maxUpload'] = 3000000;

    // caminhos Ficheiros
    $arrConfig['files_auth'] = array ('application/pdf');
    $arrConfig['files_maxUpload'] = 10000000;

    // número de registo de página, para situações de paginação
    $arrConfig['num_reg_pagina'] = 25;

    require "functions/alerts.inc.php";
    require "functions/echohtml.inc.php";
    require "functions/SQLfunctions.inc.php";
    require "functions/validateLRForm.inc.php";
    getThemes();



?>  