package com.blooddonation.system.models.skills;

import com.blooddonation.system.models.people.IVolunteer;

import java.util.List;

public abstract class SkillsDecorator implements IVolunteer {
    protected IVolunteer volunteer;

    public SkillsDecorator(IVolunteer volunteer) {
        this.volunteer = volunteer;
    }

    @Override
    public List<String> skill() {
        return volunteer.skill();
    }
}
