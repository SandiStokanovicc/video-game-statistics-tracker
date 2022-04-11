<?php
  // DAO CLASS IS TRASH ATM, TOO LAZY TO FIX. WILL DO LATER IG
  class TrackerDao{

    private $conn;

    public function __construct(){
      $servername = "localhost";
      $username = user;
      $password = user;
      $schema test;

      $this->conn = new PDO("mysql:host=$servername;dbname=$schema", $username, $password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function get_all(){
      $stmt = $this->conn->prepare("SELECT * FROM 'stat tracker'");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add(){
      $stmt = $this->conn->prepare("INSERT INTO 'stat tracker' (wins, playername) VALUES (:wins, :playtime)");
      $stat->execute(['description' => $description, 'playername' => $playername]);
    }
  }

 ?>
