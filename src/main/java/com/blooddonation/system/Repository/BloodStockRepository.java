package com.blooddonation.system.Repository;

import com.blooddonation.system.models.donations.BloodDonation.BloodStock;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface BloodStockRepository extends JpaRepository<BloodStock, Long> {
}