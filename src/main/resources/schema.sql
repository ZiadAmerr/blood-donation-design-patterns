CREATE TABLE IF NOT EXISTS DONORS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date_of_birth VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS blood_donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    donation_date DATE,
    volume INT,
    CONSTRAINT fk_donor FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS MONEY_DONATIONS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    donation_date VARCHAR(50),
    amount DECIMAL(10, 2),
    CONSTRAINT fk_donor_money FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE
);
