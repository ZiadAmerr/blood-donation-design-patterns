<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

class BloodDonation extends Donation{
    // donation id should be removed imo (or should be db generated..not entered by a user)
    private Donor $donor;
    private int $numberOfLiters;
    private BloodTypeEnum $bloodType;

    public function __construct($donor, $numberOfLiters, $bloodType)
    {
        $this->donor = $donor;
        $this->numberOfLiters = $numberOfLiters;
        $this->bloodType = $bloodType;
        
    }

    public function increaseBloodStock($bloodStock): bool
    {
        // increase the blood stock in the singelton instance
        return true;
    }

    
}
?>