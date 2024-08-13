drop table Circuit_1;
drop table HaveResults2;
drop table WorkFor;
drop table Broadcast;
drop table Participate;
drop table RacesTakePlace;
drop table Circuit_2;
drop table Sponsor;
drop table Drive;
drop table RacingCars;
drop table RacingDrivers;
drop table TeamPrinciples;
drop table EmployTeamMembers;
drop table DriveSafetyCars;
drop table OwnCars;
drop table Constructors;
drop table Sponsorship;
drop table HaveResults1;
drop table SafetyCarDriver;
drop table President;
drop table OfficialStaff;
drop table Broadcasters;

CREATE TABLE Circuit_1(
    city CHAR(15) PRIMARY KEY,
    time_zone CHAR(15)
);

CREATE TABLE Circuit_2(
    city CHAR(15) NOT NULL,
    circuit_name CHAR(50) PRIMARY KEY,
    country CHAR(15) NOT NULL,
    longitude REAL NOT NULL,
    latitude REAL NOT NULL
);

CREATE TABLE RacesTakePlace(
    race_date  CHAR(10) PRIMARY KEY, 
    race_name  VARCHAR(30), 
    round_number INTEGER, 
    lap_numbers INTEGER, 
    circuit_name CHAR(50) NOT NULL,
    FOREIGN KEY(circuit_name) REFERENCES Circuit_2 ON DELETE CASCADE 
);

CREATE TABLE Constructors(
    constructor_name CHAR(15) PRIMARY KEY,
    nationality CHAR(15),
    city CHAR(15)
);

CREATE TABLE Sponsorship(
    sponsorship_name CHAR(50) PRIMARY KEY,
    nationality CHAR(15),
    amount INTEGER
);

CREATE TABLE Sponsor(
    constructor_name CHAR(15),
    sponsorship_name CHAR(50),
    PRIMARY KEY(constructor_name,sponsorship_name),
    FOREIGN KEY(constructor_name) REFERENCES Constructors ON DELETE CASCADE,
    FOREIGN KEY(sponsorship_name) REFERENCES Sponsorship ON DELETE CASCADE
);

CREATE TABLE EmployTeamMembers(
    first_name CHAR(20),
    last_name CHAR(20),
    date_of_birth CHAR(10),
    nationality CHAR(15),
    constructor_name CHAR(15) NOT NULL,
    PRIMARY KEY(first_name,last_name,date_of_birth),
    FOREIGN KEY(constructor_name) REFERENCES Constructors ON DELETE CASCADE
);

CREATE TABLE RacingDrivers(
    first_name CHAR(20),
    last_name CHAR(20),
    date_of_birth CHAR(10),
    driver_id INTEGER,
    PRIMARY KEY(first_name,last_name,date_of_birth),
    FOREIGN KEY(first_name,last_name,date_of_birth) REFERENCES EmployTeamMembers(first_name,last_name,date_of_birth) ON DELETE CASCADE
);

CREATE TABLE TeamPrinciples(
    first_name CHAR(20),
    last_name CHAR(20),
    date_of_birth CHAR(10),  
    duration CHAR(15),
    PRIMARY KEY(first_name,last_name,date_of_birth),
    FOREIGN KEY(first_name,last_name,date_of_birth) REFERENCES EmployTeamMembers(first_name,last_name,date_of_birth) ON DELETE CASCADE
);

CREATE TABLE RacingCars(
    racingcar_name CHAR(30) PRIMARY KEY
);

CREATE TABLE Drive(
    racingcar_name CHAR(30),
    racingdriver_dob CHAR(10),
    racingdriver_firstname CHAR(20),
    racingdriver_lastname CHAR(20),
    PRIMARY KEY(racingcar_name, racingdriver_dob,racingdriver_firstname, racingdriver_lastname),
    FOREIGN KEY(racingcar_name) REFERENCES RacingCars(racingcar_name) ON DELETE CASCADE,
    FOREIGN KEY(racingdriver_dob, racingdriver_firstname, racingdriver_lastname) REFERENCES RacingDrivers(date_of_birth,first_name,last_name) ON DELETE CASCADE
);

