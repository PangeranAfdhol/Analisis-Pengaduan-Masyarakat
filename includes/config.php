<?php
$SETT = array (
	'db_host'	 	=> 'localhost',
	'db_username' 	=> 'root',
	'db_password' 	=> '',
	'db_name'		=> 'ta_knn'
);

$conn = new mysqli($SETT['db_host'], $SETT['db_username'], $SETT['db_password'], $SETT['db_name']);

if ($conn->connect_error){
	echo "Gagal terkoneksi ke database : (".$mysqli->connect_error.")".$mysqli->connect_error;
}
    
?>