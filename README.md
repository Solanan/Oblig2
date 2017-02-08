# Oblig2
Obligatorisk oppgave 2 2017, for Fredrik, Knut og Martin.

-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema oblig2
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema oblig2
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `oblig2` DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ;
USE `oblig2` ;

-- -----------------------------------------------------
-- Table `oblig2`.`bilde`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `oblig2`.`bilde` (
  `bilde_id` INT NOT NULL AUTO_INCREMENT,
  `opplastings_dato` DATE NOT NULL,
  `filnavn` VARCHAR(45) NOT NULL,
  `beskrivelse` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`bilde_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `oblig2`.`klasse`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `oblig2`.`klasse` (
  `klassekode` CHAR(3) NOT NULL,
  `klassenavn` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`klassekode`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `oblig2`.`student`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `oblig2`.`student` (
  `brukernavn` CHAR(2) NOT NULL,
  `bilde_id` INT NULL,
  `fornavn` VARCHAR(45) NOT NULL,
  `etternavn` VARCHAR(45) NOT NULL,
  `klassekode` CHAR(3) NOT NULL,
  `leveringsfrist` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`brukernavn`),
  INDEX `fk_bilde_id_idx` (`bilde_id` ASC),
  INDEX `fk_klassekode_idx` (`klassekode` ASC),
  CONSTRAINT `fk_bilde_id`
    FOREIGN KEY (`bilde_id`)
    REFERENCES `oblig2`.`bilde` (`bilde_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_klassekode`
    FOREIGN KEY (`klassekode`)
    REFERENCES `oblig2`.`klasse` (`klassekode`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `oblig2`.`admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `oblig2`.`admin` (
  `admin_id` INT NOT NULL AUTO_INCREMENT,
  `brukernavn` VARCHAR(45) NOT NULL,
  `passord` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`admin_id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
