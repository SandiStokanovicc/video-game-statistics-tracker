-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema riot
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema riot
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `riot` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `riot` ;

-- -----------------------------------------------------
-- Table `riot`.`summoner`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `riot`.`summoner` (
  `idsummoner` INT NOT NULL,
  `summonerName` VARCHAR(45) NULL DEFAULT NULL,
  `puuid` VARCHAR(78) NULL DEFAULT NULL,
  `server` VARCHAR(4) NULL DEFAULT NULL,
  `rank` VARCHAR(2) NULL DEFAULT NULL,
  `summonerlevel` INT NULL DEFAULT NULL,
  PRIMARY KEY (`idsummoner`),
  UNIQUE INDEX `puuid_UNIQUE` (`puuid` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `riot`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `riot`.`user` (
  `iduser` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NULL DEFAULT NULL,
  `email` VARCHAR(45) NULL DEFAULT NULL,
  `password` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE INDEX `iduser_UNIQUE` (`iduser` ASC) VISIBLE,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
