<?php
    global $arrConfig;
    $arrConfig = [];
    if($_SERVER['HTTP_HOST'] == 'gentl.store') {
        error_reporting(E_ALL);
        $arrConfig['url_site']='https://gentl.store';
        $arrConfig['dir_site'] = "/var/www/DDOC";
        $arrConfig['connect_DB'] = array('localhost', 'root', 'ddocPassNice', 'ddoc');
    }
    else if($_SERVER['HTTP_HOST'] == 'web.colgaia.local') {
        $arrConfig['url_site']='http://web.colgaia.local/12itm124/DDOC';
        $arrConfig['dir_site'] = "W:\\www\\DDOC";
        error_reporting(E_ALL); 
        $arrConfig['connect_DB'] = array("localhost", "12itm124", "12itm124654b57cf2e691", "12itm124_ddoc"); 
    }



    // caminhos Docs e/ou fotografias
    $arrConfig['dir_css'] = $arrConfig['dir_site'].'/src/css/';
    $arrConfig['url_css'] = $arrConfig['url_site'].'/src/css/';
    $arrConfig['dir_posts'] = $arrConfig['dir_site'].'/src/upload/posts/';
    $arrConfig['url_posts'] = $arrConfig['url_site'].'/src/upload/posts/';
    $arrConfig['dir_assets'] = $arrConfig['dir_site'].'/src/assets/';
    $arrConfig['url_assets'] = $arrConfig['url_site'].'/src/assets/';
    $arrConfig['dir_users'] = $arrConfig['dir_site'].'/upload/users/';
    $arrConfig['url_users'] = $arrConfig['url_site'].'/upload/users/';
    $arrConfig['fotos_auth'] = array ('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
    $arrConfig['fotos_maxUpload'] = 3000000;

    // caminhos Ficheiros
    $arrConfig['files_auth'] = array ('application/pdf');
    $arrConfig['files_maxUpload'] = 10000000;

    // número de registo de página, para situações de paginação
    $arrConfig['num_reg_pagina'] = 25;
