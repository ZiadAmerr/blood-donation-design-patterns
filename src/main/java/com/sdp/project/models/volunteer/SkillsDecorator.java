package com.sdp.project.models.volunteer;

public abstract class SkillsDecorator implements IVolunteer {
    protected IVolunteer volunteer;

    public SkillsDecorator(IVolunteer volunteer) {
        this.volunteer = volunteer;
    }

    @Override
    public void addSkill(String skill) {
        volunteer.addSkill(skill);
    }
}