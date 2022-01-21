<?php
session_start();
if (isset($_SESSION['foreleser_id']) && isset($_SESSION['navn'])) {
?>

<!DOCTYPE html>

<html>
	<head>
	    <title>HJEM</title>
	    <link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body>
	     <h1>Hei, <?php echo $_SESSION['navn']; ?></h1>
			 <h1>Id: <?php echo $_SESSION['foreleser_id']; ?></h1>
			 <h1>E-post: <?php echo $_SESSION['e-post']; ?></h1>

	     <a href="logout.php">Logg ut</a>
	</body>
</html>

<?php

} else{
     header("Location: login_TEACHER.php");
     exit();
}

?>
