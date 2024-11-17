package com.sdp.project.repositories;

import com.sdp.project.models.BloodDonation;
import org.springframework.data.repository.CrudRepository;

import java.util.List;

public interface BloodDonationRepository extends CrudRepository<BloodDonation, Integer> {
}
