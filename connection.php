<?php
$servername = "fdb1033.awardspace.net";
$username = "4745343_drip";
$password = "DRIPCollectives2026";
$dbname = "4745343_drip";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>