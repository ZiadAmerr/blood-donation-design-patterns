package com.sdp.project.repositories;

import com.sdp.project.models.volunteers.Skill;
import org.springframework.data.repository.CrudRepository;

import java.util.List;

public interface SkillRepository extends CrudRepository<Skill, Integer> {
    Skill findByName(String name);
}

