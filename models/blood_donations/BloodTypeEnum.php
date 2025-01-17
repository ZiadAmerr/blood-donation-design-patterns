<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

enum BloodTypeEnum: string {
    case AB_POSITIVE = 'AB+';
    case AB_NEGATIVE = 'AB-';
    case A_POSITIVE = 'A+';
    case A_NEGATIVE = 'A-';
    case B_POSITIVE = 'B+';
    case B_NEGATIVE = 'B-';
    case O_POSITIVE = 'O+';
    case O_NEGATIVE = 'O-';

    // Returns an array of all blood type values
    public static function values(): array {
        return array_column(self::cases(), 'value');
    }

    // Converts enum to string
    public function toString(): string {
        return $this->value;
    }

    // Converts string to enum
    public static function fromString(string $blood_type): self {
        return self::tryFrom($blood_type) ?? throw new Exception("Invalid blood type: $blood_type");
    }
}
