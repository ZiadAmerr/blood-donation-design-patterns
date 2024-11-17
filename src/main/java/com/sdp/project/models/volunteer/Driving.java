package com.sdp.project.models.volunteer;

public class Driving extends SkillsDecorator {
    public Driving(IVolunteer volunteer) {
        super(volunteer);
    }

    @Override
    public void addSkill(String skill) {
        super.addSkill("Driving: " + skill);
    }
}