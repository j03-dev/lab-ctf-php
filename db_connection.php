<?php
class SqliteConnection {
    private $pdo;

    public function connect() {
        $PATH_TO_SQLITE_FILE = "sqlite:db.sqlite";
        if ($this->pdo == null) {
            $this->pdo = new PDO($PATH_TO_SQLITE_FILE);
        }
        return $this->pdo;
    }
}
?>
