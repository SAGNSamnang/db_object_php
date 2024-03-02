<?php

  // Database configuration
  define("SERVER_NAME", "127.0.0.1");
  define("USER_NAME", "root");
  define("PASSWORD", "");
  define("DATABASE_NAME", "product_shop_db");

  // Connect to database
  function dbConn() {
    // Constant variable not $ sign
    $conn = new mysqli(SERVER_NAME, USER_NAME, PASSWORD, DATABASE_NAME);
    if (!$conn->connect_error) {
      return $conn;
    }
    else {
      echo "Error in connection database: " . $conn->connect_error; 
      exit();
    }
  }
  // Close a database
  function dbClose($conn) {
    $conn->close();
  }
  // Select a table from a database
  function dbSelect($table, $column="*", $criteria="", $clause="") {
    if (empty($table)) {
      return false;
    }
    $sql = "SELECT " . $column . " FROM ". $table;
    if (!empty($criteria)) {
      $sql .= " WHERE " . $criteria;
    }
    if (!empty($clause)) {
      $sql .= " " . $clause;
    }

    $conn = dbConn();
    $result = $conn->query($sql);
    if (!$result) {
      echo "Error in selecting data: " . $conn->error;
      return false;
    }
    dbClose($conn);
    return $result;
  }

  // Insert a record in a database
  function dbInsert($table, $data=array()) {
    if (empty($table) || empty($data)){
      return false;
    }
    $conn = dbConn();
    $fields = implode(", ", array_keys($data));
    $values = implode("','", array_values($data));

    $sql = "INSERT INTO " . $table . " (" . $fields . ") VALUES ('" . $values . "')";

    $result = $conn->query($sql);
    if (!$result) {
      echo "Error Insert data: " . $conn->error;
      return false;
    }
    dbClose($conn);
    return true;
  }

  // Update 
  function dbUpdate($table, $data=array(), $criteria="") {
    if (empty($table) || empty($data) || empty($criteria)) {
      return false;
    }
    $fv = "";
    $conn = dbConn();
    foreach($data as $field=>$value) {
      $fv .= " " . $field . "='" . $value . "',";
    }
    $fv = substr($fv, 0, strlen($fv)-1);
    $sql = "UPDATE ". $table . " SET ". $fv . "WHERE " . $criteria;

    $result = $conn->query($sql);

    if (!$result) {
      echo "Error updated: " . $conn->error;
      return false;
    }
    dbConn($conn);
    return true;
  }

  // Delete a record from a database
  function dbDelete($table, $criteria) {
    if (empty($table) || empty($criteria)) {
      return false;
    } 
    $sql = "DELETE FROM " . $table . " WHERE " . $criteria;
    $conn = dbConn();
    $result = $conn->query($sql);
    if (!$result) {
      echo "Error deleted: " . $conn->error;
      return false;
    }
    dbClose($conn);
    return true;
  }

  // Count records in database
  function dbCount($table="", $criteria="") {
    if (empty($table)) {
      return false;
    }
    $sql = "SELECT * FROM " . $table;
    if (!empty($criteria)) {
      $sql .= " WHERE " . $criteria;
    }
    $conn = dbConn();
    $result = $conn->query($sql);
    $num = $result->num_rows;
    if ($num < 0) {
      echo "Error count: " . mysqli_error($conn);
      return false;
    }
    dbClose($conn);
    return $num;
  }
