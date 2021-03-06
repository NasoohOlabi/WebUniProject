DELETE FROM `student` WHERE id < 100;
DELETE FROM `exam_center` WHERE id < 100;
DELETE FROM `user` WHERE id < 100;
DELETE FROM `exam` WHERE id < 100;
DELETE FROM `role_has_permission` WHERE id < 100;
DELETE FROM `role` WHERE id < 100;
DELETE FROM `choice` WHERE id < 100;
DELETE FROM `question` WHERE id < 100;
DELETE FROM `permission` WHERE id < 100;
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
(8, "Civics", "this is civics"),
(9, "Religious Studies", "this is religious studies"),
(10, "Theatre", "this is theatre"),
(11, "Film", "this is film"),
(12, "Drama", "this is drama"),
(13, "Music", "this is music"),
(14, "Art", "this is art"),
(15, "PE", "this is PE"),
(16, "Health", "this is health"),
(17, "Business", "this is business"),
(18, "Economics", "this is economics"),
(19, "Psychology", "this is psychology"),
(20, "Sociology", "this is sociology"),
(21, "Political Science", "this is political science"),
(22, "History", "this is history");


INSERT INTO `topic` (`id`, `name`, `description`, `subject_id`) VALUES
(1, 'Types', 'it is about static type systems.', 1),
(2, "Algebra", "This is another topic in the math subject", 2),
(3, 'Local Law', 'the study of Local Law', 4),
(4, "Analysis", "This topic is part of the mathematics subject", 2),
(5, "Relativity", "testing", 3),
(6, 'Geometry', 'The study of shapes', 2);

INSERT INTO `permission` (`id`, `name`) VALUES
(1, 'add_exam_center'),
(2, 'assign_role'),
(4, 'create_permission'),
(5, 'create_question'),
(6, 'create_role'),
(7, 'create_subject'),
(8, 'create_topic'),
(9, 'create_user'),
(18, 'enroll_student'),(19, 'generate_exam'),
-- ########################################### 10 lines separator
(10, 'delete_exam_center'),
(11, 'delete_permission'),
(12, 'delete_question'),
(13, 'delete_role'),
(14, 'delete_student'),
(15, 'delete_subject'),
(16, 'delete_topic'),
(17, 'delete_user'),
(37, 'delete_exam'),
-- ########################################### 10 lines separator
(21, 'read_exam'),
(22, 'read_exam_center'),
(23, 'read_question'),
(24, 'read_role'),
(25, 'read_student'),
(26, 'read_subject'),
(27, 'read_topic'),
(28, 'read_user'),
(39, 'read_student_exam'),
-- ########################################### 10 lines separator
(29, 'take_exam'),
(3, 'change_role_permissions'),
(20, 'grant_permission'),
(30, 'write_exam'),
(31, 'write_exam_center'),
(32, 'write_question'),
(33, 'write_student'),
(34, 'write_subject'),
(35, 'write_topic'),
(38,'reassign_role'),
-- ########################################### 10 lines separator
(40,'read_student_exam_has_question'),
(41,'read_student_exam_has_choice'),
(42, 'write_student_exam'),
(43, 'create_student_exam_has_choice'),
(36, 'write_user');
-- max id: 41


INSERT INTO `question` (`id`, `text`, `topic_id`,`active`) VALUES
(1, 'Which is bigger?',  1,1),
(2, 'What is 1+1 equals to?',  2,1),
(3, "Let f(x)=x^2. What is f\'(2)?",  4,1),
(4, "What is physics",  5,1),
(5, 'if x+1=3 then x = ?',  2,1),
(6, 'Is f(x) = -x non decreasing?',  4,1),
(7, 'what is 3+2 ?',  2,1),
(8, 'Is f(x) = x*x non decreasing?',  4,1),
(9, 'The area of Square?',  6,1),
(10, 'The area of Rectangle?',  6,1),
(11, 'The area of Circle?',  6,1);

INSERT INTO `choice` (`id`, `text`, `is_correct`, `question_id`) VALUES
(1, 'INT', 0, 1),
(2, 'Long int', 0, 1),
(3, 'Float', 1, 1),
(4, '2', 1, 2),
(5, '1', 0, 2),
(6, "f\'(2)=1", 0, 3),
(7, "f\'(2)=2", 0, 3),
(8, "f\'(2)=3", 0, 3),
(9, "f\'(2)=4", 1, 3),
(10, 'x = 2', 1, 5),
(11, 'x = -1', 0, 5),
(12, 'x = 10', 0, 5),
(13, 'Yes', 0, 6),
(14, 'No', 1, 6),
(15, '5', 1, 7),
(16, '1', 0, 7),
(17, '4', 0, 7),
(18, 'No', 1, 8),
(19, 'Yes', 0, 8),
(20, 'length*length', 1, 9),
(21, 'length+length', 0, 9),
(22, 'width*height', 1, 10),
(23, '2(width+height)', 0, 10),
(24, 'pi*radius*radius', 1, 11),
(25, '2*pi*radius*radius', 0, 11);


INSERT INTO `exam` (`id`, `number_of_questions`, `duration`, `subject_id`) VALUES
(1, 1, 60, 1),
(2, 3, 60, 2);

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'ROOT::ADMIN'),
(2, 'Student'),
(3, 'Teacher'),
(4, 'Parent'),
(5, 'Guest'),
(6, 'TestAdmin'),
(7, 'Admin'),
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
(37, 2, 23),
(38, 6, 21),
(39, 6, 22),
(40, 6, 24),
(41, 6, 26),
(42, 6, 39),
(43, 6, 28),
(44, 2, 21),
(45, 2, 22),
(46, 2, 23),
(47, 2, 40),
(48, 2, 41),
(49, 2, 42),
(50, 2, 39),
(51, 2, 26),
(52, 2, 25),
(53, 2, 43),
(54, 2, 28),
(55, 2, 24);

