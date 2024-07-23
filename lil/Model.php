<?php

namespace Lil;

use App\Config\ConfigClass;

class Model
{
    protected $table;
    protected $columns = [];
    private $db;
    protected $id;

    protected $query;
    protected $bindings = [];
    protected $types = '';

    public function __construct()
    {
        $this->db = new \mysqli(ConfigClass::$db_host, ConfigClass::$db_uname, ConfigClass::$db_pass, ConfigClass::$db_name);

        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }

        $this->query = "SELECT * FROM {$this->table}";
    }

    // Create a new record
    public function create(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

        $stmt = $this->db->prepare($sql);
        $types = str_repeat('s', count($data)); // Assuming all data is of type string
        $values = array_values($data);
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    // Read a record by primary key
    public static function read($id)
    {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE id = ?";
        $stmt = $instance->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $instance->id = $id;
        $stmt->execute();
        return $stmt->get_result()->fetch_object(static::class);
    }

    // Update a record by primary key
    public function update($id, array $data)
    {
        $sets = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE {$this->table} SET $sets WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $types = str_repeat('s', count($data)) . 'i'; // Adding 'i' for integer id
        $values = array_values($data);
        $values[] = $id; // Append id to values
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    // Delete a record by primary key
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // Retrieve all records
    public static function all()
    {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table}";
        $result = $instance->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add a WHERE clause
    public function where($column, $operator, $value = null)
    {
        if (is_null($value) && ($operator === 'IS NULL' || $operator === 'IS NOT NULL')) {
            $this->query .= " WHERE $column $operator";
        } else {
            $this->query .= " WHERE $column $operator ?";
            $this->bindings[] = $value;
            $this->types .= 's'; // Assuming all data is of type string
        }
        return $this;
    }

    // Add an ORDER BY clause
    public function orderBy($column, $direction = 'ASC')
    {
        $this->query .= " ORDER BY $column $direction";
        return $this;
    }

    // Limit the number of results
    public function limit($number)
    {
        $this->query .= " LIMIT ?";
        $this->bindings[] = $number;
        $this->types .= 'i';
        return $this;
    }

    // Offset the results
    public function offset($number)
    {
        $this->query .= " OFFSET ?";
        $this->bindings[] = $number;
        $this->types .= 'i';
        return $this;
    }

    // Group results by a column
    public function groupBy($column)
    {
        $this->query .= " GROUP BY $column";
        return $this;
    }

    // Execute the built query
    public function get()
    {
        $stmt = $this->db->prepare($this->query);
        if ($stmt === false) {
            throw new \Exception("Unable to prepare statement: " . $this->db->error);
        }

        if (!empty($this->bindings)) {
            $stmt->bind_param($this->types, ...$this->bindings);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Aggregation methods
    public function count()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $result = $this->db->query($sql);
        return $result->fetch_assoc()['count'];
    }

    public function sum($column)
    {
        $sql = "SELECT SUM($column) as sum FROM {$this->table}";
        $result = $this->db->query($sql);
        return $result->fetch_assoc()['sum'];
    }

    public function avg($column)
    {
        $sql = "SELECT AVG($column) as avg FROM {$this->table}";
        $result = $this->db->query($sql);
        return $result->fetch_assoc()['avg'];
    }

    public function min($column)
    {
        $sql = "SELECT MIN($column) as min FROM {$this->table}";
        $result = $this->db->query($sql);
        return $result->fetch_assoc()['min'];
    }

    public function max($column)
    {
        $sql = "SELECT MAX($column) as max FROM {$this->table}";
        $result = $this->db->query($sql);
        return $result->fetch_assoc()['max'];
    }

    // Other utility methods
    public function toArray()
    {
        return get_object_vars($this);
    }

    public function save()
    {
        if (isset($this->id)) {
            return $this->update($this->id, $this->toArray());
        } else {
            return $this->create($this->toArray());
        }
    }

    public function belongsTo($relatedModel, $foreignKey = 'user_id')
    {
        $relatedModelInstance = new $relatedModel();
        $sql = "SELECT * FROM {$relatedModelInstance->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $this->{$foreignKey});
        $stmt->execute();
        return $stmt->get_result()->fetch_object($relatedModel);
    }

    public function hasMany($relatedModel, $foreignKey = 'user_id')
    {
        $relatedModelInstance = new $relatedModel();
        $sql = "SELECT * FROM {$relatedModelInstance->table} WHERE {$foreignKey} = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Execute custom prepared queries
    public function query($sql, $params = [], $types = '')
    {
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new \Exception("Unable to prepare statement: " . $this->db->error);
        }

        if (!empty($params)) {
            if (empty($types)) {
                // Default to assuming all params are strings if no types are specified
                $types = str_repeat('s', count($params));
            }
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
