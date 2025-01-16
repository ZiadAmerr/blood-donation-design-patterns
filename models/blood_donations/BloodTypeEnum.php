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
}

?>