INSERT INTO `user` (`id`, `username`, `password`, `first_name`, `last_name`, `middle_name`, `profile_picture`, `role_id`) VALUES
(1, 'NasoohOlabi', '202cb962ac59075b964b07152d234b70', 'Nasooh', 'Olabi', 'Yaser', 'NasoohOlabi.jpg', 1),
(2, 'NassouhAlOlabi', '202cb962ac59075b964b07152d234b70', 'Nassouh', 'AlOlabi', 'Yasser', NULL, 1),
(3, 'MSmith', '202cb962ac59075b964b07152d234b70', 'Michell', 'Smith', 'car', 'MSmith.jpg', 7),
(4, 'BrandonFreeman', '202cb962ac59075b964b07152d234b70', 'Brandon', 'Freeman', 'M.', 'BrandonFreeman.jpg', 6),
(5, 'ColleenDiaz', '202cb962ac59075b964b07152d234b70', 'Colleen', 'Diaz', 'L.', 'ColleenDiaz.jpg', 2),
(6, 'NicoleFord', '202cb962ac59075b964b07152d234b70', 'Nicole', 'Ford', 'Nancy', 'NicoleFord.jpg', 5),
(7, 'AddisonOlson', '202cb962ac59075b964b07152d234b70', 'Addison', 'Olson', 'Mike', 'AddisonOlson.jpg', 5),
(8, 'DouglasFletcher', '202cb962ac59075b964b07152d234b70', 'Douglas', 'Fletcher', 'John', 'DouglasFletcher.jpg', 5),
(9, 'FloydSteward', '202cb962ac59075b964b07152d234b70', 'Floyd', 'Steward', 'Jack', 'FloydSteward.jpg', 5),
(10, 'JesseBrown', '202cb962ac59075b964b07152d234b70', 'Jesse', 'Brown', 'Axel', 'JesseBrown.jpg', 5),
(11, 'OscarMorgan', '202cb962ac59075b964b07152d234b70', 'Oscar', 'Morgan', 'Gotee', 'OscarMorgan.jpg', 5),
(12, 'SohamPalmer', '202cb962ac59075b964b07152d234b70', 'Soham', 'Palmer', 'Kid', 'SohamPalmer.jpg', 5),
(13, 'EdithSanchez', '202cb962ac59075b964b07152d234b70', 'Edith', 'Sanchez', 'Blonde', 'EdithSanchez.jpg', 5),
(14, 'KimSnyder', '202cb962ac59075b964b07152d234b70', 'Kim', 'Snyder', 'Red', 'KimSnyder.jpg', 5),
(15, 'SandraNeal', '202cb962ac59075b964b07152d234b70', 'Sandra', 'Neal', 'Kim', 'SandraNeal.jpg', 5),
(16, 'ToniRhodes', '202cb962ac59075b964b07152d234b70', 'Toni', 'Rhodes', 'Jordan', 'ToniRhodes.jpg', 5),
(17, 'MarcDemo', '202cb962ac59075b964b07152d234b70', 'Marc', 'Demo', 'Angry', 'MarcDemo.jpg', 2),
(18, 'testadmin', '202cb962ac59075b964b07152d234b70', 'TestCenter', 'Admin', NULL, NULL, 6),
(19, "user1", "202cb962ac59075b964b07152d234b70", "User1", "Lname", "MidName", NULL, 1),
(21, "mail", "202cb962ac59075b964b07152d234b70", "RealUser", "UserLname", "", NULL, 2),
(22, "myadmin", "202cb962ac59075b964b07152d234b70", "AdminUser", "AdminLname", "middleName1", "myadmin.jpeg", 1),
(23, "teacher", "202cb962ac59075b964b07152d234b70", "Teacher", "TLname", "", NULL, 2),
(24, "newuser", "202cb962ac59075b964b07152d234b70", "New", "User", "", NULL, 2),
(25, "newuser1", "202cb962ac59075b964b07152d234b70", "New", "User", "", NULL, 2),
(26, "newuser2", "202cb962ac59075b964b07152d234b70", "New", "modified", "", "newuser2.png", 2),
(27, "omar.a", "202cb962ac59075b964b07152d234b70", "Omar", "Ahmad", "middleName", NULL, 2),
(28, "bob", "202cb962ac59075b964b07152d234b70", "Newacc", "Lname", "MyMiddleName", "bob.jpeg", 2),
(29, "radmin", "202cb962ac59075b964b07152d234b70", "Root", "Admin", "", NULL, 1),
(30, "profpic", "202cb962ac59075b964b07152d234b70", "With", "Profile", NULL, "profpic.jpg", 2),
(31, "centeradmin", "202cb962ac59075b964b07152d234b70", "TestCenter", "Admin", "", NULL, 6);


INSERT INTO `student` (`id`, `enroll_date`, `user_id`) VALUES
(1, '2022-04-12', 5), (2, '2022-04-01', 24),(3, '2022-04-12', 17);


INSERT INTO `exam_center` (`id`, `name`, `description`,`user_id`) VALUES
(1, 'Hiast Center', 'A center in the hiast...',18),
(2, 'FourSeason', 'test center gf',31)
-- last query doesn't need a semicolon