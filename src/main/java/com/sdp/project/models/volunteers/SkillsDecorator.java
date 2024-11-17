package com.sdp.project.models.volunteers;

public abstract class SkillsDecorator implements IVolunteer {
    protected IVolunteer decoratedVolunteer;

    public SkillsDecorator(IVolunteer decoratedVolunteer) {
        this.decoratedVolunteer = decoratedVolunteer;
    }

    @Override
    public void addSkill(String name) {
        decoratedVolunteer.addSkill(name);
    }
}
