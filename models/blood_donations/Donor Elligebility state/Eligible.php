<?php

require_once 'DonorState.php';

class Eligible implements DonorState {
    public function donate() {
        echo "You are eligible to donate blood. Proceeding with the donation.\n";
    }
}

?>