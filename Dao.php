<?php
require_once 'KLogger.php';

class Dao {

  private $host = "localhost";
  private $db = "ckenning";
  private $user = "ckenning";
  private $pass = "password";

  protected $logger;

  public function __construct () {
    $this->logger = new KLogger ( "log.txt" , KLogger::DEBUG );
  }

  public function getConnection () {
    $this->logger->LogDebug("Getting a connection.");
    try {
      $conn = new PDO("mysql:host={$this->host};dbname={$this->db}", $this->user,
            $this->pass);
    } catch (Exception $e) {
      $this->logger->LogError(__CLASS__ . "::" . __FUNCTION__ . " The database exploded " . print_r($e,1));
      echo print_r($e,1);
      exit;
    }
    return $conn;
  }

  public function getComments () {
    $conn = $this->getConnection();
    return $conn->query("select comment, date_created  from comment order by date_created desc", PDO::FETCH_ASSOC);
  }

  public function getUser ($userName) {
    $conn = $this->getConnection();
    return $conn->query("select *  from user where user_name = {$userName}", PDO::FETCH_ASSOC);
  }

  public function createUser ($userName, $password, $imagePath) {
    $conn = $this->getConnection();
    $saveQuery = "insert into user (name, email, age, password, image_path) values (:name, :email, :age, :password, :image_path)";
    $q = $conn->prepare($saveQuery);
    $age = "18";
    $q->bindParam(":name", $userName);
    $q->bindParam(":email", $userName);
    $q->bindParam(":age", $age);
    $q->bindParam(":password", $password);
    $q->bindParam(":image_path", $imagePath);
    $q->execute();
  }

  public function saveComment ($comment) {
    $this->logger->LogInfo("Saving a comment [{$comment}]");
    $conn = $this->getConnection();
    $saveQuery = "insert into comment (comment, user_id) values (:comment, 1)";
    $q = $conn->prepare($saveQuery);
    $q->bindParam(":comment", $comment);
    $q->execute();
  }
}
