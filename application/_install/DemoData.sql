DELETE FROM `user` WHERE id < 100;
DELETE FROM `exam` WHERE id < 100;
DELETE FROM `role_has_permission` WHERE id < 100;
DELETE FROM `role` WHERE id < 100;
DELETE FROM `choice` WHERE id < 100;
DELETE FROM `question` WHERE id < 100;
DELETE FROM `permission` WHERE id < 100;
DELETE FROM `exam_center` WHERE id < 100;
DELETE FROM `topic` WHERE id < 100;
DELETE FROM `subject` WHERE id < 100;

INSERT INTO `subject` (`id`, `name`, `description`) VALUES
(1, 'Computer Science', 'It is the study of Computers and other stuff'),
(2, "Math", "This subject teaches elementary mathematics."),
(3, "Physics", "This is physics"),
(4, 'Law', 'the study of Law'),
(5, "English", "test"),
(6, "Biology", "this is biology"),
(7, "Chemistry", "this is chemistry"),
(8, "History", "this is history"),
(9, "Geography", "this is geography"),
(10, "Civics", "this is civics"),
(11, "Music", "this is music"),
(12, "Art", "this is art"),
(13, "PE", "this is PE"),
(14, "Health", "this is health"),
(15, "Business", "this is business"),
(16, "Economics", "this is economics"),
(17, "Psychology", "this is psychology"),
(18, "Sociology", "this is sociology"),
(19, "Political Science", "this is political science"),
(20, "Geography", "this is geography"),
(21, "History", "this is history"),
(22, "Philosophy", "this is philosophy"),
(23, "Religious Studies", "this is religious studies"),
(24, "Theatre", "this is theatre"),
(25, "Film", "this is film"),
(26, "Drama", "this is drama"),
(27, "Music", "this is music"),
(28, "Art", "this is art"),
(29, "PE", "this is PE"),
(30, "Health", "this is health"),
(31, "Business", "this is business"),
(32, "Economics", "this is economics"),
(33, "Psychology", "this is psychology"),
(34, "Sociology", "this is sociology"),
(35, "Political Science", "this is political science"),
(36, "Geography", "this is geography"),
(37, "History", "this is history"),
(38, "Philosophy", "this is philosophy"),
(39, "Religious Studies", "this is religious studies"),
(40, "Theatre", "this is theatre"),
(41, "Film", "this is film"),
(42, "Drama", "this is drama"),
(43, "Music", "this is music"),
(44, "Art", "this is art"),
(45, "PE", "this is PE");


INSERT INTO `topic` (`id`, `name`, `description`, `subject_id`) VALUES
(1, 'Types', 'it is about static type systems.', 1),
(2, "Algebra", "This is another topic in the math subject", 2),
(3, 'Local Law', 'the study of Local Law', 4),
(4, "Analysis", "This topic is part of the mathematics subject", 2),
(5, "Relativity", "testing", 3),
;

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
(2, 'What is 1+1 equals to?', 3, 2),
(3, "Let f(x)=x^2. What is f\'(2)?", 4, 2),
(4, "What is physics", 5, 5);

INSERT INTO `choice` (`id`, `text`, `is_correct`, `question_id`) VALUES
(1, 'INT', 0, 1),
(2, 'Long int', 0, 1),
(3, 'Float', 1, 1),
(4, '2', 1, 2),
(5, '1', 0, 2),
(1, "f\'(2)=1", 0, 3),
(2, "f\'(2)=2", 0, 3),
(3, "f\'(2)=3", 0, 3),
(4, "f\'(2)=4", 1, 3);

INSERT INTO `exam` (`id`, `name`, `number_of_questions`, `duration`, `subject_id`) VALUES
(1, "", 1, 60, 1);

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'ROOT::ADMIN'),
(8, 'Admin'),
(5, 'Guest'),
(4, 'Parent'),
(2, 'Student'),
(3, 'Teacher'),
(6, 'TestAdmin'),
(7, "TestAdminn"),
(8, 'Text Center Admin');

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
(36, 1, 36),
(37, 2, 23);

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
(18, 'testadmin', '202cb962ac59075b964b07152d234b70', 'TestCenter', 'Admin', NULL, NULL, 6),
(19, "user1", "testpassword", "User1", "Lname", "MidName", NULL, 1),
(21, "mail", "827ccb0eea8a706c4c34a16891f84e7b", "RealUser", "UserLname", "", NULL, 2),
(22, "myadmin", "202cb962ac59075b964b07152d234b70", "AdminUser", "AdminLname", "middleName1", "myadmin.jpeg", 1),
(23, "teacher", "202cb962ac59075b964b07152d234b70", "Teacher", "TLname", "", NULL, 2),
(24, "newuser", "202cb962ac59075b964b07152d234b70", "New", "User", "", NULL, 2),
(25, "newuser1", "202cb962ac59075b964b07152d234b70", "New", "User", "", NULL, 2),
(26, "newuser2", "202cb962ac59075b964b07152d234b70", "New", "modified", "", "newuser2.png", 2),
(27, "omar.a", "202cb962ac59075b964b07152d234b70", "Omar", "Ahmad", "middleName", NULL, 2),
(28, "bob", "202cb962ac59075b964b07152d234b70", "Newacc", "Lname", "MyMiddleName", "bob.jpeg", 2),
(29, "radmin", "a9b7b5912b7043805c326b62c636e069", "Root", "Admin", "", NULL, 1)
(30, "profpic", "a9b7b5912b7043805c326b62c636e069", "With", "Profile", "", "profpic.jpg", 2),
(31, "centeradmin", "123", "TestCenter", "Admin", "", NULL, 6),
(32, "testadmin", "202cb962ac59075b964b07152d234b70", "TestCenter", "Admin", "", NULL, 6),
-- last query doesn't need a semicolon