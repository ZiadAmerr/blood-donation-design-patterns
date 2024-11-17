package com.sdp.project.repositories;

import com.sdp.project.models.Donor;
import org.springframework.data.repository.CrudRepository;

public interface DonorRepository extends CrudRepository<Donor, Integer> {
    // You can add custom queries here
}
