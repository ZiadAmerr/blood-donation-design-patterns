<?php


abstract class SkillsDecorator implements IVolunteer {
    protected IVolunteer $volunteer;

    public function __construct(IVolunteer $volunteer) {
        $this->volunteer = $volunteer;
    }

    public function addSkill(string $skill): void {
        $this->volunteer->addSkill($skill);
    }
}


?>