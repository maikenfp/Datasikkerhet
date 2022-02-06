<?php 
  class Student {
    private $conn;
    private $table = 'student';

    public $passord; 
    public $studieretning;
    public $studiekull;
    public $epost;
    public $navn;
    public $student_id;
    public $emne_id;
    public $spørsmål;

    public function __construct($db) {
      $this->conn = $db;
    }

    public function read() {
      $query = 'SELECT navn, epost, studieretning.studieretning, studiekull FROM student JOIN studieretning on student.studieretning = studieretning.retning_id';
      
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
          $query = 'INSERT INTO ' . $this->table . ' SET navn = :navn, epost = :epost, 
          studieretning = (SELECT retning_id FROM studieretning WHERE studieretning = 
          :studieretning), studiekull = :studiekull, passord = :passord ';
          $stmt = $this->conn->prepare($query);

          $this->navn = htmlspecialchars(strip_tags($this->navn));
          $this->epost = htmlspecialchars(strip_tags($this->epost));
          $this->passord = htmlspecialchars(strip_tags($this->passord));
          $this->studieretning = htmlspecialchars(strip_tags($this->studieretning));
          $this->studiekull = htmlspecialchars(strip_tags($this->studiekull));
          $stmt->bindParam(':navn', $this->navn);
          $stmt->bindParam(':epost', $this->epost);
          $stmt->bindParam(':passord', $this->passord);
          $stmt->bindParam(':studieretning', $this->studieretning);
          $stmt->bindParam(':studiekull', $this->studiekull);
          if($stmt->execute()) {
            return true;
      }

      printf("Error: %s.\n", $stmt->error);

      return false;
    }


    public function update() {
          $query = 'UPDATE ' . $this->table . '
                                SET epost = :epost, studieretning = (SELECT retning_id FROM studieretning WHERE studieretning = :studieretning), studiekull = :studiekull WHERE epost = :epost AND passord = :passord';

          $stmt = $this->conn->prepare($query);

          $this->navn = htmlspecialchars(strip_tags($this->navn));
          $this->epost = htmlspecialchars(strip_tags($this->epost));
          $this->studieretning = htmlspecialchars(strip_tags($this->studieretning));
          $this->studiekull = htmlspecialchars(strip_tags($this->studiekull));
          $this->passord = htmlspecialchars(strip_tags($this->passord));

          $stmt->bindParam(':navn', $this->navn);
          $stmt->bindParam(':epost', $this->epost);
          $stmt->bindParam(':studieretning', $this->studieretning);
          $stmt->bindParam(':studiekull', $this->studiekull);
          $stmt->bindParam(':passord', $this->passord);

          if($stmt->execute()) {
            return true;
          }

          printf("Error: %s.\n", $stmt->error);

          return false;
    }

    public function delete() {
          $query = 'DELETE FROM ' . $this->table . ' WHERE navn = :navn AND passord = :passord';

          $stmt = $this->conn->prepare($query);

          $this->navn = htmlspecialchars(strip_tags($this->navn));
          $this->passord = htmlspecialchars(strip_tags($this->passord));
          $stmt->bindParam(':navn', $this->navn);
          $stmt->bindParam(':passord', $this->passord);

          if($stmt->execute()) {
            return true;
          }

          printf("Error: %s.\n", $stmt->error);

          return false;
    }
    
  }