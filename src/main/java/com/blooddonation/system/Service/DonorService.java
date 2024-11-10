package com.blooddonation.system.Service;

import com.blooddonation.system.models.people.Donor;
import java.util.List;
import java.util.Optional;

public interface DonorService {
    Donor saveDonor(Donor donor);
    Optional<Donor> getDonorById(Long id);
    List<Donor> getAllDonors();
    void deleteDonor(Long id);
}