CREATE SCHEMA IF NOT EXISTS space_merchant;

USE space_merchant;

GRANT ALL ON *.* TO 'sa'@'localhost' IDENTIFIED BY 'rasta' WITH GRANT OPTION;
FLUSH PRIVILEGES;

DROP TABLE IF EXISTS mission;
DROP TABLE IF EXISTS merchant_crew;
DROP TABLE IF EXISTS crew_member;
DROP TABLE IF EXISTS merchant_spaceship;
DROP TABLE IF EXISTS spaceship;
DROP TABLE IF EXISTS planet;
DROP TABLE IF EXISTS cargo_type;
DROP TABLE IF EXISTS ability;
DROP TABLE IF EXISTS merchant;


CREATE TABLE merchant (
    id_merchant INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
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
    cargo_capacity_ton INT,
    max_travel_range_parsec INT,
    price INT,
    image VARCHAR(200)
);

CREATE TABLE merchant_spaceship (
    id_merchant INT,
    id_spaceship INT,
    level INT DEFAULT 1,
    FOREIGN KEY (id_merchant) REFERENCES merchant(id_merchant),
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
    in_a_team INT DEFAULT 0,
    FOREIGN KEY (id_ability) REFERENCES ability(id_ability)
);

CREATE TABLE merchant_crew(
    id_crew_member INT,
    id_merchant INT,
    FOREIGN KEY (id_crew_member) REFERENCES crew_member(id_crew_member),
    FOREIGN KEY (id_merchant) REFERENCES merchant(id_merchant)
);

CREATE TABLE planet (
    id_planet INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(25),
    distance_from_earth INT
);

CREATE TABLE cargo_type (
    id_cargo_type INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(30),
    price_by_ton INT
);

CREATE TABLE mission (
    id_mission INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name TEXT,
    id_cargo_type INT,
    id_planet INT,
    id_ability INT,
    id_merchant INT,
    reward INT,
    description TEXT,
    done INT DEFAULT 0,
    FOREIGN KEY (id_cargo_type) REFERENCES cargo_type(id_cargo_type),
    FOREIGN KEY (id_planet) REFERENCES planet(id_planet),
    FOREIGN KEY (id_ability) REFERENCES ability(id_ability),
    FOREIGN KEY (id_merchant) REFERENCES merchant(id_merchant)
);



INSERT INTO ability(name, description) VALUES ("Sneaky", "increase your chance to succeed in missions that requiere stealth");
INSERT INTO ability(name, description) VALUES ("Pilot", "increase your chance to succeed in missions that requiere great maniement of spaceship");
INSERT INTO ability(name, description) VALUES ("ColdBlood", "increase your chance to succeed in missions that requiere great gestion of stress");
INSERT INTO ability(name, description) VALUES ("Genius", "increase your chance to succeed in missions that requiere great intelligence");
INSERT INTO ability(name, description) VALUES ("Eagle Eye", "increases your chance to succeed in missions that require sharp observation skills");
INSERT INTO ability(name, description) VALUES ("Mechanic", "increases your chance to succeed in missions that require mechanical skills");
INSERT INTO ability(name, description) VALUES ("Negotiator", "increases your chance to succeed in missions that require negotiation and diplomacy");
INSERT INTO ability(name, description) VALUES ("Medic", "increases your chance to succeed in missions that require medical expertise");

INSERT INTO cargo_type (type, price_by_ton) VALUES ("Minerals", 6);
INSERT INTO cargo_type (type, price_by_ton) VALUES ("Foods", 3);
INSERT INTO cargo_type (type, price_by_ton) VALUES ("Weaponry", 8);
INSERT INTO cargo_type (type, price_by_ton) VALUES ("Components", 7);
INSERT INTO cargo_type (type, price_by_ton) VALUES ("Agricultural Products", 2);
INSERT INTO cargo_type (type, price_by_ton) VALUES ("Luxury Goods", 10);
INSERT INTO cargo_type (type, price_by_ton) VALUES ("Electronics", 5);
INSERT INTO cargo_type (type, price_by_ton) VALUES ("Medicine", 2);
INSERT INTO cargo_type (type, price_by_ton) VALUES ("Machinery", 6);
INSERT INTO cargo_type (type, price_by_ton) VALUES ("Textiles", 3);
INSERT INTO cargo_type (type, price_by_ton) VALUES ("Chemicals", 7);
INSERT INTO cargo_type (type, price_by_ton) VALUES ("Vehicles", 6);


