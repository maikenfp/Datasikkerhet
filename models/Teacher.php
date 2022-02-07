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

    public function read_single() {
          $query = 'SELECT epost, navn FROM ' . $this->table . ' WHERE navn = ?';

          $stmt = $this->conn->prepare($query);

          $stmt->bindParam(1, $this->navn);

          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          $this->epost= $row['epost'];
          $this->navn = $row['navn'];

          
    }

    public function create() {
          $query = 'INSERT INTO ' . $this->table . ' SET navn = :navn, epost = :epost, passord = :passord ';
          $stmt = $this->conn->prepare($query);

          $this->navn = htmlspecialchars(strip_tags($this->navn));
          $this->epost = htmlspecialchars(strip_tags($this->epost));
          $this->passord = htmlspecialchars(strip_tags($this->passord));
          $stmt->bindParam(':navn', $this->navn);
          $stmt->bindParam(':epost', $this->epost);
          $stmt->bindParam(':passord', $this->passord);
          if($stmt->execute()) {
            return true;
      }

      printf("Error: %s.\n", $stmt->error);

      return false;
    }
    
  }