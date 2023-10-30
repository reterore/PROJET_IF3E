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
    intergalactic_credits INT
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
    name TEXT,
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



INSERT INTO ability(name, description) VALUES ("Sneaky", "increase your chance to succeed in missions that requiere stealth");
INSERT INTO ability(name, description) VALUES ("Pilot", "increase your chance to succeed in missions that requiere great maniement of spaceship");
INSERT INTO ability(name, description) VALUES ("ColdBlood", "increase your chance to succeed in missions that requiere great gestion of stress");
INSERT INTO ability(name, description) VALUES ("Genius", "increase your chance to succeed in missions that requiere great intelligence");

INSERT INTO cargo_type(type) VALUES ("mineral");
INSERT INTO cargo_type(type) VALUES ("food");
INSERT INTO cargo_type(type) VALUES ("weaponery");
INSERT INTO cargo_type(type) VALUES ("component");

INSERT INTO planet(name, distance_from_earth) VALUES("mars", 21);

INSERT INTO space_merchant(id_space_merchant, login, password, first_name, last_name, intergalactic_credits)
VALUES (1, 'guild_account', 'guild_password', 'intergalactic', 'guild', 1000000);

INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_space_merchant, reward, description) VALUES ("send Laser to mars", 1, 1, 1, 1, 1000, "you have to bring laser to mars");

INSERT INTO spaceship(name, crew_capacity, cargo_capacity_kg, max_travel_range_light_year, price)
VALUES("T-wings", 2, 2000, 5, 1500);

INSERT into merchant_spaceship(id_space_merchant, id_spaceship)
VALUES(1, 1);