CREATE TABLE OwnCars(
    car_name CHAR(30) PRIMARY KEY,
    constructor_name CHAR(15) NOT NULL, 
    engine CHAR(50),
    FOREIGN KEY(constructor_name) REFERENCES Constructors ON DELETE CASCADE 
);


CREATE TABLE HaveResults1(
    result_time CHAR(12) PRIMARY KEY,
    race_rank INTEGER
);

CREATE TABLE HaveResults2(
    result_id INTEGER,
    race_date CHAR(10),
    grid_position INTEGER,
    result_time CHAR(12),
    race_status CHAR(30),
    PRIMARY KEY(result_id,race_date),
    FOREIGN KEY(race_date) REFERENCES RacesTakePlace(race_date) ON DELETE CASCADE    
);

CREATE TABLE OfficialStaff(
    officialstaff_name CHAR(30) PRIMARY KEY
);

CREATE TABLE SafetyCarDriver(
    safetycardriver_name CHAR(30) PRIMARY KEY,
    FOREIGN KEY(safetycardriver_name) REFERENCES OfficialStaff ON DELETE CASCADE 
);

CREATE TABLE DriveSafetyCars(
    safetycar_name CHAR(30) PRIMARY KEY,
    safetycar_driver CHAR(30) NOT NULL,
    brand_name CHAR (30),
    FOREIGN KEY(safetycar_name) REFERENCES OwnCars(car_name) ON DELETE CASCADE,
    FOREIGN KEY(safetycar_driver) REFERENCES SafetyCarDriver(safetycardriver_name) ON DELETE CASCADE
);

CREATE TABLE President(
    president_name CHAR(30) PRIMARY KEY,
    duration_start_date CHAR(10),
    FOREIGN KEY(president_name) REFERENCES OfficialStaff ON DELETE CASCADE 
);

CREATE TABLE WorkFor(
    officialstaff_name CHAR(30),
    race_date CHAR(10),
    PRIMARY KEY(officialstaff_name, race_date),
    FOREIGN KEY(officialstaff_name) REFERENCES OfficialStaff  ON DELETE CASCADE,
    FOREIGN KEY(race_date) REFERENCES RacesTakePlace(race_date) ON DELETE CASCADE
);

CREATE TABLE Broadcasters(
    broadcasters_name CHAR(30) PRIMARY KEY
);

CREATE TABLE Broadcast(
    race_date CHAR(10),
    broadcasters_name CHAR(30),
    PRIMARY KEY(race_date, broadcasters_name),
    FOREIGN KEY(race_date) REFERENCES RacesTakePlace(race_date) ON DELETE CASCADE,
    FOREIGN KEY(broadcasters_name) REFERENCES Broadcasters ON DELETE CASCADE
);

CREATE TABLE Participate(
    race_date CHAR(10),
    racingcar_name CHAR(30),
    racingdriver_dob CHAR(10),
    racingdriver_firstname CHAR(20),
    racingdriver_lastname CHAR(20),
    PRIMARY KEY(race_date, racingcar_name, racingdriver_dob, racingdriver_firstname, racingdriver_lastname),
    FOREIGN KEY(race_date) REFERENCES RacesTakePlace(race_date) ON DELETE CASCADE,
    FOREIGN KEY(racingcar_name) REFERENCES RacingCars(racingcar_name) ON DELETE CASCADE,
    FOREIGN KEY(racingdriver_dob,racingdriver_firstname,
    racingdriver_lastname) REFERENCES RacingDrivers(date_of_birth, 
    first_name, last_name) ON DELETE CASCADE
);

-- circuit1
INSERT INTO Circuit_1 VALUES ('Silverstone', 'GMT+1');
INSERT INTO Circuit_1 VALUES ('Kuala Lumpur', 'GMT+8');
INSERT INTO Circuit_1 VALUES ('Montreal', 'GMT-4');
INSERT INTO Circuit_1 VALUES ('Sakhir', 'GMT+4');
INSERT INTO Circuit_1 VALUES ('Abu Dhabi', 'GMT+4');

-- circuit2
INSERT INTO Circuit_2 VALUES ('Silverstone', 'Silverstone Circuit', 'UK', -1.01694, 52.0786);
INSERT INTO Circuit_2 VALUES ('Kuala Lumpur', 'Sepang International Circuit', 'Malaysia', 101.738, 2.76083);
INSERT INTO Circuit_2 VALUES ('Montreal', 'Circuit Gilles Villeneuve', 'Canada', -73.5228, 45.5);
INSERT INTO Circuit_2 VALUES ('Sakhir', 'Bahrain International Circuit', 'Bahrain', 50.5106, 26.0325);
INSERT INTO Circuit_2 VALUES ('Abu Dhabi', 'Yes Marina Circuit', 'UAE', 54.6031, 24.4672);

-- RacesTakePlace
INSERT INTO RacesTakePlace VALUES('2008-07-06', 'British Grand Prix', 9, 52, 'Silverstone Circuit');
INSERT INTO RacesTakePlace VALUES('2009-04-05', 'Malaysian Grand Prix', 2, 56, 'Sepang International Circuit');
INSERT INTO RacesTakePlace VALUES('2008-06-08', 'Canadian Grand Prix', 7, 70, 'Circuit Gilles Villeneuve');
INSERT INTO RacesTakePlace VALUES('2009-04-26', 'Bahrain Grand Prix', 4, 57, 'Bahrain International Circuit');
INSERT INTO RacesTakePlace VALUES('2009-11-01', 'Abu Dhabi Grand Prix', 17, 55, 'Yes Marina Circuit');

-- Constructors
INSERT INTO Constructors VALUES('Spyker', 'Dutch', 'Zeewolde');
INSERT INTO Constructors VALUES('Toyota', 'Japanese', 'Toyota');
INSERT INTO Constructors VALUES('Boro', 'Dutch', 'Bovenkerk');
INSERT INTO Constructors VALUES('Jordan', 'Irish', 'Silverstone');
INSERT INTO Constructors VALUES('Sauber', 'Swiss', 'Hinwil');


-- Sponsorship
INSERT INTO Sponsorship VALUES('McGregor Fashion Group','Netherlands', 130);
INSERT INTO Sponsorship VALUES('Panasonic','Japan', 253);
INSERT INTO Sponsorship VALUES('HB Bewaking', 'Netherlands', 55);
INSERT INTO Sponsorship VALUES('Ferrari', 'Italy', 157);
INSERT INTO Sponsorship VALUES('Alfa Romeo', 'Italy', 182);

-- Sponsor
INSERT INTO Sponsor VALUES('Spyker', 'McGregor Fashion Group');
INSERT INTO Sponsor VALUES('Toyota', 'Panasonic');
INSERT INTO Sponsor VALUES('Boro', 'HB Bewaking');
INSERT INTO Sponsor VALUES('Jordan', 'Ferrari');
INSERT INTO Sponsor VALUES('Sauber', 'Alfa Romeo');

--EmployTeamMembers
INSERT INTO EmployTeamMembers VALUES('Colin','Kolles','1967-12-13','German','Spyker');
INSERT INTO EmployTeamMembers VALUES('Tadashi','Yamashina','1951-05-08','Japanese','Spyker');
INSERT INTO EmployTeamMembers VALUES('Bob','Hoogenboom','1949-05-06','Netherlands','Boro');
INSERT INTO EmployTeamMembers VALUES('James', 'Steve', '1973-05-23', 'UK', 'Boro');
INSERT INTO EmployTeamMembers VALUES('Jobs','Bond','1957-08-24','United States','Jordan');
INSERT INTO EmployTeamMembers VALUES('Mark','Webber','1997-04-05','Austrilian','Jordan');
INSERT INTO EmployTeamMembers VALUES('James','Speed', '1993-05-23', 'United States','Sauber');
INSERT INTO EmployTeamMembers VALUES('Shinji','Nakano','1985-09-07','Japanese','Sauber');
INSERT INTO EmployTeamMembers VALUES('Martin','Brundle','1994-07-25','German','Toyota');
INSERT INTO EmployTeamMembers VALUES('Aguri', 'Suzuki', '1995-06-07','Japanese','Toyota');

--RacingDrivers
INSERT INTO RacingDrivers VALUES ('Mark','Webber','1997-04-05',1);
INSERT INTO RacingDrivers VALUES ('James','Speed','1993-05-23',2);
INSERT INTO RacingDrivers VALUES ('Shinji','Nakano','1985-09-07',3);
INSERT INTO RacingDrivers VALUES ('Martin','Brundle','1994-07-25',4);
INSERT INTO RacingDrivers VALUES ('Aguri','Suzuki','1995-06-07',5);

--TeamPrinciples
INSERT INTO TeamPrinciples VALUES ('Colin','Kolles','1967-12-13','2005-2008');
INSERT INTO TeamPrinciples VALUES ('Tadashi','Yamashina','1951-05-08','2012-2016');
INSERT INTO TeamPrinciples VALUES ('Bob','Hoogenboom','1949-05-06','2013-2014');
INSERT INTO TeamPrinciples VALUES ('James','Steve','1973-05-23','2008-2009');
INSERT INTO TeamPrinciples VALUES ('Jobs','Bond','1957-08-24','2010-2012');

--RacingCars
INSERT INTO RacingCars VALUES ('VF-22');
INSERT INTO RacingCars VALUES ('TF102'); 
INSERT INTO RacingCars VALUES ('A522');
INSERT INTO RacingCars VALUES ('AT03');
INSERT INTO RacingCars VALUES ('AMR22');

--Drive
INSERT INTO Drive VALUES ('VF-22','1997-04-05','Mark','Webber');
INSERT INTO Drive VALUES ('TF102','1993-05-23','James','Speed');
INSERT INTO Drive VALUES ('A522','1985-09-07','Shinji','Nakano');
INSERT INTO Drive VALUES ('AT03','1994-07-25','Martin','Brundle');
INSERT INTO Drive VALUES ('AMR22','1995-06-07','Aguri','Suzuki');

--OwnCars
INSERT INTO OwnCars VALUES ('VF-22','Spyker','Ferrar-056');
INSERT INTO OwnCars VALUES ('TF102','Toyota','Toyota RVX-02');
INSERT INTO OwnCars VALUES ('A522','Boro','Ford');
INSERT INTO OwnCars VALUES ('AT03','Jordan','Red Bull');
INSERT INTO OwnCars VALUES ('AMR22','Sauber','Mercedes-AMG');
INSERT INTO OwnCars VALUES ('Porsche 914','Spyker','Volkswagen Type 4 F4');
INSERT INTO OwnCars VALUES ('Porsche 911','Toyota','Porsche flat-six engine');
INSERT INTO OwnCars VALUES ('Lamborghini Countach','Boro','Lamborghini V12 LP400');
INSERT INTO OwnCars VALUES ('Ford Escort RS Cosworth','Jordan','Cosworth YBT');
INSERT INTO OwnCars VALUES ('Mercedes-AMG GT','Sauber','M178');

-- OfficialStaff
INSERT INTO OfficialStaff VALUES ('Stefano Domenicali');
INSERT INTO OfficialStaff VALUES ('Chase Carey');
INSERT INTO OfficialStaff VALUES ('Ross Brawn');
INSERT INTO OfficialStaff VALUES ('Duncan Llowarch');
INSERT INTO OfficialStaff VALUES ('Sacha Woodward Hill');
INSERT INTO OfficialStaff VALUES ('Bernd Maylander');
INSERT INTO OfficialStaff VALUES ('Eppie Wietzes');
INSERT INTO OfficialStaff VALUES ('Mark Goddard');
INSERT INTO OfficialStaff VALUES ('Max Angelelli');
INSERT INTO OfficialStaff VALUES ('Oliver Gavin');

-- SafetyCarDriver
INSERT INTO SafetyCarDriver VALUES ('Bernd Maylander');
INSERT INTO SafetyCarDriver VALUES ('Eppie Wietzes');
INSERT INTO SafetyCarDriver VALUES ('Mark Goddard');
INSERT INTO SafetyCarDriver VALUES ('Max Angelelli');
INSERT INTO SafetyCarDriver VALUES ('Oliver Gavin');

