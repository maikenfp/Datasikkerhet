<?php 
  class Student {
    private $conn;
    private $table = 'student';

    public $passord; 
    public $studieretning;
    public $studiekull;
    public $epost;
    public $navn;

    public function __construct($db) {
      $this->conn = $db;
    }

    public function read() {
      $query = 'SELECT navn, epost, studieretning.studieretning, studiekull FROM student JOIN studieretning on student.studieretning = studieretning.retning_id';
      
      $stmt = $this->conn->prepare($query);

      $stmt->execute();

      return $stmt;
    }

    public function login() {
          $query = 'SELECT epost, navn, passord, studieretning, studiekull FROM ' . $this->table . ' 
          WHERE epost = :epost AND passord = :passord';

          $stmt = $this->conn->prepare($query);

          $stmt->bindParam(':epost', $this->epost);
          $stmt->bindParam(':passord', $this->passord);

          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
          if($this->passord == $row['passord']){

          $this->epost= $row['epost'];
          $this->navn = $row['navn'];
          $this->studieretning = $row['studieretning'];
          $this->passord = $row['passord'];
          $this->studiekull = $row['studiekull'];

          } else {
            echo "Epost eller passord stemmer ikke";
            exit();
          }
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

          try{
            $stmt->execute();
          }catch(PDOException $e) {
            echo 'Message: ' .$e->getMessage();
          }
    }

    
  }