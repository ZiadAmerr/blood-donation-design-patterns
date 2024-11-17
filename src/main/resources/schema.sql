DROP TABLE IF EXISTS BLOOD_DONATIONS;
DROP TABLE IF EXISTS MONEY_DONATIONS;
DROP TABLE IF EXISTS DONORS;
DROP TABLE IF EXISTS VOLUNTEER_SKILLS;
DROP TABLE IF EXISTS VOLUNTEERS;
DROP TABLE IF EXISTS SKILLS;

-- donor tables
CREATE TABLE IF NOT EXISTS DONORS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date_of_birth VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS BLOOD_DONATIONS (
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

-- volunteer tables
CREATE TABLE IF NOT EXISTS VOLUNTEERS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date_of_birth VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS SKILLS (
    id INT PRIMARY KEY,
    name VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS VOLUNTEER_SKILLS (
    volunteer_id INT,
    skill_id INT,
    FOREIGN KEY (volunteer_id) REFERENCES VOLUNTEERS(id),
    FOREIGN KEY (skill_id) REFERENCES SKILLS(id),
    PRIMARY KEY (volunteer_id, skill_id)
);

