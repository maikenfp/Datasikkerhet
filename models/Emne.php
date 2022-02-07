<?php 
  class Emne {
    private $conn;
    private $table = 'emne';

    public $pinkode; 
    public $emnenavn;
    public $emnekode;

    public function __construct($db) {
      $this->conn = $db;
    }

    public function read() {
      $query = 'SELECT emnenavn, emnekode FROM emne';
      
      $stmt = $this->conn->prepare($query);

      $stmt->execute();

      return $stmt;
    }
    
  }