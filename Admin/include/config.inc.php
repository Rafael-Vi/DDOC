<?php
@session_start();
global $arrConfig;

/*if (count($_GET)>0) {
    $lang = $_GET['lang'];
} else {
    $lang = 'pt';
}
*/

if($_SERVER['HTTP_HOST'] == 'web.colgaia.local' || $_SERVER['HTTP_HOST'] == 'localhost') {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

if($_SERVER['HTTP_HOST'] == 'web.colgaia.local') {
    $arrConfig['servername'] = 'localhost';
    $arrConfig['username'] = '12itm124';
    $arrConfig['password'] = '12itm124654b57cf2e691';
    $arrConfig['dbname'] = '12itm124_frontoffice_proj';
    $arrConfig['url_site']='http://web.colgaia.local/12itm124/FrontOffice_Proj/Admin/';
    $arrConfig['dir_site']='C:/Share/12itm124/www/FrontOffice_Proj/Admin/';
    $arrConfig['url_fotos']="http://web.colgaia.local/12itm124/FrontOffice_Proj/".'\assets\images';
    $arrConfig['dir_fotos']="C:/Share/12itm124/www/FrontOffice_Proj\assets\images";
    
} elseif($_SERVER['HTTP_HOST'] == 'localhost') {

    $arrConfig['servername'] = 'localhost';
    $arrConfig['username'] = 'root';
    $arrConfig['password'] = '';
    $arrConfig['dbname'] = '12itm124_frontoffice_proj';
    $arrConfig['dir_site']='C:\wamp64\www\FrontOffice_Proj\Admin';
    $arrConfig['url_site']='http://localhost/FrontOffice_Proj/Admin/';
    $arrConfig['url_fotos']="http://localhost/FrontOffice_Proj/".'\assets\images';
    $arrConfig['dir_fotos']="C:\wamp64\www\FrontOffice_Proj\assets\images";
}


/*
$arrConfig['servername'] = 'localhost';
$arrConfig['username'] = '12itm124';
$arrConfig['password'] = '12itm124654b57cf2e691';
$arrConfig['dbname'] = '12itm124_frontoffice_proj';


$arrConfig['servername'] = 'localhost';
$arrConfig['username'] = 'root';
$arrConfig['password'] = '';
$arrConfig['dbname'] = '12itm124_frontoffice_proj';
*/

// isLoginKey - alterar a chave de codificação para o Backoffice
$arrConfig['isLoginKey'] = '';

//$arrConfig['url_site']='http://web.colgaia.local/12itm124/BackOffice_Proj';
//$arrConfig['dir_site']='C:/Share/12itm124/www/BackOffice_Proj';


// caminhos Docs e/ou fotografias
$arrConfig['url_fotos']="http://localhost/FrontOffice_Proj/".'\assets\images';
$arrConfig['dir_fotos']="C:\wamp64\www\FrontOffice_Proj\assets\images";
$arrConfig['fotos_auth'] = array ('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
$arrConfig['fotos_maxUpload'] = 3000000;

// caminhos Ficheiros
$arrConfig['files_auth'] = array ('application/pdf');
$arrConfig['files_maxUpload'] = 10000000;

// número de registo de página, para situações de paginação
$arrConfig['num_reg_pagina'] = 25;

// chamada de outros include
include_once $arrConfig['dir_site'].'/include/functions/db.inc.php'; 
include_once $arrConfig['dir_site'].'/include/functions/echohtml.inc.php'; 
include_once $arrConfig['dir_site'].'/include/functions/SQLfunctions.inc.php'; 
include_once $arrConfig['dir_site'].'/include/functions/checkLogin.inc.php'; 




