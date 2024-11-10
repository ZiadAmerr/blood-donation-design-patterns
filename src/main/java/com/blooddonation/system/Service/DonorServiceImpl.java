package com.blooddonation.system.Service;

import com.blooddonation.system.models.people.Donor;
import com.blooddonation.system.Repository.DonorRepository;
import com.blooddonation.system.Service.DonorService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

@Service
public class DonorServiceImpl implements DonorService {

    private final DonorRepository donorRepository;

    @Autowired
    public DonorServiceImpl(DonorRepository donorRepository) {
        this.donorRepository = donorRepository;
    }

    @Override
    public Donor saveDonor(Donor donor) {
        return donorRepository.save(donor);
    }

    @Override
    public Optional<Donor> getDonorById(Long id) {
        return donorRepository.findById(id);
    }

    @Override
    public List<Donor> getAllDonors() {
        return donorRepository.findAll();
    }

    @Override
    public void deleteDonor(Long id) {
        donorRepository.deleteById(id);
    }
}