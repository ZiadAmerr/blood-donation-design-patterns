package com.sdp.project.repositories;

import com.sdp.project.models.volunteers.VolunteerSkill;
import org.springframework.data.repository.CrudRepository;

public interface VolunteerSkillRepository extends CrudRepository<VolunteerSkill, Integer> {
    // Custom methods for adding/removing skills
}
