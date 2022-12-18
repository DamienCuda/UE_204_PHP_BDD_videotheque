<?php 
  $servername = "localhost";
  $username = "videotheque";
  $password = "x92uD]fr2yN8A5Ix";
  $dbname = "videotheque";

  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>