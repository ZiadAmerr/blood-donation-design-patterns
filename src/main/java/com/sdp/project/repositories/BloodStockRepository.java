package com.sdp.project.repositories;

import com.sdp.project.models.blood.BloodType;
import com.sdp.project.models.blood.BloodStock;

import org.springframework.data.repository.CrudRepository;

public interface BloodStockRepository extends CrudRepository<BloodStock, BloodType> {
}

