<?php 
  $servername = "localhost";
  $username = "videotheque";
  $password = "";
  $dbname = "videotheque";

  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>