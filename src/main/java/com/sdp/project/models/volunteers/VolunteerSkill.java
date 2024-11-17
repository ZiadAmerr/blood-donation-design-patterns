package com.sdp.project.models.volunteers;

import org.springframework.data.relational.core.mapping.Table;

@Table("VOLUNTEER_SKILLS")
public class VolunteerSkill {

    private Integer volunteerId;
    private Integer skillId;

    public Integer getVolunteerId() {
        return volunteerId;
    }

    public void setVolunteerId(Integer volunteerId) {
        this.volunteerId = volunteerId;
    }

    public Integer getSkillId() {
        return skillId;
    }

    public void setSkillId(Integer skillId) {
        this.skillId = skillId;
    }
}
