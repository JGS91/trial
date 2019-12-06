<?php

class propertiesTable
{
  private $servername = "localhost";
  private $username = "root";
  private $password = "root";
  private $conn;

  function __construct()
  {
    try {
      $this->conn = new PDO("mysql:host=$this->servername;dbname=myDB", $this->username, $this->password);
      // set the PDO error mode to exception
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  function getProperty()
  { }

  function getProperties()
  {
    $sql = "SELECT * FROM properties";
    $stmt = $this->conn->query($sql);
    return $stmt->fetchAll();
  }

  function addProperty($property)
  {
    $sql = "INSERT INTO properties (uuid, 
              county, 
              country, 
              town, 
              description, 
              displayable_address, 
              bedrooms, 
              bathrooms,
              price, 
              property_type, 
              type, 
              created_at, 
              updated_at) 
                  VALUES ( UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";

    //Need to add local url's for images.
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$property['county'], $property['country'], $property['town'], $property['description'], $property['displayableAddress'], $property['bedrooms'], $property['bathrooms'], $property['price'], $property['propertyType'], $property['type']]);
  }

  function editProperty()
  { }

  function deleteProperty($uuid)
  {
    $sql = "DELETE FROM properties WHERE uuid  = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$uuid]);
  }
}
