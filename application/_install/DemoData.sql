
INSERT INTO `choice` (`id`, `text`, `is_correct`, `question_id`) VALUES
(1, 'INT', 0, 1),
(2, 'Long int', 0, 1),
(3, 'Float', 1, 1),
(4, '2', 1, 2),
(5, '1', 0, 2);

INSERT INTO `exam_center` (`id`, `name`, `description`) VALUES
(1, 'Hiast Center', 'A center in the hiast...'),
(2, 'FourSeason', 'test center gf');

INSERT INTO `permission` (`id`, `name`) VALUES
(1, 'add_exam_center'),
(2, 'assign_role'),
(3, 'change_role_permissions'),
(4, 'create_permission'),
(5, 'create_question'),
(6, 'create_role'),
(7, 'create_subject'),
(8, 'create_topic'),
(9, 'create_user'),
(10, 'delete_exam_center'),
(11, 'delete_permission'),
(12, 'delete_question'),
(13, 'delete_role'),
(14, 'delete_student'),
(15, 'delete_subject'),
(16, 'delete_topic'),
(17, 'delete_user'),
(18, 'enroll_student'),
(19, 'generate_exam'),
(20, 'grant_permission'),
(21, 'read_exam'),
(22, 'read_exam_center'),
(23, 'read_question'),
(24, 'read_role'),
(25, 'read_student'),
(26, 'read_subject'),
(27, 'read_topic'),
(28, 'read_user'),
(29, 'take_exam'),
(30, 'write_exam'),
(31, 'write_exam_center'),
(32, 'write_question'),
(33, 'write_student'),
(34, 'write_subject'),
(35, 'write_topic'),
(36, 'write_user');

INSERT INTO `question` (`id`, `text`, `number_of_choices`, `topic_id`) VALUES
(1, 'Which is bigger?', 3, 1),
(2, 'What is 1+1 equals to?', 3, 2);

INSERT INTO `role` (`id`, `name`) VALUES
(8, 'Admin'),
(5, 'Guest'),
(4, 'Parent'),
(1, 'ROOT::ADMIN'),
(2, 'Student'),
(3, 'Teacher'),
(6, 'TestAdmin'),
(7, 'Text Center Admin');

INSERT INTO `role_has_permission` (`id`, `role_id`, `permission_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16),
(17, 1, 17),
(18, 1, 18),
(19, 1, 19),
(20, 1, 20),
(21, 1, 21),
(22, 1, 22),
(23, 1, 23),
(24, 1, 24),
(25, 1, 25),
(26, 1, 26),
(27, 1, 27),
(28, 1, 28),
(29, 1, 29),
(30, 1, 30),
(31, 1, 31),
(32, 1, 32),
(33, 1, 33),
(34, 1, 34),
(35, 1, 35),
(36, 1, 36);

INSERT INTO `subject` (`id`, `name`, `description`) VALUES
(1, 'Computer Science', 'It is the study of Computers and other stuff'),
(2, 'Math', 'the study of numbers'),
(3, 'Physics', 'the study of not numbers'),
(4, 'Law', 'the study of Law');

INSERT INTO `topic` (`id`, `name`, `description`, `subject_id`) VALUES
(1, 'Types', 'it is about static type systems.', 1),
(2, 'Relativity', 'Hard', 3),
(3, 'Local Law', 'the study of Local Law', 4);

INSERT INTO `user` (`id`, `username`, `password`, `first_name`, `last_name`, `middle_name`, `profile_picture`, `role_id`) VALUES
(1, 'NasoohOlabi', '81dc9bdb52d04dc20036dbd8313ed055', 'Nasooh', 'Olabi', 'Yaser', 'NasoohOlabi.jpg', 1),
(2, 'NassouhAlOlabi', '202cb962ac59075b964b07152d234b70', 'Nassouh', 'AlOlabi', 'Yasser', NULL, 1),
(3, 'MSmith', 'e268f98f247841960366c10ea099a67e', 'Michell', 'Smith', 'car', 'MSmith.jpg', 1),
(4, 'BrandonFreeman', 'e268f98f247841960366c10ea099a67e', 'Brandon', 'Freeman', 'M.', 'BrandonFreeman.jpg', 1),
(5, 'ColleenDiaz', 'e268f98f247841960366c10ea099a67e', 'Colleen', 'Diaz', 'L.', 'ColleenDiaz.jpg', 1),
(6, 'NicoleFord', 'e268f98f247841960366c10ea099a67e', 'Nicole', 'Ford', 'Nancy', 'NicoleFord.jpg', 1),
(7, 'AddisonOlson', 'e268f98f247841960366c10ea099a67e', 'Addison', 'Olson', 'Mike', 'AddisonOlson.jpg', 1),
(8, 'DouglasFletcher', 'e268f98f247841960366c10ea099a67e', 'Douglas', 'Fletcher', 'John', 'DouglasFletcher.jpg', 1),
(9, 'FloydSteward', 'e268f98f247841960366c10ea099a67e', 'Floyd', 'Steward', 'Jack', 'FloydSteward.jpg', 1),
(10, 'JesseBrown', 'e268f98f247841960366c10ea099a67e', 'Jesse', 'Brown', 'Axel', 'JesseBrown.jpg', 1),
(11, 'OscarMorgan', 'e268f98f247841960366c10ea099a67e', 'Oscar', 'Morgan', 'Gotee', 'OscarMorgan.jpg', 1),
(12, 'SohamPalmer', 'e268f98f247841960366c10ea099a67e', 'Soham', 'Palmer', 'Kid', 'SohamPalmer.jpg', 1),
(13, 'EdithSanchez', 'e268f98f247841960366c10ea099a67e', 'Edith', 'Sanchez', 'Blonde', 'EdithSanchez.jpg', 1),
(14, 'KimSnyder', 'e268f98f247841960366c10ea099a67e', 'Kim', 'Snyder', 'Red', 'KimSnyder.jpg', 1),
(15, 'SandraNeal', 'e268f98f247841960366c10ea099a67e', 'Sandra', 'Neal', 'Kim', 'SandraNeal.jpg', 1),
(16, 'ToniRhodes', 'e268f98f247841960366c10ea099a67e', 'Toni', 'Rhodes', 'Jordan', 'ToniRhodes.jpg', 1),
(17, 'MarcDemo', 'e268f98f247841960366c10ea099a67e', 'Marc', 'Demo', 'Angry', 'MarcDemo.jpg', 1),
(18, 'testadmin', '202cb962ac59075b964b07152d234b70', 'TestCenter', 'Admin', NULL, NULL, 6);
