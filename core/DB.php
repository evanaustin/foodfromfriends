<?php

class DB extends PDO {
    
    private
        $sql,
        $bind,
        $error;
    
    public function __construct($dsn, $user = '', $password = '') {
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION
        ];

        try {
            parent::__construct($dsn, $user, $password, $options);
        }
        catch (PDOException $e) {
            if (!isset($e) || !is_object($e)) {
                $e = new stdClass();
            }
            $this->error = $e->getMessage();
        }
    }
    
    private function filter($table, $info) {
		$driver = $this->getAttribute(PDO::ATTR_DRIVER_NAME);

		if ($driver == 'sqlite') {
			$sql = "PRAGMA table_info('{$table}');";
			$key = 'name';
		}

		else if ($driver == 'mysql') {
			$sql = "DESCRIBE {$table};";
			$key = 'Field';
		}

		else {
			$sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '{$table}';";
			$key = 'column_name';
		}

		if (false !== ($list = $this->run($sql))) {
			$fields = [];

			foreach($list as $record) {
				$fields[] = $record[$key];
			}

			return array_values(array_intersect($fields, array_keys($info)));
		}

		return [];
	}
    
    private function cleanup($bind) {
		if (!is_array($bind)) {
			if (!empty($bind)) {
				$bind = [$bind];
			} else {
				$bind = [];
			}
		}
		return $bind;
	}

    public function insert($table, $info) {
		$fields = $this->filter($table, $info);
		$sql    = "INSERT INTO {$table} (" . implode($fields, ', ') . ") VALUES (:" . implode($fields, ', :') . ");";
		$bind   = [];

		foreach($fields as $field) {
			$bind[":{$field}"] = $info[$field];
		}
		
		return $this->run($sql, $bind);
	}

    public function select($table, $where, $bind='') {
		$sql = "SELECT * FROM {$table} WHERE {$where};";
		return $this->run($sql, $bind);
	}
    
    public function delete($table, $where, $bind='') {
		$sql = "DELETE FROM {$table} WHERE {$where};";
		return $this->run($sql, $bind);
	}
    
    public function update($table, $info, $where, $bind='') {
		$fields    = $this->filter($table, $info);
		$fieldSize = sizeof($fields);

		$sql = "UPDATE {$table} SET ";

		for($f = 0; $f < $fieldSize; ++$f) {
			if($f > 0) {
				$sql .= ', ';
			}

			$sql .= $fields[$f] . " = :update_" . $fields[$f];
		}

		$sql .= " WHERE {$where};";

		$bind = $this->cleanup($bind);

		foreach($fields as $field) {
			$bind[":update_{$field}"] = $info[$field];
		}

		return $this->run($sql, $bind);
	}

    public function run($sql, $bind='') {
		$this->sql   = trim($sql);
		$this->bind  = $this->cleanup($bind);
		$this->error = '';

		try {
			$pdostmt = $this->prepare($this->sql);

			if ($pdostmt->execute($this->bind) !== false) {
				if (preg_match("/^(" . implode('|', ['select', 'describe', 'pragma']) . ") /i", $this->sql)) {
					return $pdostmt->fetchAll(PDO::FETCH_ASSOC);
				}

				else if (preg_match("/^(" . implode('|', ['delete', 'insert', 'update']) . ") /i", $this->sql)) {
					return [
						'row_count'      => $pdostmt->rowCount(),
						'last_insert_id' => $this->lastInsertId()
					];
				}
			}
		}
		catch (PDOException $e) {
			if (!isset($e) || !is_object($e)) {
				$e = new stdClass();
			}
			$this->error = $e->getMessage();
			return false;
		}
	}

}

?>