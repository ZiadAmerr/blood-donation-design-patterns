package com.sdp.project.models.volunteers;

import com.sdp.project.services.VolunteerService;

public class DrivingDecorator extends SkillsDecorator {

    public DrivingDecorator(IVolunteer decoratedVolunteer) {
        super(decoratedVolunteer);
    }

    @Override
    public void addSkill(String name) {
        super.addSkill("Driving");
    }
}
