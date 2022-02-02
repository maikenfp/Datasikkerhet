<!DOCTYPE html>

<html>
	<head>
	    <title>LOGG INN</title>
	    <link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
	     <form action="loginProcess_TEACHER.php" method="post">
	        <h2>FORELESER LOGG INN</h2>

	        <?php if (isset($_GET['error'])) { ?>
	            <p class="error"><?php echo $_GET['error']; ?></p>
	        <?php } ?>

	        <label>E-post</label>
	        <input type="text" name="brukerEpost" placeholder="E-post"><br>

	        <label>Passord</label>
	        <input type="password" name="brukerPassord" placeholder="Passord"><br>

	        <button type="submit">Logg inn</button>
	     </form>

			 <a href="index.php">Tilbake</a>
	</body>
</html>
