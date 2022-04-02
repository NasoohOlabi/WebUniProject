INSERT INTO `subject` (`name`, `description`) VALUES
('Computer Science', 'It is the study of Computers and other stuff'),
('Math', 'the study of numbers'),
('Physics', 'the study of not numbers'),
('Law', 'the study of Law');
INSERT INTO `topic` (`name`, `description`, `subject_id`) VALUES
('Types', 'it is about static type systems.', 1),
('Relativity', 'Hard', 3),
('Local Law', 'the study of Local Law', 4);
INSERT INTO `question` (`text`, `number_of_choices`, `topic_id`) VALUES
('Which is bigger?', 3, 1),
('What is 1+1 equals to?', 3, 2);
INSERT INTO `choice` (`text`, `is_correct`, `question_id`) VALUES
('INT', 0, 1),
('Long int', 0, 1),
('Float', 1, 1),
('2', 1, 2),
('1', 0, 2);
INSERT INTO `exam_center` (`name`, `description`) VALUES
('Hiast Center', 'A center in the hiast...'),
('FourSeason', 'test center gf');
INSERT INTO `permission` (`name`) VALUES
('add_exam_center'),
('assign_role'),
('change_role_permissions'),
('create_permission'),
('create_question'),
('create_role'),
('create_subject'),
('create_topic'),
('create_user'),
('delete_exam_center'),
('delete_permission'),
('delete_question'),
('delete_role'),
('delete_student'),
('delete_subject'),
('delete_topic'),
('delete_user'),
('enroll_student'),
('generate_exam'),
('grant_permission'),
('read_exam'),
('read_exam_center'),
('read_question'),
('read_role'),
('read_student'),
('read_subject'),
('read_topic'),
('read_user'),
('take_exam'),
('write_exam'),
('write_exam_center'),
('write_question'),
('write_student'),
('write_subject'),
('write_topic'),
('write_user');
INSERT INTO `role` (`name`) VALUES
('ROOT::ADMIN'),
('Student'),
('Teacher'),
('Parent'),
('Guest'),
('TestAdmin'),
('Text Center Admin'),
('Admin');
INSERT INTO `role_has_permission` (`role_id`, `permission_id`) VALUES
(1, 1),(1, 2),(1, 3),(1, 4),(1, 5),(1, 6),(1, 7),(1, 8),(1, 9),(1, 10),(1, 11),(1, 12),(1, 13),(1, 14),(1, 15),(1, 16),(1, 17),(1, 18),(1, 19),(1, 20),(1, 21),(1, 22),(1, 23),(1, 24),(1, 25),(1, 26),(1, 27),(1, 28),(1, 29),(1, 30),(1, 31),(1, 32),(1, 33),(1, 34),(1, 35),(1, 36);
INSERT INTO `user` (`username`, `password`, `first_name`, `last_name`, `middle_name`, `profile_picture`, `role_id`) VALUES
('NasoohOlabi', '81dc9bdb52d04dc20036dbd8313ed055', 'Nasooh', 'Olabi', 'Yaser', 'NasoohOlabi.jpg', 1),
('NassouhAlOlabi', '202cb962ac59075b964b07152d234b70', 'Nassouh', 'AlOlabi', 'Yasser', NULL, 1),
('MSmith', 'e268f98f247841960366c10ea099a67e', 'Michell', 'Smith', 'car', 'MSmith.jpg', 1),
('BrandonFreeman', 'e268f98f247841960366c10ea099a67e', 'Brandon', 'Freeman', 'M.', 'BrandonFreeman.jpg', 1),
('ColleenDiaz', 'e268f98f247841960366c10ea099a67e', 'Colleen', 'Diaz', 'L.', 'ColleenDiaz.jpg', 1),
('NicoleFord', 'e268f98f247841960366c10ea099a67e', 'Nicole', 'Ford', 'Nancy', 'NicoleFord.jpg', 1),
('AddisonOlson', 'e268f98f247841960366c10ea099a67e', 'Addison', 'Olson', 'Mike', 'AddisonOlson.jpg', 1),
('DouglasFletcher', 'e268f98f247841960366c10ea099a67e', 'Douglas', 'Fletcher', 'John', 'DouglasFletcher.jpg', 1),
('FloydSteward', 'e268f98f247841960366c10ea099a67e', 'Floyd', 'Steward', 'Jack', 'FloydSteward.jpg', 1),
('JesseBrown', 'e268f98f247841960366c10ea099a67e', 'Jesse', 'Brown', 'Axel', 'JesseBrown.jpg', 1),
('OscarMorgan', 'e268f98f247841960366c10ea099a67e', 'Oscar', 'Morgan', 'Gotee', 'OscarMorgan.jpg', 1),
('SohamPalmer', 'e268f98f247841960366c10ea099a67e', 'Soham', 'Palmer', 'Kid', 'SohamPalmer.jpg', 1),
('EdithSanchez', 'e268f98f247841960366c10ea099a67e', 'Edith', 'Sanchez', 'Blonde', 'EdithSanchez.jpg', 1),
('KimSnyder', 'e268f98f247841960366c10ea099a67e', 'Kim', 'Snyder', 'Red', 'KimSnyder.jpg', 1),
('SandraNeal', 'e268f98f247841960366c10ea099a67e', 'Sandra', 'Neal', 'Kim', 'SandraNeal.jpg', 1),
('ToniRhodes', 'e268f98f247841960366c10ea099a67e', 'Toni', 'Rhodes', 'Jordan', 'ToniRhodes.jpg', 1),
('MarcDemo', 'e268f98f247841960366c10ea099a67e', 'Marc', 'Demo', 'Angry', 'MarcDemo.jpg', 1),
('testadmin', '202cb962ac59075b964b07152d234b70', 'TestCenter', 'Admin', NULL, NULL, 6);
