<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

enum BloodTypeEnum {
    const AB_POSITIVE = 'AB+';
    const AB_NEGATIVE = 'AB-';
    const A_POSITIVE = 'A+';
    const A_NEGATIVE = 'A-';
    const B_POSITIVE = 'B+';
    const B_NEGATIVE = 'B-';
    const O_POSITIVE = 'O+';
    const O_NEGATIVE = 'O-';

    public static function values(): array {
        return [
            self::AB_POSITIVE,
            self::AB_NEGATIVE,
            self::A_POSITIVE,
            self::A_NEGATIVE,
            self::B_POSITIVE,
            self::B_NEGATIVE,
            self::O_POSITIVE,
            self::O_NEGATIVE,
        ];
    }
}

?>