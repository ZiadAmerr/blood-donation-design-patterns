<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Disease.php";

class DiseaseService extends Model {
    public static function getAllDiseases(): array {
        return Disease::getAllDiseases();
    }
}