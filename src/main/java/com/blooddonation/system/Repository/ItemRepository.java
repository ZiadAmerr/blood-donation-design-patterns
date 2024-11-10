package com.blooddonation.system.Repository;

import com.blooddonation.system.models.donations.MoneyDonation.Item;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface ItemRepository extends JpaRepository<Item, Long> {
}
