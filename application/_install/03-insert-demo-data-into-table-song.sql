-- INSERT INTO `role_has_permission` VALUES (1,1);
INSERT INTO `permission`(`name`)
VALUES ('read_questions');
INSERT INTO `permission`(`name`)
VALUES ('write_questions');
INSERT INTO `permission`(`name`)
VALUES ('write_topic');
INSERT INTO `permission`(`name`)
VALUES ('write_subject');
INSERT INTO `permission`(`name`)
VALUES ('write_exam_center');
INSERT INTO `permission`(`name`)
VALUES ('write_role');
INSERT INTO `permission`(`name`)
VALUES ('write_permission');
INSERT INTO `permission`(`name`)
VALUES ('grant_role');
INSERT INTO `permission`(`name`)
VALUES ('grant_permission');
INSERT INTO `role`(`name`)
VALUES ('ROOT::ADMIN');
-- INSERT INTO `role`(`name`) VALUES ('admin');
-- INSERT INTO `role`(`name`) VALUES ('exam_center_admin');
-- INSERT INTO `role`(`name`) VALUES ('vister');
-- INSERT INTO `role`(`name`) VALUES ('student');
INSERT INTO `role_has_permission`(`role_id`, `permission_id`)
VALUES (1, 1);
INSERT INTO `role_has_permission`(`role_id`, `permission_id`)
VALUES (1, 2);
INSERT INTO `role_has_permission`(`role_id`, `permission_id`)
VALUES (1, 3);
INSERT INTO `role_has_permission`(`role_id`, `permission_id`)
VALUES (1, 4);
INSERT INTO `role_has_permission`(`role_id`, `permission_id`)
VALUES (1, 5);
INSERT INTO `role_has_permission`(`role_id`, `permission_id`)
VALUES (1, 6);
INSERT INTO `role_has_permission`(`role_id`, `permission_id`)
VALUES (1, 7);
INSERT INTO `role_has_permission`(`role_id`, `permission_id`)
VALUES (1, 8);
INSERT INTO `role_has_permission`(`role_id`, `permission_id`)
VALUES (1, 9);
INSERT INTO `subject`(`name`, `description`)
VALUES (
        'Computer Science',
        "It\'s the study of Computers and other stuff"
    );
INSERT INTO `topic`(`name`, `description`, `subject_id`)
VALUES ('Types', "it\'s about static type systems", 1);
INSERT INTO `question`(`text`, `number_of_choices`, `topic_id`)
VALUES ('Which is bigger?', 3, 1);
INSERT INTO `choice`(`text`, `is_correct`, `question_id`)
VALUES ("INT", 0, 1);
INSERT INTO `choice`(`text`, `is_correct`, `question_id`)
VALUES ("Long int", 0, 1);
INSERT INTO `choice`(`text`, `is_correct`, `question_id`)
VALUES ("Float", 1, 1);
INSERT INTO `exam`(`number_of_questions`, `duration`, `subject_id`)
VALUES (1, 5, 1);
INSERT INTO `exam_center`(`name`, `description`)
VALUES ('Hiast Center', 'A center in the Higher...');
INSERT INTO `exam_center_has_exam`(`date`, `exam_id`, `exam_center_id`)
VALUES (curdate(), 1, 1);