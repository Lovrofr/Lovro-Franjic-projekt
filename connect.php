<?php
$servername = "localhost";
$username = "root";
$password = "";
$basename = "sportplus";

$dbc = mysqli_connect($servername, $username, $password, $basename) or die('Greška u konekciji: ' . mysqli_connect_error());

mysqli_set_charset($dbc, "utf8");
?>
