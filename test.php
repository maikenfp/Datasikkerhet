<?php
include '../testing.php';
// php code for logging error into a given file
  
// error message to be logged
$error_message = "This is an error message!";
  
// path of the log file where errors need to be logged
$log_file = "../../logs/app.log";
  
// logging error message to given log file
error_log($error_message, 3, $log_file);
  
?>