
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'rishabh', '$2y$10$EJmG3YxpMCJlfuWGP9u.aOqpIzXVCebJzn85M0T5jxmyRECzg8AAG', '2024-05-21 09:44:05'),
(2, 'Naina', '$2y$10$gKR5O7mdtRDkwLbzZGbmmuhTSoHCjfMs2ZhGho4j.hsGrb8Cp4WbG', '2024-05-21 09:55:56'),
(3, 'Kavya', '$2y$10$vT0pzef9Lk0n1d1X4pFlD.UxZAvKeHbj6yJ.FNZ0uv9HfQ1cYYz1.', '2024-05-21 18:09:05'),
(4, 'Mehak', '$2y$10$mkMrz69ZnWz.K5pdxYB//ep833ice8kFSewjOhdPXtmJ4GriE.hXq', '2024-05-21 18:12:12'),
(5, 'Suman', '$2y$10$zeIZ1XHLwSf4A2xQJ7Armu3wOXw8B.yi5Wlazzzea9tfnX6CLaPt2', '2024-05-22 09:24:23');
