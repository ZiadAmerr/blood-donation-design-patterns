package com.sdp.project.repositories;

import org.springframework.stereotype.Repository;
import com.sdp.project.models.bloodbank.BloodStock;

@Repository
public class BloodStockRepository {

    public void saveBloodStock(BloodStock bloodStock) {
        // Logic to save blood stock to the database (via JDBC)
    }

    public BloodStock getBloodStock() {
        // Logic to retrieve blood stock from the database (via JDBC)
        return BloodStock.getInstance(); // Simplified for Singleton
    }
}
