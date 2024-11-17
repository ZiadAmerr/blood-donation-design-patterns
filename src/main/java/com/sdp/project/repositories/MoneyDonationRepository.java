package com.sdp.project.repositories;

import com.sdp.project.models.money.MoneyDonation;
import org.springframework.data.repository.CrudRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface MoneyDonationRepository extends CrudRepository<MoneyDonation, Long> {
    List<MoneyDonation> findByDonorId(int donorId);
}

