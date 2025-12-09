-- GreenLife - Smart Environmental Impact Dashboard
--
-- Database: `greenlife_db`
--
-- This script does the following:
-- 1. Creates a new database `greenlife_db` if it doesn't exist.
-- 2. Switches to the new database.
-- 3. Creates the `footprints` table for storing carbon footprint calculations.
-- 4. Inserts some sample data into the `footprints` table for testing and demonstration.

--
-- Database Creation
--
CREATE DATABASE IF NOT EXISTS `greenlife_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `greenlife_db`;

-- --------------------------------------------------------

--
-- Table structure for table `footprints`
--
-- This table stores the results of each carbon footprint calculation.
-- - id: A unique identifier for each record.
-- - car_km: The daily car kilometers input by the user.
-- - elect_hours: The daily electricity usage hours input by the user.
-- - footprint: The calculated carbon footprint in kg CO2e.
-- - suggestion: An AI-generated suggestion for reducing the footprint.
-- - created_at: A timestamp for when the record was created.
--
CREATE TABLE `footprints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `car_km` float NOT NULL,
  `elect_hours` float NOT NULL,
  `footprint` float NOT NULL,
  `suggestion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footprints`
--
-- Here we add a few sample records to the `footprints` table.
-- This helps in testing the data retrieval and visualization features
-- without needing to manually input data first.
--
INSERT INTO `footprints` (`car_km`, `elect_hours`, `footprint`, `suggestion`, `created_at`) VALUES
(20, 8, 8.2, 'Your carbon footprint is moderate. Consider carpooling or using public transport once a week to see a significant reduction.', '2025-11-15 10:00:00'),
(5, 4, 3.05, 'Excellent! Your carbon footprint is quite low. Keep up the great work! To improve further, try replacing old incandescent bulbs with LEDs.', '2025-11-18 11:30:00'),
(50, 12, 16.5, 'Your carbon footprint is high. A major contributor is your daily commute. Exploring electric vehicle options or reducing travel days could cut your emissions drastically.', '2025-11-21 14:00:00'),
(0, 6, 3, 'Excellent! Your carbon footprint is quite low. Keep up the great work! To improve further, try replacing old incandescent bulbs with LEDs.', '2025-11-25 09:15:00');
