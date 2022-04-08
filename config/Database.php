<?php
class Database
{
  // DB Params
  private $host = 'localhost';
  private $db_name = 'datasikkerhet2fqwldersd';
  private $username = 'adminr4gtQwerT32';
  private $password = 'Fle3Cwdsefdeg4';
  private $conn;

  private $host2 = '158.39.188.205';

  private $usernameStudent = 'student';
  private $usernameForeleser = 'foreleser';
  private $usernameGjest = 'gjest';

  private $passwordStudent = 'WtFdwe42t43fde';
  private $passwordForeleser = 'Fve4gfw43ftwy';
  private $passwordGjest = '23btxdbtw4gbe5';

  // DB Connect
  public function connect()
  {
    $this->conn = null;

    try {
      $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->conn->exec("set names utf8");
    } catch (PDOException $e) {
      echo 'Connection Error: ' . $e->getMessage();
    }
    
    return $this->conn;
  }

  public function connectStudent()
  {
    $this->conn = null;

    try {
      $this->conn = new PDO('mysql:host=' . $this->host2 . ';dbname=' . $this->db_name, $this->usernameStudent, $this->passwordStudent);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->conn->exec("set names utf8");
    } catch (PDOException $e) {
      echo 'Connection Error: ' . $e->getMessage();
    }

    return $this->conn;
  }

  public function connectForeleser()
  {
    $this->conn = null;

    try {
      $this->conn = new PDO('mysql:host=' . $this->host2 . ';dbname=' . $this->db_name, $this->usernameForeleser, $this->passwordForeleser);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->conn->exec("set names utf8");
    } catch (PDOException $e) {
      echo 'Connection Error: ' . $e->getMessage();
    }

    return $this->conn;
  }

  public function connectGjest()
  {
    $this->conn = null;

    try {
      $this->conn = new PDO('mysql:host=' . $this->host2 . ';dbname=' . $this->db_name, $this->usernameGjest, $this->passwordGjest);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->conn->exec("set names utf8");
    } catch (PDOException $e) {
      echo 'Connection Error: ' . $e->getMessage();
    }

    return $this->conn;
  }

}
