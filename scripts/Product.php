<?php

class Products
{
  public $db = null;

  public function __construct(DBController $db)
  {
    if (!isset($db->con)) {
      $this->db = null;
    } else {
      $this->db = $db;
    }
  }

  // fetch product data using getData Method
  public function getData($table = 'products')
  {
    $result = $this->db->con->query("SELECT * FROM {$table}");

    $resultArray = [];

    // fetch product data one by one
    while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $resultArray[] = $item;
    }

    return $resultArray;
  }

  // get product using item id
  public function getProduct($item_id = null, $table = 'products')
  {
    if (isset($item_id)) {
      // Prepare statement to prevent SQL injection
      $stmt = $this->db->con->prepare("SELECT * FROM {$table} WHERE id = ?");
      $stmt->bind_param("i", $item_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        // fetch product data
        return $result->fetch_assoc();
      } else {
        // No product found
        return null;
      }
    }

    return null;
  }

  // add a new product
  public function addProduct($params = [], $table = 'products')
  {
    if (!empty($params)) {
      // Extract keys and values from the params array
      $columns = implode(', ', array_keys($params));
      $placeholders = implode(', ', array_fill(0, count($params), '?'));
      $values = array_values($params);

      // Prepare statement to prevent SQL injection
      $stmt = $this->db->con->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})");

      // Create a dynamic call to bind_param
      $types = str_repeat('s', count($values)); // assuming all fields are strings
      $stmt->bind_param($types, ...$values);

      // Execute the statement
      $stmt->execute();
    }
  }

  // update a product
  public function updateProduct($item_id = null, $params = [], $table = 'products')
  {
    if (isset($item_id) && !empty($params)) {
      $setClause = '';
      $values = [];

      foreach ($params as $key => $value) {
        $setClause .= "{$key} = ?, ";
        $values[] = $value;
      }

      // Add updated_at to params
      $setClause .= "updated_at = CURRENT_TIMESTAMP, ";
      $values[] = $item_id;

      $setClause = rtrim($setClause, ', ');

      // Prepare statement to prevent SQL injection
      $stmt = $this->db->con->prepare("UPDATE {$table} SET {$setClause} WHERE id = ?");

      // Create a dynamic call to bind_param
      $types = str_repeat('s', count($values) - 1) . 'i'; // assuming all fields are strings except id
      $stmt->bind_param($types, ...$values);

      // Execute the statement
      $stmt->execute();
    }
  }


  // delete a product
  public function deleteProduct($item_id = null, $table = 'products')
  {
    if (isset($item_id)) {
      // Remove the image too
      $product = $this->getProduct($item_id);
      $image = $product['image'];
      unlink('assets/' . $image);

      // Prepare statement to prevent SQL injection
      $stmt = $this->db->con->prepare("DELETE FROM {$table} WHERE id = ?");
      $stmt->bind_param("i", $item_id);
      $stmt->execute();
      return $stmt->affected_rows;
    }

    return null;
  }

  // search product
  public function searchProduct($search = null, $table = 'products')
  {
    if (isset($search)) {
      // Prepare statement to prevent SQL injection
      $stmt = $this->db->con->prepare("SELECT * FROM {$table} WHERE name LIKE ?");
      $search = "%$search%";
      $stmt->bind_param("s", $search);
      $stmt->execute();
      $result = $stmt->get_result();

      $resultArray = [];

      // fetch product data one by one
      while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $resultArray[] = $item;
      }

      return $resultArray;
    }

    return null;
  }
}
