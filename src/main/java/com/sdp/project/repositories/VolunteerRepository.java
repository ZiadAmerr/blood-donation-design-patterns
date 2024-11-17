package com.sdp.project.repositories;

import com.sdp.project.models.volunteers.Volunteer;
import org.springframework.data.repository.CrudRepository;

public interface VolunteerRepository extends CrudRepository<Volunteer, Integer> {
}

