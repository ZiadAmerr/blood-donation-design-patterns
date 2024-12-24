<?php

class DonationController {

    protected $command;
    protected $donor;

    public function __construct(Donor $donor) {
        $this->donor = $donor;
    }

    public function setCommand(Command $command): bool {
        $this->command = $command;
        return true;
    }

    public function execute(): bool {
        if ($this->command) {
            return $this->command->execute(new DonationFacade($this->donor), $this->donor);
        }
        return false;
    }
}

?>
