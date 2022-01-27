<?php
include_once '../config/Database.php';

$database = new Database();
$db = $database->connect();

if(isset($_POST['submit_email']) && $_POST['email']) {
  $sql = "SELECT epost, passord FROM student WHERE epost='email'";

  $stmt = $db->query($sql);
  $result = $db->prepare("SELECT SQL_CALC_FOUND_ROWS student_id, passord, studieretning, studiekull, epost, navn FROM student");
  $result->execute();
  $result = $db->prepare("SELECT FOUND_ROWS()");
  $result->execute();
  $row_count = $result->fetchColumn();

  if($row_count === 1) {
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $email = md5($row['epost']);
      $pass = md5($row['passord']);

      $link="<a href='www.samplewebsite.com/reset.php?key=".$email."&reset=".$pass."'>Click To Reset password</a>";
      require_once 'C:\xampp\Composer\vendor\autoload.php';
      $mail = new PHPMailer(true);
      $mail->CharSet =  "utf-8";
      $mail->IsSMTP();
      // enable SMTP authentication
      $mail->SMTPAuth = true;
      // GMAIL username
      $mail->Username = "your_email_id@gmail.com";
      // GMAIL password
      $mail->Password = "your_gmail_password";
      $mail->SMTPSecure = "ssl";
      // sets GMAIL as the SMTP server
      $mail->Host = "smtp.gmail.com";
      // set the SMTP port for the GMAIL server
      $mail->Port = "465";
      $mail->From='your_gmail_id@gmail.com';
      $mail->FromName='your_name';
      $mail->AddAddress('reciever_email_id', 'reciever_name');
      $mail->Subject  =  'Reset Password';
      $mail->IsHTML(true);
      $mail->Body    = 'Click On This Link to Reset Password '.$pass.'';

      if($mail->Send()) {
        echo "Check Your Email and Click on the link sent to your email";
      } else {
        echo "Mail Error - >".$mail->ErrorInfo;
      }
    }
  }
}
?>
