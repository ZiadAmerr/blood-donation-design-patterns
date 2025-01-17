<?php
require "Volunteer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Volunteer.php";


class Workshop {
    private int $id;
    private Volunteer $instructor;
    private string $topic;

    public function __construct(int $id, Volunteer $instructor, string $topic) {
        $this->id = $id;
        $this->instructor = $instructor;
        $this->topic = $topic;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getInstructor(): Volunteer {
        return $this->instructor;
    }

    public function getTopic(): string {
        return $this->topic;
    }

    public function setInstructor(Volunteer $instructor): void {
        $this->instructor = $instructor;
    }

    public function setTopic(string $topic): void {
        $this->topic = $topic;
    }

    public function showDetails(): string {
        return "Workshop ID: {$this->id}, Instructor: {$this->instructor->getName()}, Topic: {$this->topic}";
    }

    // Save method for inserting or updating the workshop
    public function save(): bool {
        if ($this->id === 0) {
            // Insert new workshop into the database
            $sql = "INSERT INTO workshops (instructor_id, topic) VALUES (?, ?)";
            $stmt = Database::getInstance()->prepare($sql);
            $stmt->bind_param("is", $this->instructor->getId(), $this->topic);

            if ($stmt->execute()) {
                $this->id = Database::getInstance()->insert_id;  // Set the id after insertion
                return true;
            }
        } else {
            // Update existing workshop
            $sql = "UPDATE workshops SET instructor_id = ?, topic = ? WHERE id = ?";
            $stmt = Database::getInstance()->prepare($sql);
            $stmt->bind_param("isi", $this->instructor->getId(), $this->topic, $this->id);

            return $stmt->execute();
        }
        return false;
    }

    // Delete a workshop by ID
    public function delete(): bool {
        $sql = "DELETE FROM workshops WHERE id = ?";
        $stmt = Database::getInstance()->prepare($sql);
        $stmt->bind_param("i", $this->id);

        return $stmt->execute();
    }

    // Load a workshop by ID
    public static function loadById(int $id): ?Workshop {
        $sql = "SELECT * FROM workshops WHERE id = ?";
        $stmt = Database::getInstance()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            // Assuming you have a method to get the instructor by their ID
            $instructor = Volunteer::loadById($row['instructor_id']);  // Load instructor using their ID
            if ($instructor) {
                return new self($row['id'], $instructor, $row['topic']);
            }
        }
        return null;  // Return null if no workshop is found
    }
}
?>
