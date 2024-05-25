
-- --------------------------------------------------------

--
-- Table structure for table `librarians`
--

CREATE TABLE `librarians` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `librarians`
--

INSERT INTO `librarians` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'mama sir', '$2y$10$xFZsbUaK9huqYzk8JXCDw.M2TmTVuOCv/uMecFED2soJEGMf.e6/6', '2024-05-21 18:37:38');
