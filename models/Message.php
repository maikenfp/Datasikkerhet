<?php 
  class Message {
    private $conn;
    private $table = 'melding';

    
    public $epost;
    public $emnenavn;
    public $spørsmål;

    public function __construct($db) {
      $this->conn = $db;
    }

    public function createMessage(){

      $query = 'INSERT INTO melding (question, emne_id, student_id) 
      VALUES (:question, 
      (SELECT emne_id FROM emne WHERE emnenavn = :emnenavn), 
      (SELECT student_id FROM student WHERE epost = :epost))';



          $stmt = $this->conn->prepare($query);

          $this->question = htmlspecialchars(strip_tags($this->question));
          $this->emnenavn = htmlspecialchars(strip_tags($this->emnenavn));
          $this->epost = htmlspecialchars(strip_tags($this->epost));

          $stmt->bindValue(':question', $this->question);
          $stmt->bindValue(':emnenavn', $this->emnenavn);
          $stmt->bindValue(':epost', $this->epost);

          try{
            $stmt->execute();
          
          } catch(PDOException $e) {
            echo 'Message: ' .$e->getMessage();
          }
    }



    
  }