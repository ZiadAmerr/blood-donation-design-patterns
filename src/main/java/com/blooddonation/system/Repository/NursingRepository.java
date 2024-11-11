package com.blooddonation.system.Repository;

import com.blooddonation.system.models.skills.Nursing;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface NursingRepository extends JpaRepository<Nursing, Long> {
}
