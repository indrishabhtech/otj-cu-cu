
-- --------------------------------------------------------

--
-- Table structure for table `student_queries`
--

CREATE TABLE `student_queries` (
  `id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_class` varchar(255) NOT NULL,
  `issue` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_queries`
--

INSERT INTO `student_queries` (`id`, `student_name`, `student_class`, `issue`, `created_at`) VALUES
(1, 'Rishabh', 'bca', 'Sir math book', '2024-05-22 18:23:38'),
(2, 'Bhavna', 'bsc', 'i we need math book asap', '2024-05-23 03:04:13'),
(3, 'Depak', 'bca', 'we need math book', '2024-05-23 11:13:03');
