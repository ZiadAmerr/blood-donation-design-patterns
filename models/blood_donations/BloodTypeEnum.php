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

    public static function values(): array {
        return array_column(BloodTypeEnum::cases(), 'value');
    }

    public static function fromString(string $value): ?BloodTypeEnum {
        foreach (BloodTypeEnum::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }
        return null; // Return null if the value is not found
    }
    public static function getAllValues(): array {
        return self::values(); // Simply return the existing values() function
    }
}

    


?>