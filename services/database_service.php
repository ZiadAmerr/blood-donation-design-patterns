<?php
class Database {
    private static $instance = null;
    private $conn;

    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "sdp";

    private function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}

trait DatabaseTrait
{
    protected static $db;

    // Initialize the database connection
    public function initializeDatabase(): void
    {
        if (self::$db === null) {
            try {
                self::$db = Database::getInstance();
            } catch (Exception $e) {
                $this->logError("Failed to initialize database: " . $e->getMessage());
                // Optionally, handle the failure, such as by returning a default value or error code
            }
        }
    }

    // Log errors (you can replace this with a more sophisticated logging mechanism)
    private function logError(string $message): void
    {
        // This could be logging to a file or an external logging service
        error_log($message);
    }

    // Execute a query with parameters and return the result
    protected function executeQuery(string $sql, string $types = "", ...$params): ?mysqli_stmt
    {
        $this->initializeDatabase();

        if (self::$db === null) {
            $this->logError("Database connection not initialized.");
            return null;
        }

        $stmt = self::$db->prepare($sql);
        if (!$stmt) {
            $this->logError("Failed to prepare query: " . self::$db->error);
            return null;
        }

        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            $this->logError("Query execution failed: " . $stmt->error);
            return null;
        }

        return $stmt;
    }

    // Fetch a single record as an associative array
    protected function fetchSingle(string $sql, string $types = "", ...$params): ?array
    {
        $stmt = $this->executeQuery($sql, $types, ...$params);
        if ($stmt === null) {
            return null;
        }

        $result = $stmt->get_result();
        if ($result === false) {
            $this->logError("Failed to fetch result: " . $stmt->error);
            return null;
        }

        return $result->fetch_assoc() ?: null;
    }

    // Fetch multiple records as an array of associative arrays
    protected function fetchAll(string $sql, string $types = "", ...$params): array
    {
        $stmt = $this->executeQuery($sql, $types, ...$params);
        if ($stmt === null) {
            return [];
        }

        $result = $stmt->get_result();
        if ($result === false) {
            $this->logError("Failed to fetch result: " . $stmt->error);
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Run an update, insert, or delete query and return affected rows
    protected function executeUpdate(string $sql, string $types = "", ...$params): int
    {
        $stmt = $this->executeQuery($sql, $types, ...$params);
        if ($stmt === null) {
            return 0;
        }

        return $stmt->affected_rows;
    }
}

abstract class Model
{
    use DatabaseTrait;

    // Fetch a single record as an associative array
    protected static function fetchSingle(string $sql, string $types = "", ...$params): ?array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $db->error);
        }

        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    // Run an update, insert, or delete query and return affected rows
    protected static function executeUpdate(string $sql, string $types = "", ...$params): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $db->error);
        }

        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Failed to execute update: " . $stmt->error);
        }

        return $stmt->insert_id ?: $stmt->affected_rows;
    }
}


?>

