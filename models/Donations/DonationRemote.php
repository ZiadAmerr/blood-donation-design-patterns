<?php

class DonationRemote {

    protected $command;
    public $donor;

    public function __construct(Donor $donor) {
        $this->donor = $donor;
    }

    public static function create(Donor $donor): DonationRemote {
        return new DonationRemote($donor);
    }

    public function setCommand(Command $command): bool {
        $this->command = $command;
        return true;
    }

    public function execute(DonationFacade $receiver, Donor $donor, Donation $donation = null): bool {
        if ($this->command) {
            return $this->command->execute($receiver, $donor, $donation);
        }
        return false;
    }
}

?>
