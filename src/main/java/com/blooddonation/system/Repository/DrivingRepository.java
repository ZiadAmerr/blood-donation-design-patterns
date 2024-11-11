package com.blooddonation.system.Repository;

import com.blooddonation.system.models.skills.Driving;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface DrivingRepository extends JpaRepository<Driving, Long> {
}
