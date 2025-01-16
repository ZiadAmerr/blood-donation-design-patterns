<?php

require_once 'DonorState.php';

class PermanentlyIneligible implements DonorState {
    public function can_donate(): bool {
        echo "You are permanently ineligible to donate blood. Thank you for your willingness to help.\n";
    }
}

?>