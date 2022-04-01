-- INSERT INTO `role_has_permission` VALUES (1,1);
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


INSERT INTO `permission`(`name`) VALUES ('grant_permission'), ('create_permission'), ('delete_permission'), ('create_question'), ('read_question'), ('write_question'), ('delete_question'), ('create_user'), ('read_user'), ('write_user'), ('delete_user'), ('create_role'), ('read_role'), ('change_role_permissions'), ('delete_role'), ('assign_role'), ('enroll_student'), ('read_student'), ('write_student'), ('delete_student'), ('create_topic'), ('read_topic'), ('write_topic'), ('delete_topic'), ('create_subject'), ('read_subject'), ('write_subject'), ('delete_subject'), ('add_exam_center'), ('read_exam_center'), ('write_exam_center'), ('delete_exam_center'), ('generate_exam'), ('read_exam'), ('take_exam'), ('write_exam');
-- give admin all permissions
INSERT INTO `role_has_permission`(`role_id`, `permission_id`) VALUES (60, 12), (60, 14), (60, 15), (60, 16), (60, 17), (60, 18), (60, 19), (60, 20), (60, 21), (60, 22), (60, 23), (60, 24), (60, 25), (60, 26), (60, 27), (60, 28), (60, 29), (60, 30), (60, 31), (60, 32), (60, 33), (60, 34), (60, 35), (60, 36);

INSERT INTO `user` (`id`, `username`, `password`, `first_name`, `last_name`, `middle_name`, `profile_picture`, `role_id`) VALUES
(68, 'MSmith', 'e268f98f247841960366c10ea099a67e', 'Michell', 'Smith', 'car', 'MSmith.jpg', 2),
(69, 'BrandonFreeman', 'e268f98f247841960366c10ea099a67e', 'Brandon', 'Freeman', 'M.', 'BrandonFreeman.jpg', 1),
(70, 'ColleenDiaz', 'e268f98f247841960366c10ea099a67e', 'Colleen', 'Diaz', 'L.', 'ColleenDiaz.jpg', 1),
(71, 'NicoleFord', 'e268f98f247841960366c10ea099a67e', 'Nicole', 'Ford', 'Nancy', 'NicoleFord.jpg', 2),
(72, 'AddisonOlson', 'e268f98f247841960366c10ea099a67e', 'Addison', 'Olson', 'Mike', 'AddisonOlson.jpg', 2),
(73, 'DouglasFletcher', 'e268f98f247841960366c10ea099a67e', 'Douglas', 'Fletcher', 'John', 'DouglasFletcher.jpg', 2),
(74, 'FloydSteward', 'e268f98f247841960366c10ea099a67e', 'Floyd', 'Steward', 'Jack', 'FloydSteward.jpg', 2),
(75, 'JesseBrown', 'e268f98f247841960366c10ea099a67e', 'Jesse', 'Brown', 'Axel', 'JesseBrown.jpg', 2),
(76, 'OscarMorgan', 'e268f98f247841960366c10ea099a67e', 'Oscar', 'Morgan', 'Gotee', 'OscarMorgan.jpg', 2),
(77, 'SohamPalmer', 'e268f98f247841960366c10ea099a67e', 'Soham', 'Palmer', 'Kid', 'SohamPalmer.jpg', 2),
(78, 'EdithSanchez', 'e268f98f247841960366c10ea099a67e', 'Edith', 'Sanchez', 'Blonde', 'EdithSanchez.jpg', 2),
(79, 'KimSnyder', 'e268f98f247841960366c10ea099a67e', 'Kim', 'Snyder', 'Red', 'KimSnyder.jpg', 2),
(80, 'SandraNeal', 'e268f98f247841960366c10ea099a67e', 'Sandra', 'Neal', 'Kim', 'SandraNeal.jpg', 2),
(81, 'ToniRhodes', 'e268f98f247841960366c10ea099a67e', 'Toni', 'Rhodes', 'Jordan', 'ToniRhodes.jpg', 2),
(82, 'MarcDemo', 'e268f98f247841960366c10ea099a67e', 'Marc', 'Demo', 'Angry', 'MarcDemo.jpg', 2);