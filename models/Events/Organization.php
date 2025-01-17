<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

class Organization extends Model
{
    private $id;
    private $name;
    private $address;
    private $contactNumber;
    private $email;
    private $website;

    public function __construct($id, $name, $address, $contactNumber, $email, $website)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->contactNumber = $contactNumber;
        $this->email = $email;
        $this->website = $website;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public static function create(string $name, string $address, string $contactNumber, string $email, ?string $website): Organization
    {
        $id = self::executeUpdate(
            "INSERT INTO Organizations (name, address, contact_number, email, website) VALUES (?, ?, ?, ?, ?)",
            "sssss",
            $name,
            $address,
            $contactNumber,
            $email,
            $website
        );

        return new Organization($id, $name, $address, $contactNumber, $email, $website);
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
            $data['website']
        );
    }

    public function update(string $name, string $address, string $contactNumber, string $email, ?string $website): void
    {
        self::executeUpdate(
            "UPDATE Organizations SET name = ?, address = ?, contact_number = ?, email = ?, website = ? WHERE id = ?",
            "sssssi",
            $name,
            $address,
            $contactNumber,
            $email,
            $website,
            $this->id
        );

        $this->name = $name;
        $this->address = $address;
        $this->contactNumber = $contactNumber;
        $this->email = $email;
        $this->website = $website;
    }

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
                $organizationData['website']
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
