package com.sdp.project.models.volunteers;

import com.sdp.project.services.VolunteerService;

public class NursingDecorator extends SkillsDecorator {

    public NursingDecorator(IVolunteer decoratedVolunteer) {
        super(decoratedVolunteer);
    }

    @Override
    public void addSkill(String name) {
        super.addSkill("Nursing");
    }
}


