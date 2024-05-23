
-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `notice` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `notice`, `created_at`) VALUES
(1, 'I will come tommarrow', '2024-05-23 11:33:38'),
(2, 'I have a internship for u guys...', '2024-05-23 17:09:00'),
(3, 'I have a internship for u boys', '2024-05-23 17:31:59'),
(4, 'ok', '2024-05-23 17:32:08'),
(5, 'ok', '2024-05-23 18:04:09'),
(6, 'hey everyone', '2024-05-23 18:04:36'),
(7, 'prajawal is a bad boy', '2024-05-23 18:11:39');
