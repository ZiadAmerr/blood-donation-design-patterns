<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

class Organization extends Model
{
    private int $id;
    private string $name;
    private string $address;
    private string $contactNumber;
    private string $email;
    private ?string $website;

    public function __construct(int $id, string $name, string $address, string $contactNumber, string $email, ?string $website = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->contactNumber = $contactNumber;
        $this->email = $email;
        $this->website = $website;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getContactNumber(): string
    {
        return $this->contactNumber;
    }

    public function setContactNumber(string $contactNumber): void
    {
        $this->contactNumber = $contactNumber;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): void
    {
        $this->website = $website;
    }

    // Save method for inserting or updating the organization
    public function save(): bool
    {
        if ($this->id === 0) {
            // Insert new organization into the database
            $sql = "INSERT INTO Organizations (name, address, contact_number, email, website) VALUES (?, ?, ?, ?, ?)";
            $stmt = Database::getInstance()->prepare($sql);
            $stmt->bind_param("sssss", $this->name, $this->address, $this->contactNumber, $this->email, $this->website);

            if ($stmt->execute()) {
                $this->id = Database::getInstance()->insert_id;  // Set the organization ID after insertion
                return true;
            }
        } else {
            // Update existing organization
            $sql = "UPDATE Organizations SET name = ?, address = ?, contact_number = ?, email = ?, website = ? WHERE id = ?";
            $stmt = Database::getInstance()->prepare($sql);
            $stmt->bind_param("sssssi", $this->name, $this->address, $this->contactNumber, $this->email, $this->website, $this->id);

            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    public static function create(string $name, string $address, string $contactNumber, string $email, ?string $website): Organization
    {
        $organization = new self(0, $name, $address, $contactNumber, $email, $website);
        $organization->save();
        return $organization;
    }

    public static function read(int $id): ?Organization
    {
        $data = self::fetchSingle(
            "SELECT * FROM Organizations WHERE id = ?",
            "i",
            $id
        );

        if (!$data) {
            return null;  
        }

        return new Organization(
            $data['id'],
            $data['name'],
            $data['address'],
            $data['contact_number'],
            $data['email'],
            $data['website'] ?? null  
        );
    }

    public function update(string $name, string $address, string $contactNumber, string $email, ?string $website): void
    {
        $this->name = $name;
        $this->address = $address;
        $this->contactNumber = $contactNumber;
        $this->email = $email;
        $this->website = $website;

        $this->save();
    }

    // Load all organizations
    public static function load(): array
    {
        $data = self::fetchAll(
            "SELECT * FROM Organizations"
        );

        if (!$data) {
            throw new Exception("No organizations found.");
        }

        $organizations = [];
        foreach ($data as $organizationData) {
            $organizations[] = new Organization(
                $organizationData['id'],
                $organizationData['name'],
                $organizationData['address'],
                $organizationData['contact_number'],
                $organizationData['email'],
                $organizationData['website'] ?? null
            );
        }

        return $organizations;
    }

    public function delete(): void
    {
        self::executeUpdate(
            "DELETE FROM Organizations WHERE id = ?",
            "i",
            $this->id
        );
    }
}
?>
