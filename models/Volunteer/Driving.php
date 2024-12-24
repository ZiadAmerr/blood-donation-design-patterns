<?php


class Driving extends SkillsDecorator {
    public function addSkill(string $skill): void {
        parent::addSkill("Driving: $skill");
    }
}


?>