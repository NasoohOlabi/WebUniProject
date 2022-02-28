-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mnu
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mnu
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mnu` DEFAULT CHARACTER SET utf8 ;
USE `mnu` ;

-- -----------------------------------------------------
-- Table `mnu`.`subject`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`subject` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT(500) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`topic`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`topic` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT(280) NULL,
  `subject_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  INDEX `fk_topics_subjects1_idx` (`subject_id` ASC),
  CONSTRAINT `fk_topics_subjects1`
    FOREIGN KEY (`subject_id`)
    REFERENCES `mnu`.`subject` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `text` TEXT(280) NOT NULL,
  `number_of_choices` TINYINT(1) UNSIGNED NOT NULL,
  `topic_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_questions_topics1_idx` (`topic_id` ASC),
  CONSTRAINT `fk_questions_topics1`
    FOREIGN KEY (`topic_id`)
    REFERENCES `mnu`.`topic` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`exam`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`exam` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `number_of_questions` SMALLINT(3) UNSIGNED NOT NULL,
  `duration` SMALLINT(3) UNSIGNED NOT NULL,
  `subject_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_exams_subjects1_idx` (`subject_id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  CONSTRAINT `fk_exams_subjects1`
    FOREIGN KEY (`subject_id`)
    REFERENCES `mnu`.`subject` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`exam_has_question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`exam_has_question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question_id` INT NOT NULL,
  `exam_id` INT NOT NULL,
  INDEX `fk_exam_has_question_questions1_idx` (`question_id` ASC),
  INDEX `fk_exam_has_question_exams1_idx` (`exam_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_exam_has_question_questions1`
    FOREIGN KEY (`question_id`)
    REFERENCES `mnu`.`question` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_exam_has_question_exams1`
    FOREIGN KEY (`exam_id`)
    REFERENCES `mnu`.`exam` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`exam_center`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`exam_center` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`exam_center_has_exam`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`exam_center_has_exam` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `exam_id` INT NOT NULL,
  `exam_center_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_exam_center_has_exam_exams1_idx` (`exam_id` ASC),
  INDEX `fk_exam_center_has_exam_exam_centers1_idx` (`exam_center_id` ASC),
  UNIQUE INDEX `exam_id_UNIQUE` (`exam_id` ASC, `exam_center_id` ASC),
  CONSTRAINT `fk_exam_center_has_exam_exams1`
    FOREIGN KEY (`exam_id`)
    REFERENCES `mnu`.`exam` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_exam_center_has_exam_exam_centers1`
    FOREIGN KEY (`exam_center_id`)
    REFERENCES `mnu`.`exam_center` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`choice`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`choice` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `text` TEXT(280) NULL,
  `is_correct` TINYINT(1) UNSIGNED NOT NULL,
  `question_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_choices_questions1_idx` (`question_id` ASC),
  CONSTRAINT `fk_choices_questions1`
    FOREIGN KEY (`question_id`)
    REFERENCES `mnu`.`question` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`role` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NULL,
  `password` VARCHAR(32) NULL,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `middle_name` VARCHAR(45) NOT NULL,
  `profile_picture` BLOB NULL,
  `role_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_users_roles1_idx` (`role_id` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  CONSTRAINT `fk_users_roles1`
    FOREIGN KEY (`role_id`)
    REFERENCES `mnu`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`student`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`student` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `enroll_date` DATE NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  CONSTRAINT `user_id`
    FOREIGN KEY (`id`)
    REFERENCES `mnu`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`student_took_exam`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`student_took_exam` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `exam_center_id` INT NOT NULL,
  `choice_id` INT NOT NULL,
  `students_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_student_took_exam_exam_centers1_idx` (`exam_center_id` ASC),
  INDEX `fk_student_took_exam_choices1_idx` (`choice_id` ASC),
  INDEX `fk_student_took_exam_students1_idx` (`students_id` ASC),
  CONSTRAINT `fk_student_took_exam_exam_centers1`
    FOREIGN KEY (`exam_center_id`)
    REFERENCES `mnu`.`exam_center` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_took_exam_choices1`
    FOREIGN KEY (`choice_id`)
    REFERENCES `mnu`.`choice` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_took_exam_students1`
    FOREIGN KEY (`students_id`)
    REFERENCES `mnu`.`student` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`permission`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`permission` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`role_has_permission`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`role_has_permission` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `role_id` INT NOT NULL,
  `permission_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_role_has_permission_roles1_idx` (`role_id` ASC),
  INDEX `fk_role_has_permission_permissions1_idx` (`permission_id` ASC),
  CONSTRAINT `fk_role_has_permission_roles1`
    FOREIGN KEY (`role_id`)
    REFERENCES `mnu`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_has_permission_permissions1`
    FOREIGN KEY (`permission_id`)
    REFERENCES `mnu`.`permission` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
