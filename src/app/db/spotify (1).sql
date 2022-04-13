-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-server
-- Generation Time: Apr 13, 2022 at 03:57 AM
-- Server version: 8.0.19
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spotify`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `bearer_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `refresh_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `bearer_token`, `refresh_token`) VALUES
(1, 'vikas', 'vikas@cedcoss.com', '123', 'BQAN2nfeACnm0fFGDpASVVVNX7OL-IqOpPmR0O6zSZ_t20g_a1O_PrXYNojqgkXn3bUf5hFSe9B8b_2yccOjYqwIdMUITw0L657_dhZwI16dR7vG3ikCYXj4tN8NfUUOKWbO03LCFDqOBlZPokexUloofe1CGiz_hbUMFggKpjSeMg26DDjeR9nBJCdzhwxYD4WxRrdreO87PiWN', 'AQDwqlwUoZAU45fLF1nlvx860g3f8huXL15If1YTwB3xuEuorfsvUw7FL49RffwhneecAGPoKq4C7fg5yUADa3Ggz6VFiYQ8Nrh5ccp1nmLXyP21ehqpluSB2I340qPe7zg'),
(5, 'admin', 'admin@gmail.com', '123', 'BQBSXk1kvI8xTVErKiz7xBT_EHh7GZ5X71vcUsmY2OD-b8XB1Bj56aV2pLCuYutqX0y72ZcZjoScor8xXLpmtXBejbezjT5GPilSJRZFcJY2sTVYxXFOfB9nhz5h0ndbs6I8OtJoOvqoTjTJvGPG8mZytP9V2CsLzuvn2LrEnOzloAj6xJ6l2v4lGoimEx-nOkDY3ZZKJajHJrsc', 'AQClgB-2boCcWSKsa2acZAIugGwsq-I6hW2MI5EvHB38wiDwf96wSZwi4xGzfkTDSgcQbSatQC904F2WyxrfD8dDWdUlMQXZNgsld619bXOki4DvFSyVPSuTMY-UXIbbGE0'),
(6, 'Harsh', 'Harsh@gmail.com', '123', NULL, NULL),
(7, 'vikas', 'hello@gmail.com', '123', NULL, NULL),
(8, 'vikas', 'admn@gmail.com', '123', 'BQCflTdfaNo0ki1xjvIMlBcuMOnynmDfi5vkWbcIUHj_wKKHM-U1Tx0mGCoUyhW-r96rQThdRstsZH58KwRI90OS4n9RT17wY_Ct05YO6VoqFarwFudYpt_CzurY8-N2gTrCgIQof8btVDQ-EIqpOQZ4aN6PC_egDsLS8at2OuuPHoXaHDWODOhYvhrf0vH4qIV1D41Xcyu3MGax', 'AQC5Czhtk9Zaqha4pCXON7sZS4g1mOPlNqQGSRjCKV6RPB1Y80y4qJXyGUm9Kjs0n4US9QXP-Ur-HAoED72pwiZyG5MHYT2-t16EQLDVsl45ay-1-lR3DDzYezC6sjR7r14'),
(9, 'vikas', 'vikash@cedcoss.com', '123', 'BQDQrKIsWwQSu02WlZEHXWGe3XKGZCGovZrZ2Yyn76wdxPOgSy64WlHlSXUkWmqNgQgvJWsquRWy3zGjYpYHj6QCRRCW-g8ifIm1xosr4eHCE7Kv8NZradaMJzaasj2WW8ckZLv_GaDdNoRs6J695NxfRH1duk_Zcudmk7nSgRLu6npHNg_1xLCfTwnUjEu6qjvt4Pj-PqKcPhmP', 'AQBuhiYqvT3aPxa34M8QJ4VAH-2reFt6g_l1gG63Z1SXZhAI7-vu1GQm2ssKv_bvcPdt20giK3Mo3V6Crtuzp2D9OWthheNb29qHT0BMHEuD1bprhwzHUD54wmIcFN7FgHo');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
