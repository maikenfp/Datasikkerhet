<?php 
  class Teacher {
    private $conn;
    private $table = 'foreleser';

    public $passord; 
    public $epost;
    public $navn;

    public function __construct($db) {
      $this->conn = $db;
    }

    public function read() {
      $query = 'SELECT navn, epost FROM foreleser';
      
      $stmt = $this->conn->prepare($query);

      $stmt->execute();

      return $stmt;
    }
    
  }