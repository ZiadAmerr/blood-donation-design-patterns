package com.sdp.project.models.volunteer;

public class Nursing extends SkillsDecorator {
    public Nursing(IVolunteer volunteer) {
        super(volunteer);
    }

    @Override
    public void addSkill(String skill) {
        super.addSkill("Nursing: " + skill);
    }
}