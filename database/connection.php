<?php
$conn = new mysqli("localhost", "root","","mis_project");
if(!$conn){
    die("Connection Failed: ".$conn->connect_error);
}
?>