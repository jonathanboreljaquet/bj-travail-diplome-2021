-- MySQL Script generated by MySQL Workbench
-- Fri May 28 15:33:54 2021
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema api_rest_douceur_de_chien
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema api_rest_douceur_de_chien
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `api_rest_douceur_de_chien` DEFAULT CHARACTER SET utf8 ;
USE `api_rest_douceur_de_chien` ;

-- -----------------------------------------------------
-- Table `api_rest_douceur_de_chien`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_rest_douceur_de_chien`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NOT NULL,
  `firstname` VARCHAR(45) NOT NULL,
  `lastname` VARCHAR(45) NOT NULL,
  `phonenumber` VARCHAR(20) NOT NULL,
  `address` VARCHAR(45) NOT NULL,
  `api_token` VARCHAR(45) NOT NULL,
  `code_role` VARCHAR(10) NOT NULL,
  `password_hash` VARCHAR(60) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `api_rest_douceur_de_chien`.`dog`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_rest_douceur_de_chien`.`dog` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `breed` VARCHAR(45) NOT NULL,
  `sex` VARCHAR(45) NOT NULL,
  `picture_serial_id` VARCHAR(45) NULL,
  `chip_id` VARCHAR(15) NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_dog_user_idx` (`user_id` ASC),
  CONSTRAINT `fk_dog_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `api_rest_douceur_de_chien`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `api_rest_douceur_de_chien`.`appointment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_rest_douceur_de_chien`.`appointment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `datetime_appointment` DATETIME NOT NULL,
  `duration_in_hour` INT NOT NULL,
  `note_text` TEXT NULL,
  `note_graphical_serial_id` VARCHAR(45) NULL,
  `summary` TEXT NULL,
  `datetime_deletion` DATETIME NULL,
  `user_id_customer` INT NOT NULL,
  `user_id_educator` INT NOT NULL,
  `user_id_deletion` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_appoitment_user1_idx` (`user_id_customer` ASC),
  INDEX `fk_appoitment_user2_idx` (`user_id_deletion` ASC),
  INDEX `fk_appoitment_user3_idx` (`user_id_educator` ASC),
  CONSTRAINT `fk_appoitment_user1`
    FOREIGN KEY (`user_id_customer`)
    REFERENCES `api_rest_douceur_de_chien`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_appoitment_user2`
    FOREIGN KEY (`user_id_deletion`)
    REFERENCES `api_rest_douceur_de_chien`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_appoitment_user3`
    FOREIGN KEY (`user_id_educator`)
    REFERENCES `api_rest_douceur_de_chien`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `api_rest_douceur_de_chien`.`document`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_rest_douceur_de_chien`.`document` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `document_serial_id` VARCHAR(45) NOT NULL,
  `type` ENUM("conditions_inscription", "poster") NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_document_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_document_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `api_rest_douceur_de_chien`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `api_rest_douceur_de_chien`.`absence`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_rest_douceur_de_chien`.`absence` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date_absence_from` DATE NOT NULL,
  `date_absence_to` DATE NULL,
  `description` VARCHAR(50) NULL,
  `is_deleted` TINYINT NOT NULL DEFAULT 0,
  `id_educator` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `api_rest_douceur_de_chien`.`weekly_schedule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_rest_douceur_de_chien`.`weekly_schedule` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date_valid_from` DATE NOT NULL,
  `date_valid_to` DATE NULL,
  `is_deleted` TINYINT NOT NULL DEFAULT 0,
  `id_educator` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `api_rest_douceur_de_chien`.`schedule_override`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_rest_douceur_de_chien`.`schedule_override` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date_schedule_override` DATE NOT NULL,
  `is_deleted` TINYINT NOT NULL DEFAULT 0,
  `id_educator` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `api_rest_douceur_de_chien`.`time_slot`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_rest_douceur_de_chien`.`time_slot` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `code_day` TINYINT NOT NULL,
  `time_start` TIME NOT NULL,
  `time_end` TIME NOT NULL,
  `is_deleted` TINYINT NOT NULL DEFAULT 0,
  `id_weekly_schedule` INT NULL,
  `id_schedule_override` INT NULL,
  `id_educator` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Tbl_Time_Slot_Tbl_Schedule_idx` (`id_weekly_schedule` ASC),
  INDEX `fk_Tbl_Time_Slot_Tbl_Schedule_Override1_idx` (`id_schedule_override` ASC),
  CONSTRAINT `fk_Tbl_Time_Slot_Tbl_Schedule`
    FOREIGN KEY (`id_weekly_schedule`)
    REFERENCES `api_rest_douceur_de_chien`.`weekly_schedule` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Tbl_Time_Slot_Tbl_Schedule_Override1`
    FOREIGN KEY (`id_schedule_override`)
    REFERENCES `api_rest_douceur_de_chien`.`schedule_override` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
