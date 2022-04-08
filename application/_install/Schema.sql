-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mnu
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `mnu` ;

-- -----------------------------------------------------
-- Schema mnu
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mnu` DEFAULT CHARACTER SET utf8mb4 ;
USE `mnu` ;

-- -----------------------------------------------------
-- Table `subject`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `subject` ;

CREATE TABLE IF NOT EXISTS `subject` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `topic`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `topic` ;

CREATE TABLE IF NOT EXISTS `topic` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `subject_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  INDEX `fk_topics_subjects1_idx` (`subject_id` ASC),
  CONSTRAINT `fk_topics_subjects1`
    FOREIGN KEY (`subject_id`)
    REFERENCES `subject` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `question` ;

CREATE TABLE IF NOT EXISTS `question` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `text` TEXT NOT NULL,
  `topic_id` INT(11) NOT NULL,
  `active` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_questions_topics1_idx` (`topic_id` ASC),
  CONSTRAINT `fk_questions_topics1`
    FOREIGN KEY (`topic_id`)
    REFERENCES `topic` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `choice`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `choice` ;

CREATE TABLE IF NOT EXISTS `choice` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `text` TEXT NOT NULL,
  `is_correct` TINYINT(1) UNSIGNED NOT NULL,
  `question_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_choices_questions1_idx` (`question_id` ASC),
  CONSTRAINT `fk_choices_questions1`
    FOREIGN KEY (`question_id`)
    REFERENCES `question` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `exam`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `exam` ;

CREATE TABLE IF NOT EXISTS `exam` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `number_of_questions` SMALLINT(3) UNSIGNED NOT NULL,
  `duration` SMALLINT(3) UNSIGNED NOT NULL,
  `subject_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_exams_subjects1_idx` (`subject_id` ASC),
  CONSTRAINT `fk_exams_subjects1`
    FOREIGN KEY (`subject_id`)
    REFERENCES `subject` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `exam_center`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `exam_center` ;

CREATE TABLE IF NOT EXISTS `exam_center` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `user_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  INDEX `fk_exam_center_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_exam_center_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `role` ;

CREATE TABLE IF NOT EXISTS `role` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `middle_name` VARCHAR(45) NULL DEFAULT NULL,
  `profile_picture` VARCHAR(50) NULL DEFAULT NULL,
  `role_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  INDEX `fk_users_roles1_idx` (`role_id` ASC),
  CONSTRAINT `fk_users_roles1`
    FOREIGN KEY (`role_id`)
    REFERENCES `role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `student`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `student` ;

CREATE TABLE IF NOT EXISTS `student` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `enroll_date` DATE NOT NULL,
  `user_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_student_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_student_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `student_exam`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `student_exam` ;

CREATE TABLE IF NOT EXISTS `student_exam` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `exam_id` INT(11) NOT NULL,
  `exam_center_id` INT(11) NOT NULL,
  `student_id` INT(11) NOT NULL,
  `qs_hash` VARCHAR(50) NOT NULL,
  `grade` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_exam_center_has_exam_exams1_idx` (`exam_id` ASC),
  INDEX `fk_exam_center_has_exam_exam_centers1_idx` (`exam_center_id` ASC),
  INDEX `fk_student_exam_student1_idx` (`student_id` ASC),
  UNIQUE INDEX `qs_hash_UNIQUE` (`qs_hash` ASC),
  CONSTRAINT `fk_exam_center_has_exam_exam_centers1`
    FOREIGN KEY (`exam_center_id`)
    REFERENCES `exam_center` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_exam_center_has_exam_exams1`
    FOREIGN KEY (`exam_id`)
    REFERENCES `exam` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_exam_student1`
    FOREIGN KEY (`student_id`)
    REFERENCES `student` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `student_exam_has_question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `student_exam_has_question` ;

CREATE TABLE IF NOT EXISTS `student_exam_has_question` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `question_id` INT(11) NOT NULL,
  `student_exam_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_exam_has_question_questions1_idx` (`question_id` ASC),
  INDEX `fk_exam_has_question_exam_center_has_exam1_idx` (`student_exam_id` ASC),
  CONSTRAINT `fk_exam_has_question_exam_center_has_exam1`
    FOREIGN KEY (`student_exam_id`)
    REFERENCES `student_exam` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_exam_has_question_questions1`
    FOREIGN KEY (`question_id`)
    REFERENCES `question` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `permission` ;

CREATE TABLE IF NOT EXISTS `permission` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `role_has_permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `role_has_permission` ;

CREATE TABLE IF NOT EXISTS `role_has_permission` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `role_id` INT(11) NOT NULL,
  `permission_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_role_has_permission_roles1_idx` (`role_id` ASC),
  INDEX `fk_role_has_permission_permissions1_idx` (`permission_id` ASC),
  CONSTRAINT `fk_role_has_permission_permissions1`
    FOREIGN KEY (`permission_id`)
    REFERENCES `permission` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_has_permission_roles1`
    FOREIGN KEY (`role_id`)
    REFERENCES `role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `student_exam_has_choice`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `student_exam_has_choice` ;

CREATE TABLE IF NOT EXISTS `student_exam_has_choice` (
  `id` INT(11) NOT NULL,
  `student_exam_id` INT(11) NOT NULL,
  `choice_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_student_exam_has_choice_choice1_idx` (`choice_id` ASC),
  INDEX `fk_student_exam_has_choice_student_exam1_idx` (`student_exam_id` ASC),
  UNIQUE INDEX `student_exam_id_UNIQUE` (`student_exam_id` ASC, `choice_id` ASC),
  CONSTRAINT `fk_student_exam_has_choice_student_exam1`
    FOREIGN KEY (`student_exam_id`)
    REFERENCES `student_exam` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_exam_has_choice_choice1`
    FOREIGN KEY (`choice_id`)
    REFERENCES `choice` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = utf8mb4;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS