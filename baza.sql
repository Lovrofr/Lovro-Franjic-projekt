CREATE DATABASE sportplus;

USE sportplus;

CREATE TABLE vijesti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    datum VARCHAR(20),
    naslov VARCHAR(255),
    sazetak TEXT,
    tekst TEXT,
    slika VARCHAR(255),
    kategorija VARCHAR(50),
    arhiva INT DEFAULT 0
);

CREATE TABLE korisnik (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ime VARCHAR(50),
    prezime VARCHAR(50),
    korisnicko_ime VARCHAR(50) UNIQUE,
    lozinka VARCHAR(255),
    razina INT DEFAULT 0
);
