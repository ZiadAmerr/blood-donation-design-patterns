<?php

class PermanentlyIneligible implements DonorState {
    public function donate() {
        echo "You are permanently ineligible to donate.\n";
    }

    public function isValid(Donor $donor): bool {
        $diseases = $donor->getDiseases();
        foreach ($diseases as $disease)
            if (in_array($disease, Donor::$permanently_ineligible_diseases))
                return false;
        return true;
    }
}

class TemporarilyIneligible implements DonorState {
    public function donate() {
        echo "You are temporarily ineligible to donate. Please try again later.\n";
    }

    public function isValid(Donor $donor): bool {
        $donations = $donor->getDonations();
        $last_donation = end($donations);
        $time_since_last_donation = time() - $last_donation->getTimestamp();
        $days_since_last_donation = $time_since_last_donation / 60 / 60 / 24;
        return $days_since_last_donation >= 56; // 8 weeks
    }
}

class Eligible implements DonorState {
    public function donate() {
        echo "Donation successful. Thank you for your contribution!\n";
    }

    public function isValid(Donor $donor): bool {
        return true;
    }
}

