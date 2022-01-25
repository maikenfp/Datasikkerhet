<?php
function OpenCon()
 { 
 $dbhost = "localhost";
 $dbuser = "test";
 $dbpass = "test123";
 $db = "datasikkerhet";
 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
 
 return $conn;
 }
 
function CloseCon($conn)
 {
 $conn -> close();
 }
   
?>