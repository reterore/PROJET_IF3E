DROP TABLE IF EXISTS mission;
DROP TABLE IF EXISTS merchant_crew;
DROP TABLE IF EXISTS crew_member;
DROP TABLE IF EXISTS merchant_spaceship;
DROP TABLE IF EXISTS spaceship;
DROP TABLE IF EXISTS planet;
DROP TABLE IF EXISTS cargo_type;
DROP TABLE IF EXISTS ability;
DROP TABLE IF EXISTS space_merchant;



CREATE TABLE space_merchant (
    id_space_merchant INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(30),
    password VARCHAR(30),
    first_name VARCHAR(20),
    last_name VARCHAR(20),
    intergalactic_credit INT
);

CREATE TABLE spaceship (
    id_spaceship INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20),
    crew_capacity INT,
    cargo_capacity_kg INT,
    max_travel_range_light_year INT,
    price INT,
    image LONGBLOB
);

CREATE TABLE merchant_spaceship (
    id_space_merchant INT,
    id_spaceship INT,
    FOREIGN KEY (id_space_merchant) REFERENCES space_merchant(id_space_merchant),
    FOREIGN KEY (id_spaceship) REFERENCES spaceship(id_spaceship)
);

CREATE TABLE ability (
    id_ability INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(25),
    description TEXT
);

CREATE TABLE crew_member (
    id_crew_member INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(20),
    last_name VARCHAR(20),
    id_ability INT,
    recruitment_price INT,
    FOREIGN KEY (id_ability) REFERENCES ability(id_ability)
);

CREATE TABLE merchant_crew(
    id_crew_member INT,
    id_space_merchant INT,
    FOREIGN KEY (id_crew_member) REFERENCES crew_member(id_crew_member),
    FOREIGN KEY (id_space_merchant) REFERENCES space_merchant(id_space_merchant)
);

CREATE TABLE planet (
    id_planet INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(25),
    distance_from_earth INT
);

CREATE TABLE cargo_type (
    id_cargo_type INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(30)
);

CREATE TABLE mission (
    id_mission INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(25),
    id_cargo_type INT,
    id_planet INT,
    id_ability INT,
    id_space_merchant INT,
    reward INT,
    description TEXT,
    FOREIGN KEY (id_cargo_type) REFERENCES cargo_type(id_cargo_type),
    FOREIGN KEY (id_planet) REFERENCES planet(id_planet),
    FOREIGN KEY (id_ability) REFERENCES ability(id_ability),
    FOREIGN KEY (id_space_merchant) REFERENCES space_merchant(id_space_merchant)
);





INSERT INTO space_merchant(id_space_merchant, login, password, first_name, last_name, birth_date, intergalactic_credit)
VALUES (1, 'guild_account', 'guild_password', 'intergalactic', 'guild', '2000-01-01', 1000000);

INSERT INTO spaceship(name_spaceship, crew_capacity_spaceship, cargo_capacity_kg, max_travel_range_light_year, price_spaceship)
VALUES("T-wings", 2, 2000, 5, 1500);

INSERT into merchant_spaceship(id_space_merchant, id_spaceship)
VALUES(1, 1);