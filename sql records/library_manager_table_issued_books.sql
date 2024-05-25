
-- --------------------------------------------------------

--
-- Table structure for table `issued_books`
--

CREATE TABLE `issued_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issued_books`
--

INSERT INTO `issued_books` (`id`, `user_id`, `book_id`, `issued_at`) VALUES
(1, 4, 5, '2024-05-22 09:33:34'),
(2, 5, 6, '2024-05-22 09:39:06'),
(3, NULL, NULL, '2024-05-22 09:56:25'),
(4, NULL, NULL, '2024-05-22 09:56:41'),
(5, 5, 6, '2024-05-22 10:14:23'),
(6, NULL, NULL, '2024-05-22 10:14:34'),
(7, NULL, NULL, '2024-05-22 10:28:51'),
(8, NULL, NULL, '2024-05-22 10:47:53'),
(9, NULL, NULL, '2024-05-22 10:49:02'),
(10, 4, 5, '2024-05-23 11:02:06');
