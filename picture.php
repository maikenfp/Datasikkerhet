<?php

if(isset($_POST['submit'])){

$imgFile = $_FILES['img']['name'];
$tmp_dir = $_FILES['img']['tmp_name'];
$imgSize = $_FILES['img']['size'];

if(!empty($imgFile))
{

$upload_dir = 'photos/';

$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); 

$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); 
$coverpic = rand(1000,1000000).".".$imgExt;

if(in_array($imgExt, $valid_extensions)){
    if($imgSize < 5000000)
{
move_uploaded_file($tmp_dir,$upload_dir.$coverpic);
echo "Opplasting ferdig";
}
else{
$errMSG = "Filen er for stor";
}
}
else{
$errMSG = "Kun JPG, JPEG, PNG & GIF filer tillatt";
}
}
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Bilde</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

    <form method="post" enctype="multipart/form-data">
        <p><input type="file" name="img" required="required" /></p>
        <p><input type="submit" name="submit" style="background-color: rgb(255, 102, 0);" class="btn btn-warning" value="Upload"/></p>
    </form>
</body>
</html>