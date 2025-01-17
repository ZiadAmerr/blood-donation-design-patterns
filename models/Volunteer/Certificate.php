<?php

class Certificate {
    private static int $nextCertificateID = 1;
    public string $certificateID;
    public string $volunteerName;
    public string $eventName;
    public int $hoursContributed;
    public string $issueDate;

    public function __construct(string $volunteerName, string $eventName, int $hoursContributed, string $issueDate) {
        $this->certificateID = self::$nextCertificateID++;
        $this->volunteerName = $volunteerName;
        $this->eventName = $eventName;
        $this->hoursContributed = $hoursContributed;
        $this->issueDate = $issueDate;
    }

    // Generate a certificate string
    public function generateCertificate(): string {
        return "Certificate ID: {$this->certificateID}, Volunteer: {$this->volunteerName}, Event: {$this->eventName}, Hours: {$this->hoursContributed}, Issue Date: {$this->issueDate}";
    }

    // Add a certificate to the database
    public function add(): void {
        $this->add("Certificates", [
            "certificateID" => $this->certificateID,
            "volunteerName" => $this->volunteerName,
            "eventName" => $this->eventName,
            "hoursContributed" => $this->hoursContributed,
            "issueDate" => $this->issueDate
        ]);
    }

    // Update a certificate in the database
    public function update(): void {
        $this->update("Certificates", [
            "volunteerName" => $this->volunteerName,
            "eventName" => $this->eventName,
            "hoursContributed" => $this->hoursContributed,
            "issueDate" => $this->issueDate
        ], "certificateID = ?", [$this->certificateID]);
    }

    // Delete a certificate from the database
    public function delete(): void {
        $this->delete("Certificates", "certificateID = ?", [$this->certificateID]);
    }
}
?>