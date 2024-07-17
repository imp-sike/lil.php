<?php

namespace Lil;

use App\Config\ConfigClass;

class Model
{

    protected $table;
    protected $fillable;

    private $db;

    public function __construct()
    {
        $this->db = new \mysqli(ConfigClass::$db_host, ConfigClass::$db_uname, ConfigClass::$db_pass, ConfigClass::$db_name);
        // connection error
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function all()
    {
        // SELECT * FROM db_name
        $sql = "SELECT * FROM " . $this->table;
        $result = $this->db->query($sql);
        $results_array = []; // Initialize the array

        if ($result->num_rows > 0) {
            // Fetch all results into an associative array
            while ($row = $result->fetch_assoc()) {
                $results_array[] = $row;
            }
        }

        return $results_array;
    }

    public function insert($data)
    {
        $columns = implode(', ', $this->fillable);
        $placeholders = implode(', ', array_fill(0, count($this->fillable), '?'));

        // Prepare the SQL statement
        $sql = "INSERT INTO " . $this->table . " ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);

        if ($stmt === false) {
            // Handle prepare error, if any
            die('MySQL prepare error: ' . $this->db->error);
        }

        // Dynamically bind parameters
        $types = str_repeat('s', count($this->fillable)); // Assuming all parameters are strings, adjust as needed
        $bindParams = [];
        foreach ($this->fillable as $column) {
            $bindParams[] = $data[$column];
        }
        $stmt->bind_param($types, ...$bindParams);

        // Execute the statement
        $result = $stmt->execute();

        // Check for errors
        if ($result === false) {
            // Handle execute error, if any
            die('MySQL execute error: ' . $stmt->error);
        }

        // Close statement
        $stmt->close();

        return $result;
    }


    public function update($id, $data)
    {
        $setClause = "";
        $params = [];

        foreach ($this->fillable as $index => $fillable) {
            if (isset($data[$fillable])) {
                $setClause .= ($index > 0 ? ', ' : '') . "$fillable=?";
                $params[] = $data[$fillable];
            }
        }

        // Add the ID as the last parameter for the WHERE clause
        $params[] = $id;

        // Prepare the SQL statement
        $sql = "UPDATE " . $this->table . " SET " . $setClause . " WHERE id=?";
        $stmt = $this->db->prepare($sql);

        if ($stmt === false) {
            // Handle prepare error, if any
            die('MySQL prepare error: ' . $this->db->error);
        }

        // Dynamically bind parameters
        $types = str_repeat('s', count($params)); // Assuming all parameters are strings, adjust as needed
        $stmt->bind_param($types, ...$params);

        // Execute the statement
        $result = $stmt->execute();

        // Check for errors
        if ($result === false) {
            // Handle execute error, if any
            die('MySQL execute error: ' . $stmt->error);
        }

        // Close statement
        $stmt->close();

        return $result;
    }


    public function withId($id)
    {
        // Assuming $this->db is your MySQLi database connection
        $sql = "SELECT * FROM " . $this->table . " WHERE id=?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            // Handle prepare error, if any
            die('MySQL prepare error: ' . $this->db->error);
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $result->free_result();
        return $data; // Return fetched data or null if not found
    }

    public function delete($id)
    {
        // Assuming $this->db is your MySQLi database connection
        $sql = "DELETE FROM " . $this->table . " WHERE id=?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            // Handle prepare error, if any
            die('MySQL prepare error: ' . $this->db->error);
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();

        // Check if deletion was successful
        if ($stmt->affected_rows > 0) {
            return true; // Return true on successful deletion
        } else {
            return false; // Return false if no rows were affected (no deletion occurred)
        }
    }


    public function where($conditions)
    {
        $whereClause = [];
        $params = [];
        $types = '';

        foreach ($conditions as $column => $value) {
            $whereClause[] = "$column=?";
            $params[] = $value;
            $types .= is_int($value) ? 'i' : 's'; // Adjust types based on data
        }

        $sql = "SELECT * FROM " . $this->table . " WHERE " . implode(' AND ', $whereClause);
        $stmt = $this->db->prepare($sql);

        if ($stmt === false) {
            // Handle prepare error, if any
            die('MySQL prepare error: ' . $this->db->error);
        }

        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $result = $stmt->get_result();
        $results_array = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $results_array[] = $row;
            }
        }

        $stmt->close();

        return $results_array;
    }

}