INSERT INTO planet(name, distance_from_earth) VALUES("Venus", 26);
INSERT INTO planet(name, distance_from_earth) VALUES("Jupiter", 365);
INSERT INTO planet(name, distance_from_earth) VALUES("Saturn", 746);
INSERT INTO planet(name, distance_from_earth) VALUES("Uranus", 1660);
INSERT INTO planet(name, distance_from_earth) VALUES("Neptune", 2660);
INSERT INTO planet(name, distance_from_earth) VALUES("Mercury", 18);
INSERT INTO planet(name, distance_from_earth) VALUES("Pluto", 3670);
INSERT INTO planet(name, distance_from_earth) VALUES("Mars", 21);
INSERT INTO planet(name, distance_from_earth) VALUES("Gaïa", 6010);

INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('John', 'Doe', 1, 500);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Alice', 'Smith', 2, 600);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Bob', 'Johnson', 3, 700);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Eva', 'Williams', 4, 800);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('David', 'Brown', 1, 900);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Grace', 'Lee', 2, 1000);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Frank', 'Davis', 3, 550);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Olivia', 'Moore', 4, 600);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Henry', 'Wilson', 3, 700);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Sophia', 'Taylor', 4, 800);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Liam', 'White', 1, 900);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Mia', 'Anderson', 2, 1500);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Samuel', 'Martinez', 5, 1700);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Avery', 'Hernandez', 6, 1600);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Victoria', 'Lopez', 8, 1800);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Christopher', 'Gonzalez', 3, 700);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Madison', 'Perez', 1, 2000);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Jordan', 'Wright', 7, 1300);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('William', 'Robert', 4, 1200);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Evelyn', 'Sanchez', 2, 1100);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('James', 'Clark', 6, 1900);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Emma', 'Harris', 7, 1600);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('William', 'Garcia', 8, 1800);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Sophia', 'Rodriguez', 4, 900);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Michael', 'Miller', 3, 1700);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Olivia', 'Davis', 5, 1400);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Daniel', 'Lopez', 2, 1300);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Ava', 'Martinez', 1, 1500);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Matthew', 'Da Vinci', 6, 1200);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Isabella', 'Clark', 8, 1600);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Joseph', 'Anderson', 7, 1900);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Lucas', 'Vigny', 4, 1000000);
INSERT INTO crew_member (first_name, last_name, id_ability, recruitment_price) VALUES ('Alexis', 'Davias', 5, 100000);



INSERT INTO merchant(id_merchant, login, password, first_name, last_name, intergalactic_credits)
VALUES (1, 'guild_account', 'guild_password', 'intergalactic', 'guild', 1000000);

INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Send Laser to Mars", 7, 8, 1, 1, 1000, "you have to bring laser to mars");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Deliver Robot Parts", 4, 1, 1, 1, 1200, "Transport essential robot components");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Bring Food to Gaïa", 5, 2, 2, 1, 1500, "Embark on your spaceship and bring food to Gaïa's inhabitants");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Rescue Astronauts from Asteroid", 9, 3, 3, 1, 800, "Save stranded astronauts from an asteroid and bring them home safely");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Deliver Medical Supplies to Mars", 8, 8, 4, 1, 1100, "Transport urgently needed medical equipment to the red planet");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Collect Rare Space Crystals", 1, 5, 1, 1, 1700, "Gather unique space crystals from an uncharted planet");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Defend Space Station from Aliens", 3, 6, 2, 1, 900, "Protect a space station from alien invaders in an epic battle");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Export New Seeds to Create new Type of food on Gaïa", 5, 9, 3, 1, 1300, "New seeds will provide a new light for Gaïa");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Bring Electronics component to Uranus Spatial Base", 4, 4, 4, 1, 950, "They need to fix several pieces on the Base");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Transport Luxury Goods to Titan", 6, 3, 1, 1, 1400, "Deliver high-end luxury goods to Saturn's moon, Titan");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Send New Brand Product to Neptune Merchant", 2, 5, 2, 1, 1600, "Send new brand product to Neptune infamous and greedy merchant ");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Discover Alien Artifacts on Europa", 5, 2, 4, 1, 2200, "Embark on a mission to explore Europa and retrieve mysterious alien relics");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Rescue Survivors from a Collapsed Space Station", 6, 1, 7, 1, 2800, "Save stranded survivors from a space station disaster and bring them home");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Transport Rare Musical Instruments to the Moon", 7, 4, 2, 1, 3100, "Deliver valuable musical instruments to a lunar concert event");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Defend Earth from an Asteroid Threat", 8, 1, 8, 1, 3300, "Protect Earth from an incoming asteroid by intercepting it and altering its course");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Explore a Subterranean Martian Cave", 9, 3, 6, 1, 4200, "Investigate the depths of a Martian cave system and document its unique environment");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Colonize a New Exoplanet", 10, 5, 1, 1, 6000, "Lead the effort to establish a human colony on an uncharted exoplanet");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Rescue an Alien Diplomat", 11, 6, 3, 1, 2400, "Retrieve a stranded alien diplomat and ensure interstellar diplomacy");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Transport Precious Gemstones to Venus", 12, 9, 5, 1, 3700, "Deliver a valuable cargo of gemstones to Venus for an exclusive auction");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Study Microscopic Life on an Asteroid", 1, 8, 4, 1, 2800, "Examine and document microscopic life forms on a traveling asteroid");
INSERT INTO mission(name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES ("Retrieve Ancient Alien Artifacts from Saturn's Rings", 2, 9, 8, 1, 4500, "Recover ancient alien artifacts hidden within the rings of Saturn");


INSERT INTO spaceship(name, crew_capacity, cargo_capacity_ton, max_travel_range_parsec, price, image) VALUES("T-wings", 1, 200, 20, 1500, "img/T-wings.png");
INSERT INTO spaceship(name, crew_capacity, cargo_capacity_ton, max_travel_range_parsec, price, image) VALUES("Starblade", 1, 300, 50, 2500, "img/starblade.png");
INSERT INTO spaceship(name, crew_capacity, cargo_capacity_ton, max_travel_range_parsec, price, image) VALUES("Nebula Voyager", 2, 500, 400, 4000, "img/nebula_voyager.png");
INSERT INTO spaceship(name, crew_capacity, cargo_capacity_ton, max_travel_range_parsec, price, image) VALUES("Cosmic Explorer", 2, 700, 800, 6000, "img/cosmic_explorer.png");
INSERT INTO spaceship(name, crew_capacity, cargo_capacity_ton, max_travel_range_parsec, price, image) VALUES("Galactic Cruiser", 3, 1000, 2000, 8000, "img/galactic_cruiser.png");
INSERT INTO spaceship(name, crew_capacity, cargo_capacity_ton, max_travel_range_parsec, price, image) VALUES("Starship Odyssey", 3, 1200, 5500, 10000, "img/starship_odyssey.png");
INSERT INTO spaceship(name, crew_capacity, cargo_capacity_ton, max_travel_range_parsec, price, image) VALUES("Infinity Traveler", 3, 1500, 10000, 15000, "img/infinity_traveler.png");
INSERT INTO spaceship(name, crew_capacity, cargo_capacity_ton, max_travel_range_parsec, price, image) VALUES("Rift Commander", 5, 6000, 50000, 60000 600000, "img/rift_commander.png");


INSERT into merchant_spaceship(id_merchant, id_spaceship)
VALUES(1, 1);