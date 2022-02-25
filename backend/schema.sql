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
-- Table `mnu`.`subjects`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`subjects` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT(500) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`topics`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`topics` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT(280) NULL,
  `subjects_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  INDEX `fk_topics_subjects1_idx` (`subjects_id` ASC) ,
  CONSTRAINT `fk_topics_subjects1`
    FOREIGN KEY (`subjects_id`)
    REFERENCES `mnu`.`subjects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`questions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `text` TEXT(280) NOT NULL,
  `number_of_choices` TINYINT(1) UNSIGNED NOT NULL,
  `topics_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_questions_topics1_idx` (`topics_id` ASC) ,
  CONSTRAINT `fk_questions_topics1`
    FOREIGN KEY (`topics_id`)
    REFERENCES `mnu`.`topics` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`exams`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`exams` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `number_of_questions` SMALLINT(3) UNSIGNED NOT NULL,
  `duration` SMALLINT(3) UNSIGNED NOT NULL,
  `subjects_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_exams_subjects1_idx` (`subjects_id` ASC) ,
  CONSTRAINT `fk_exams_subjects1`
    FOREIGN KEY (`subjects_id`)
    REFERENCES `mnu`.`subjects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`exam_has_question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`exam_has_question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `questions_id` INT NOT NULL,
  `exams_id` INT NOT NULL,
  INDEX `fk_exam_has_question_questions1_idx` (`questions_id` ASC) ,
  INDEX `fk_exam_has_question_exams1_idx` (`exams_id` ASC) ,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_exam_has_question_questions1`
    FOREIGN KEY (`questions_id`)
    REFERENCES `mnu`.`questions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_exam_has_question_exams1`
    FOREIGN KEY (`exams_id`)
    REFERENCES `mnu`.`exams` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`exam_centers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`exam_centers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`exam_center_has_exam`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`exam_center_has_exam` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `exams_id` INT NOT NULL,
  `exam_centers_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_exam_center_has_exam_exams1_idx` (`exams_id` ASC) ,
  INDEX `fk_exam_center_has_exam_exam_centers1_idx` (`exam_centers_id` ASC) ,
  CONSTRAINT `fk_exam_center_has_exam_exams1`
    FOREIGN KEY (`exams_id`)
    REFERENCES `mnu`.`exams` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_exam_center_has_exam_exam_centers1`
    FOREIGN KEY (`exam_centers_id`)
    REFERENCES `mnu`.`exam_centers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`choices`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`choices` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `text` TEXT(280) NULL,
  `is_correct` TINYINT(1) UNSIGNED NOT NULL,
  `questions_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_choices_questions1_idx` (`questions_id` ASC) ,
  CONSTRAINT `fk_choices_questions1`
    FOREIGN KEY (`questions_id`)
    REFERENCES `mnu`.`questions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `middle_name` VARCHAR(45) NOT NULL,
  `profile_picture` BLOB NULL,
  `roles_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_users_roles1_idx` (`roles_id` ASC) ,
  CONSTRAINT `fk_users_roles1`
    FOREIGN KEY (`roles_id`)
    REFERENCES `mnu`.`roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`students`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`students` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `enroll_date` DATE NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  CONSTRAINT `user_id`
    FOREIGN KEY (`id`)
    REFERENCES `mnu`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`student_took_exam`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`student_took_exam` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `exam_centers_id` INT NOT NULL,
  `choices_id` INT NOT NULL,
  `students_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_student_took_exam_exam_centers1_idx` (`exam_centers_id` ASC) ,
  INDEX `fk_student_took_exam_choices1_idx` (`choices_id` ASC) ,
  INDEX `fk_student_took_exam_students1_idx` (`students_id` ASC) ,
  CONSTRAINT `fk_student_took_exam_exam_centers1`
    FOREIGN KEY (`exam_centers_id`)
    REFERENCES `mnu`.`exam_centers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_took_exam_choices1`
    FOREIGN KEY (`choices_id`)
    REFERENCES `mnu`.`choices` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_took_exam_students1`
    FOREIGN KEY (`students_id`)
    REFERENCES `mnu`.`students` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`permissions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`permissions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mnu`.`role_has_permission`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mnu`.`role_has_permission` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `roles_id` INT NOT NULL,
  `permissions_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_role_has_permission_roles1_idx` (`roles_id` ASC) ,
  INDEX `fk_role_has_permission_permissions1_idx` (`permissions_id` ASC) ,
  CONSTRAINT `fk_role_has_permission_roles1`
    FOREIGN KEY (`roles_id`)
    REFERENCES `mnu`.`roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_has_permission_permissions1`
    FOREIGN KEY (`permissions_id`)
    REFERENCES `mnu`.`permissions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
