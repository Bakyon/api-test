<?php
 class Connection {
     private $sql, $pdo, $statement;
     private static $database = null;

     private function __construct() {
         $dsn = "mysql:host=" . HOST . ";dbname=" . DBNAME . ";port=" . PORT . ";charset=utf8mb4";
         $options = [
             PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
             PDO::ATTR_EMULATE_PREPARES   => false,
         ];
         $this->pdo = new PDO($dsn, USER, PASS, $options);
     }

     public static function getDatabase() {
         if (self::$database === null) {
             self::$database = new self();
         }
         return self::$database;
     }


     // Prevent cloning of the instance
     function __clone() {}

     // Prevent unserialize of the instance
     function __wakeup() {}

     function set_query($sql)
     {
         $this->sql = $sql;
         return $this;
     }

     private function exec($params = []) {
         $this->statement = $this->pdo->prepare($this->sql);
         $this->statement->execute($params);
         return $this->statement;
     }

     function load_row($params = [])
     {
         return $this->exec($params)->fetch(PDO::FETCH_OBJ);
     }

     function load_rows($params = [])
     {
         return $this->exec($params)->fetchAll(PDO::FETCH_OBJ);
     }

     function save($params = [])
     {
         return $this->exec($params);
     }

     function last_insert_id() {
         return $this->pdo->lastInsertId();
     }

     function disconnect() {
         $this->pdo = null;
         self::$database = null;
     }
 }