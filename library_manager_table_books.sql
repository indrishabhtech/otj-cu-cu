
-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category_id`, `description`) VALUES
(5, 'today is thursday', 'rishabh ji', 5, 'hacker hai bhai hacker'),
(6, 'New book', 'Anonmyos', 2, 'A new books for students'),
(7, 'omg', 'mog', 1, 'oagn'),
(9, 'raja ji', 'raja ji', 1, 'a book by raja ji');
