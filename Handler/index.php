<?php

try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=sessioncmp', 'homestead', 'secret');
} catch (PDOException $e) {
    die('Failed');
}

class DatabaseSessionHandler implements SessionHandlerInterface
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function read($id)
    {
        $statement = $this->db->preapre("
            SELECT data FROM sessions
            WHERE id = :id
        ");

        $statement->execute(['id' => $id]);

        if ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            return $row->data;
        }
        return '';
    }

    public function write($id, $data)
    {
        $statement = $this->db->prepare("
            REPLACE INTO sessions VALUES (:id, :timestamp, :data)
        ");

        $insert = $statement->execute([
            'id' => $id,
            'timestamp' => time(),
            'data' => $data
        ]);

        return ($insert) ? true : false;
    }

    public function open($path, $name)
    {
        return ($this->db) ? true : false;
    }

    public function close()
    {
        $this->db = null;

        return ($this->db === null) ? true : false;
    }

    public function destroy($id)
    {
        $statement = $this->db->prepare(["
            DELETE FROM sessions WHERE id = :id
        "]);

        $delete = $statement->execute(['id' => $id]);

        return ($delete) ? true : false;
    }

    public function gc($maxlifetime)
    {
        $limit = time() - $maxlifetime;

        $statement = $this->db->prepare("
            DELETE FROM sessions WHERE access < :limit
        ");

        $delete = $statement->execute(['limit' => $limit]);

        return ($delete) ? true : false;
    }
}

session_set_save_handler(new DatabaseSessionHandler($db));

session_start();

$_SESSION['name'] = 'Name';

echo $_SESSION['name'];
