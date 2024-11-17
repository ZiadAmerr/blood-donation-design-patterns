package com.sdp.project.services;

import com.sdp.project.models.volunteers.Skill;
import com.sdp.project.models.volunteers.Volunteer;
import com.sdp.project.models.volunteers.VolunteerSkill;
import com.sdp.project.repositories.SkillRepository;
import com.sdp.project.repositories.VolunteerRepository;
import com.sdp.project.repositories.VolunteerSkillRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class VolunteerService {

    @Autowired
    private VolunteerRepository volunteerRepository;

    @Autowired
    private SkillRepository skillRepository;

    @Autowired
    private VolunteerSkillRepository volunteerSkillRepository;

    public List<Volunteer> getAllVolunteers() {
        return (List<Volunteer>) volunteerRepository.findAll();
    }

    public void addVolunteer(String name) {
        Volunteer volunteer = new Volunteer();
        volunteer.setName(name);
        volunteerRepository.save(volunteer);
    }

    public void addSkill(Integer volunteerId, String skillName) {
        // Find or create the skill
        Skill skill = skillRepository.findByName(skillName);
        if (skill == null) {
            skill = new Skill();
            skill.setName(skillName);
            skillRepository.save(skill);
        }

        // Create a new VolunteerSkill to map the skill to the volunteer
        VolunteerSkill volunteerSkill = new VolunteerSkill();
        volunteerSkill.setVolunteerId(volunteerId);
        volunteerSkill.setSkillId(skill.getId());
        volunteerSkillRepository.save(volunteerSkill);
    }
}
