<?php 
include 'sphinx.php';

$dbhost="localhost";
$dbuser="root";
$dbpass="";
$db="core_sphinx";
$config = array(
	"connect" 		=> array($dbhost,$dbuser,$dbpass,$db),
	"header"  		=> "/", 
	"footer"  		=> "/", 
	"default_file"	=> "/",
	"base_url"      => "/",
	"system_name"   => "",
	"file_include"	=> true
);
$sphinx = new sphinx($config);

$sphinx->file();
	
