<?php

class Certificate {
    public int $certificateID;
    public string $volunteerName;
    public string $eventName;
    public int $hoursContributed;
    public string $issueDate;

    public function __construct(int $certificateID, string $volunteerName, string $eventName, int $hoursContributed, string $issueDate) {
        $this->certificateID = $certificateID;
        $this->volunteerName = $volunteerName;
        $this->eventName = $eventName;
        $this->hoursContributed = $hoursContributed;
        $this->issueDate = $issueDate;
    }

    public function generateCertificate(): string {
        return "Certificate ID: {$this->certificateID}, Volunteer: {$this->volunteerName}, Event: {$this->eventName}, Hours Contributed: {$this->hoursContributed}, Issue Date: {$this->issueDate}";
    }
}
