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

    public function read_single() {
          $query = 'SELECT emnenavn, emnekode FROM ' . $this->table . ' WHERE emnenavn = ?';

          $stmt = $this->conn->prepare($query);

          $stmt->bindParam(1, $this->emnenavn);

          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          $this->emnenavn= $row['emnenavn'];
          $this->emnekode = $row['emnekode'];

          
    }

    public function create() {
          $query = 'INSERT INTO ' . $this->table . ' SET emnenavn = :emnenavn, emnekode = :emnekode, pinkode = :pinkode';
          $stmt = $this->conn->prepare($query);

          $this->emnenavn = htmlspecialchars(strip_tags($this->emnenavn));
          $this->emnekode = htmlspecialchars(strip_tags($this->emnekode));
          $this->pinkode = htmlspecialchars(strip_tags($this->pinkode));
          $stmt->bindParam(':emnenavn', $this->emnenavn);
          $stmt->bindParam(':emnekode', $this->emnekode);
          $stmt->bindParam(':pinkode', $this->pinkode);
          if($stmt->execute()) {
            return true;
      }

      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    public function update() {
          $query = 'UPDATE ' . $this->table . '
                                SET emnekode = :emnekode WHERE emnenavn = :emnenavn';

          $stmt = $this->conn->prepare($query);

          $this->emnenavn = htmlspecialchars(strip_tags($this->emnenavn));
          $this->emnekode = htmlspecialchars(strip_tags($this->emnekode));

          $stmt->bindParam(':emnenavn', $this->emnenavn);
          $stmt->bindParam(':emnekode', $this->emnekode);
          
          if($stmt->execute()) {
            return true;
          }

          printf("Error: %s.\n", $stmt->error);

          return false;
    }

    public function delete() {
          $query = 'DELETE FROM ' . $this->table . ' WHERE emnenavn = :emnenavn';

          $stmt = $this->conn->prepare($query);

          $this->emnenavn = htmlspecialchars(strip_tags($this->emnenavn));

          $stmt->bindParam(':emnenavn', $this->emnenavn);

          if($stmt->execute()) {
            return true;
          }

          printf("Error: %s.\n", $stmt->error);

          return false;
    }
    
  }