--DriveSafetyCars
INSERT INTO DriveSafetyCars VALUES ('Porsche 914','Bernd Maylander','Spyker');
INSERT INTO DriveSafetyCars VALUES ('Porsche 911','Bernd Maylander','Toyota');
INSERT INTO DriveSafetyCars VALUES ('Lamborghini Countach','Bernd Maylander','Boro');
INSERT INTO DriveSafetyCars VALUES ('Ford Escort RS Cosworth','Bernd Maylander','Jordan');
INSERT INTO DriveSafetyCars VALUES ('Mercedes-AMG GT','Bernd Maylander','Sauber');


--HaveResult1
INSERT INTO HaveResults1 VALUES('31:18:06.876',3);
INSERT INTO HaveResults1 VALUES('38:45:03.023',9);
INSERT INTO HaveResults1 VALUES('45:33:12.391',13);
INSERT INTO HaveResults1 VALUES('46:46:05.486',15);
INSERT INTO HaveResults1 VALUES('32:29:33.828',4);

-- HaveResults2
INSERT INTO HaveResults2 VALUES (1, '2008-07-06', 5, '31:18:06.876', 'completed');
INSERT INTO HaveResults2 VALUES (2, '2009-04-05', 4, '38:45:03.023', 'accident');
INSERT INTO HaveResults2 VALUES (3, '2008-06-08', 3, '45:33:12.391', 'accident');
INSERT INTO HaveResults2 VALUES (4, '2009-04-26', 2, '46:46:05.486', 'disqualification');
INSERT INTO HaveResults2 VALUES (5, '2009-11-01', 4, '32:29:33.828', 'completed');


-- President
INSERT INTO President VALUES ('Stefano Domenicali', '2021-01-12');
INSERT INTO President VALUES ('Chase Carey', '2014-04-05');
INSERT INTO President VALUES ('Ross Brawn', '2019-11-02');
INSERT INTO President VALUES ('Duncan Llowarch', '2017-09-01');
INSERT INTO President VALUES ('Sacha Woodward Hill', '2020-01-12');

-- WorkFor
INSERT INTO WorkFor VALUES ('Bernd Maylander', '2008-07-06');
INSERT INTO WorkFor VALUES ('Eppie Wietzes', '2009-04-05');
INSERT INTO WorkFor VALUES ('Mark Goddard', '2008-06-08');
INSERT INTO WorkFor VALUES ('Max Angelelli', '2009-04-26');
INSERT INTO WorkFor VALUES ('Oliver Gavin', '2009-11-01');

-- Broadcasters
INSERT INTO Broadcasters VALUES ('ESPN');
INSERT INTO Broadcasters VALUES ('Fox Sports');
INSERT INTO Broadcasters VALUES ('BBC');
INSERT INTO Broadcasters VALUES ('ABC');
INSERT INTO Broadcasters VALUES ('CCTV');

-- Broadcast
INSERT INTO Broadcast VALUES ('2008-07-06', 'BBC');
INSERT INTO Broadcast VALUES ('2009-04-05', 'ABC');
INSERT INTO Broadcast VALUES ('2008-06-08', 'ABC');
INSERT INTO Broadcast VALUES ('2009-04-26', 'CCTV');
INSERT INTO Broadcast VALUES ('2009-11-01', 'ESPN');

-- Participate
INSERT INTO Participate VALUES ('2008-07-06', 'VF-22', '1997-04-05', 'Mark', 'Webber');
INSERT INTO Participate VALUES ('2009-04-05', 'TF102', '1993-05-23', 'James', 'Speed');
INSERT INTO Participate VALUES ('2008-06-08', 'A522', '1985-09-07', 'Shinji', 'Nakano');
INSERT INTO Participate VALUES ('2009-04-26', 'AT03', '1994-07-25', 'Martin', 'Brundle');
INSERT INTO Participate VALUES ('2009-11-01', 'AMR22', '1995-06-07', 'Aguri', 'Suzuki');
