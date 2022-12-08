<?php 
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "videotheque";

//  $conn = new mysqli($servername, $username, $password, $dbname);
    $conn = new PDO('mysql:host=$servername;dbname=$dbname', $userame, $password);
  if ($conn->connect_error) {
    die("La connexion a échouée: " . $conn->connect_error);
  }
?>
