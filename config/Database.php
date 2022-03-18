<?php
class Database
{
  // DB Params
	/*
  private $host = '158.39.188.205'
  private $db_name = 'datasikkerhet';
  private $username = 'datasikkerhet';
  private $password = '1234';
  private $conn;
  */

	// DUMMY
  private $host = 'localhost';
  private $db_name = 'datasikkerhet';
  private $username = 'root';

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
}
