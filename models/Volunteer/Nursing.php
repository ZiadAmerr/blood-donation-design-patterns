<?php


class Nursing extends SkillsDecorator {
    public function addSkill(string $skill): void {
        parent::addSkill("Nursing: $skill");
    }
}


?>