<?php

try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=sessioncmp', 'homestead', 'secret');
} catch (PDOException $e) {
    die('Failed');
}

class DatabaseSessionHandler implements SessionHandlerInterface
{
    public function read($id)
    {
        # code...
    }
    public function write($id, $data)
    {
        # code...
    }
    public function open($path, $name)
    {
        # code...
    }
    public function close()
    {
        # code...
    }
    public function destroy($id)
    {
        # code...
    }
    public function gc($maxlifetime )
    {
        # code...
    }
}

session_set_save_handler(new DatabaseSessionHandler);
session_start();

$_SESSION['name'] = 'Name';
