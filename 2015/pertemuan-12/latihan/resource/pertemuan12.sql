-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 13, 2013 at 10:53 PM
-- Server version: 5.5.25
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pertemuan12`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`pw2unpas_pert12`@`localhost` PROCEDURE `film_in_stock`(IN p_film_id INT, IN p_store_id INT, OUT p_film_count INT)
    READS SQL DATA
BEGIN
     SELECT inventory_id
     FROM inventory
     WHERE film_id = p_film_id
     AND store_id = p_store_id
     AND inventory_in_stock(inventory_id);

     SELECT FOUND_ROWS() INTO p_film_count;
END$$

CREATE DEFINER=`pw2unpas_pert12`@`localhost` PROCEDURE `film_not_in_stock`(IN p_film_id INT, IN p_store_id INT, OUT p_film_count INT)
    READS SQL DATA
BEGIN
     SELECT inventory_id
     FROM inventory
     WHERE film_id = p_film_id
     AND store_id = p_store_id
     AND NOT inventory_in_stock(inventory_id);

     SELECT FOUND_ROWS() INTO p_film_count;
END$$

CREATE DEFINER=`pw2unpas_pert12`@`localhost` PROCEDURE `rewards_report`(
    IN min_monthly_purchases TINYINT UNSIGNED
    , IN min_dollar_amount_purchased DECIMAL(10,2) UNSIGNED
    , OUT count_rewardees INT
)
    READS SQL DATA
    COMMENT 'Provides a customizable report on best customers'
proc: BEGIN

    DECLARE last_month_start DATE;
    DECLARE last_month_end DATE;

    /* Some sanity checks... */
    IF min_monthly_purchases = 0 THEN
        SELECT 'Minimum monthly purchases parameter must be > 0';
        LEAVE proc;
    END IF;
    IF min_dollar_amount_purchased = 0.00 THEN
        SELECT 'Minimum monthly dollar amount purchased parameter must be > $0.00';
        LEAVE proc;
    END IF;

    /* Determine start and end time periods */
    SET last_month_start = DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH);
    SET last_month_start = STR_TO_DATE(CONCAT(YEAR(last_month_start),'-',MONTH(last_month_start),'-01'),'%Y-%m-%d');
    SET last_month_end = LAST_DAY(last_month_start);

    /*
        Create a temporary storage area for
        Customer IDs.
    */
    CREATE TEMPORARY TABLE tmpCustomer (customer_id SMALLINT UNSIGNED NOT NULL PRIMARY KEY);

    /*
        Find all customers meeting the
        monthly purchase requirements
    */
    INSERT INTO tmpCustomer (customer_id)
    SELECT p.customer_id
    FROM payment AS p
    WHERE DATE(p.payment_date) BETWEEN last_month_start AND last_month_end
    GROUP BY customer_id
    HAVING SUM(p.amount) > min_dollar_amount_purchased
    AND COUNT(customer_id) > min_monthly_purchases;

    /* Populate OUT parameter with count of found customers */
    SELECT COUNT(*) FROM tmpCustomer INTO count_rewardees;

    /*
        Output ALL customer information of matching rewardees.
        Customize output as needed.
    */
    SELECT c.*
    FROM tmpCustomer AS t
    INNER JOIN customer AS c ON t.customer_id = c.customer_id;

    /* Clean up */
    DROP TABLE tmpCustomer;
END$$

--
-- Functions
--
CREATE DEFINER=`pw2unpas_pert12`@`localhost` FUNCTION `get_customer_balance`(p_customer_id INT, p_effective_date DATETIME) RETURNS decimal(5,2)
    READS SQL DATA
    DETERMINISTIC
BEGIN

       #OK, WE NEED TO CALCULATE THE CURRENT BALANCE GIVEN A CUSTOMER_ID AND A DATE
       #THAT WE WANT THE BALANCE TO BE EFFECTIVE FOR. THE BALANCE IS:
       #   1) RENTAL FEES FOR ALL PREVIOUS RENTALS
       #   2) ONE DOLLAR FOR EVERY DAY THE PREVIOUS RENTALS ARE OVERDUE
       #   3) IF A FILM IS MORE THAN RENTAL_DURATION * 2 OVERDUE, CHARGE THE REPLACEMENT_COST
       #   4) SUBTRACT ALL PAYMENTS MADE BEFORE THE DATE SPECIFIED

  DECLARE v_rentfees DECIMAL(5,2); #FEES PAID TO RENT THE VIDEOS INITIALLY
  DECLARE v_overfees INTEGER;      #LATE FEES FOR PRIOR RENTALS
  DECLARE v_payments DECIMAL(5,2); #SUM OF PAYMENTS MADE PREVIOUSLY

  SELECT IFNULL(SUM(film.rental_rate),0) INTO v_rentfees
    FROM film, inventory, rental
    WHERE film.film_id = inventory.film_id
      AND inventory.inventory_id = rental.inventory_id
      AND rental.rental_date <= p_effective_date
      AND rental.customer_id = p_customer_id;

  SELECT IFNULL(SUM(IF((TO_DAYS(rental.return_date) - TO_DAYS(rental.rental_date)) > film.rental_duration,
        ((TO_DAYS(rental.return_date) - TO_DAYS(rental.rental_date)) - film.rental_duration),0)),0) INTO v_overfees
    FROM rental, inventory, film
    WHERE film.film_id = inventory.film_id
      AND inventory.inventory_id = rental.inventory_id
      AND rental.rental_date <= p_effective_date
      AND rental.customer_id = p_customer_id;


  SELECT IFNULL(SUM(payment.amount),0) INTO v_payments
    FROM payment

    WHERE payment.payment_date <= p_effective_date
    AND payment.customer_id = p_customer_id;

  RETURN v_rentfees + v_overfees - v_payments;
END$$

CREATE DEFINER=`pw2unpas_pert12`@`localhost` FUNCTION `inventory_held_by_customer`(p_inventory_id INT) RETURNS int(11)
    READS SQL DATA
BEGIN
  DECLARE v_customer_id INT;
  DECLARE EXIT HANDLER FOR NOT FOUND RETURN NULL;

  SELECT customer_id INTO v_customer_id
  FROM rental
  WHERE return_date IS NULL
  AND inventory_id = p_inventory_id;

  RETURN v_customer_id;
END$$

CREATE DEFINER=`pw2unpas_pert12`@`localhost` FUNCTION `inventory_in_stock`(p_inventory_id INT) RETURNS tinyint(1)
    READS SQL DATA
BEGIN
    DECLARE v_rentals INT;
    DECLARE v_out     INT;

    #AN ITEM IS IN-STOCK IF THERE ARE EITHER NO ROWS IN THE rental TABLE
    #FOR THE ITEM OR ALL ROWS HAVE return_date POPULATED

    SELECT COUNT(*) INTO v_rentals
    FROM rental
    WHERE inventory_id = p_inventory_id;

    IF v_rentals = 0 THEN
      RETURN TRUE;
    END IF;

    SELECT COUNT(rental_id) INTO v_out
    FROM inventory LEFT JOIN rental USING(inventory_id)
    WHERE inventory.inventory_id = p_inventory_id
    AND rental.return_date IS NULL;

    IF v_out > 0 THEN
      RETURN FALSE;
    ELSE
      RETURN TRUE;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `film_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `release_year` year(4) DEFAULT NULL,
  PRIMARY KEY (`film_id`),
  KEY `idx_title` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1001 ;

--
-- Dumping data for table `film`
--

INSERT INTO `film` (`film_id`, `title`, `description`, `release_year`) VALUES
(1, 'ACADEMY DINOSAUR', 'A Epic Drama of a Feminist And a Mad Scientist who must Battle a Teacher in The Canadian Rockies', 2006),
(2, 'ACE GOLDFINGER', 'A Astounding Epistle of a Database Administrator And a Explorer who must Find a Car in Ancient China', 2006),
(3, 'ADAPTATION HOLES', 'A Astounding Reflection of a Lumberjack And a Car who must Sink a Lumberjack in A Baloon Factory', 2006),
(4, 'AFFAIR PREJUDICE', 'A Fanciful Documentary of a Frisbee And a Lumberjack who must Chase a Monkey in A Shark Tank', 2006),
(5, 'AFRICAN EGG', 'A Fast-Paced Documentary of a Pastry Chef And a Dentist who must Pursue a Forensic Psychologist in The Gulf of Mexico', 2006),
(6, 'AGENT TRUMAN', 'A Intrepid Panorama of a Robot And a Boy who must Escape a Sumo Wrestler in Ancient China', 2006),
(7, 'AIRPLANE SIERRA', 'A Touching Saga of a Hunter And a Butler who must Discover a Butler in A Jet Boat', 2006),
(8, 'AIRPORT POLLOCK', 'A Epic Tale of a Moose And a Girl who must Confront a Monkey in Ancient India', 2006),
(9, 'ALABAMA DEVIL', 'A Thoughtful Panorama of a Database Administrator And a Mad Scientist who must Outgun a Mad Scientist in A Jet Boat', 2006),
(10, 'ALADDIN CALENDAR', 'A Action-Packed Tale of a Man And a Lumberjack who must Reach a Feminist in Ancient China', 2006),
(11, 'ALAMO VIDEOTAPE', 'A Boring Epistle of a Butler And a Cat who must Fight a Pastry Chef in A MySQL Convention', 2006),
(12, 'ALASKA PHANTOM', 'A Fanciful Saga of a Hunter And a Pastry Chef who must Vanquish a Boy in Australia', 2006),
(13, 'ALI FOREVER', 'A Action-Packed Drama of a Dentist And a Crocodile who must Battle a Feminist in The Canadian Rockies', 2006),
(14, 'ALICE FANTASIA', 'A Emotional Drama of a A Shark And a Database Administrator who must Vanquish a Pioneer in Soviet Georgia', 2006),
(15, 'ALIEN CENTER', 'A Brilliant Drama of a Cat And a Mad Scientist who must Battle a Feminist in A MySQL Convention', 2006),
(16, 'ALLEY EVOLUTION', 'A Fast-Paced Drama of a Robot And a Composer who must Battle a Astronaut in New Orleans', 2006),
(17, 'ALONE TRIP', 'A Fast-Paced Character Study of a Composer And a Dog who must Outgun a Boat in An Abandoned Fun House', 2006),
(18, 'ALTER VICTORY', 'A Thoughtful Drama of a Composer And a Feminist who must Meet a Secret Agent in The Canadian Rockies', 2006),
(19, 'AMADEUS HOLY', 'A Emotional Display of a Pioneer And a Technical Writer who must Battle a Man in A Baloon', 2006),
(20, 'AMELIE HELLFIGHTERS', 'A Boring Drama of a Woman And a Squirrel who must Conquer a Student in A Baloon', 2006),
(21, 'AMERICAN CIRCUS', 'A Insightful Drama of a Girl And a Astronaut who must Face a Database Administrator in A Shark Tank', 2006),
(22, 'AMISTAD MIDSUMMER', 'A Emotional Character Study of a Dentist And a Crocodile who must Meet a Sumo Wrestler in California', 2006),
(23, 'ANACONDA CONFESSIONS', 'A Lacklusture Display of a Dentist And a Dentist who must Fight a Girl in Australia', 2006),
(24, 'ANALYZE HOOSIERS', 'A Thoughtful Display of a Explorer And a Pastry Chef who must Overcome a Feminist in The Sahara Desert', 2006),
(25, 'ANGELS LIFE', 'A Thoughtful Display of a Woman And a Astronaut who must Battle a Robot in Berlin', 2006),
(26, 'ANNIE IDENTITY', 'A Amazing Panorama of a Pastry Chef And a Boat who must Escape a Woman in An Abandoned Amusement Park', 2006),
(27, 'ANONYMOUS HUMAN', 'A Amazing Reflection of a Database Administrator And a Astronaut who must Outrace a Database Administrator in A Shark Tank', 2006),
(28, 'ANTHEM LUKE', 'A Touching Panorama of a Waitress And a Woman who must Outrace a Dog in An Abandoned Amusement Park', 2006),
(29, 'ANTITRUST TOMATOES', 'A Fateful Yarn of a Womanizer And a Feminist who must Succumb a Database Administrator in Ancient India', 2006),
(30, 'ANYTHING SAVANNAH', 'A Epic Story of a Pastry Chef And a Woman who must Chase a Feminist in An Abandoned Fun House', 2006),
(31, 'APACHE DIVINE', 'A Awe-Inspiring Reflection of a Pastry Chef And a Teacher who must Overcome a Sumo Wrestler in A U-Boat', 2006),
(32, 'APOCALYPSE FLAMINGOS', 'A Astounding Story of a Dog And a Squirrel who must Defeat a Woman in An Abandoned Amusement Park', 2006),
(33, 'APOLLO TEEN', 'A Action-Packed Reflection of a Crocodile And a Explorer who must Find a Sumo Wrestler in An Abandoned Mine Shaft', 2006),
(34, 'ARABIA DOGMA', 'A Touching Epistle of a Madman And a Mad Cow who must Defeat a Student in Nigeria', 2006),
(35, 'ARACHNOPHOBIA ROLLERCOASTER', 'A Action-Packed Reflection of a Pastry Chef And a Composer who must Discover a Mad Scientist in The First Manned Space Station', 2006),
(36, 'ARGONAUTS TOWN', 'A Emotional Epistle of a Forensic Psychologist And a Butler who must Challenge a Waitress in An Abandoned Mine Shaft', 2006),
(37, 'ARIZONA BANG', 'A Brilliant Panorama of a Mad Scientist And a Mad Cow who must Meet a Pioneer in A Monastery', 2006),
(38, 'ARK RIDGEMONT', 'A Beautiful Yarn of a Pioneer And a Monkey who must Pursue a Explorer in The Sahara Desert', 2006),
(39, 'ARMAGEDDON LOST', 'A Fast-Paced Tale of a Boat And a Teacher who must Succumb a Composer in An Abandoned Mine Shaft', 2006),
(40, 'ARMY FLINTSTONES', 'A Boring Saga of a Database Administrator And a Womanizer who must Battle a Waitress in Nigeria', 2006),
(41, 'ARSENIC INDEPENDENCE', 'A Fanciful Documentary of a Mad Cow And a Womanizer who must Find a Dentist in Berlin', 2006),
(42, 'ARTIST COLDBLOODED', 'A Stunning Reflection of a Robot And a Moose who must Challenge a Woman in California', 2006),
(43, 'ATLANTIS CAUSE', 'A Thrilling Yarn of a Feminist And a Hunter who must Fight a Technical Writer in A Shark Tank', 2006),
(44, 'ATTACKS HATE', 'A Fast-Paced Panorama of a Technical Writer And a Mad Scientist who must Find a Feminist in An Abandoned Mine Shaft', 2006),
(45, 'ATTRACTION NEWTON', 'A Astounding Panorama of a Composer And a Frisbee who must Reach a Husband in Ancient Japan', 2006),
(46, 'AUTUMN CROW', 'A Beautiful Tale of a Dentist And a Mad Cow who must Battle a Moose in The Sahara Desert', 2006),
(47, 'BABY HALL', 'A Boring Character Study of a A Shark And a Girl who must Outrace a Feminist in An Abandoned Mine Shaft', 2006),
(48, 'BACKLASH UNDEFEATED', 'A Stunning Character Study of a Mad Scientist And a Mad Cow who must Kill a Car in A Monastery', 2006),
(49, 'BADMAN DAWN', 'A Emotional Panorama of a Pioneer And a Composer who must Escape a Mad Scientist in A Jet Boat', 2006),
(50, 'BAKED CLEOPATRA', 'A Stunning Drama of a Forensic Psychologist And a Husband who must Overcome a Waitress in A Monastery', 2006),
(51, 'BALLOON HOMEWARD', 'A Insightful Panorama of a Forensic Psychologist And a Mad Cow who must Build a Mad Scientist in The First Manned Space Station', 2006),
(52, 'BALLROOM MOCKINGBIRD', 'A Thrilling Documentary of a Composer And a Monkey who must Find a Feminist in California', 2006),
(53, 'BANG KWAI', 'A Epic Drama of a Madman And a Cat who must Face a A Shark in An Abandoned Amusement Park', 2006),
(54, 'BANGER PINOCCHIO', 'A Awe-Inspiring Drama of a Car And a Pastry Chef who must Chase a Crocodile in The First Manned Space Station', 2006),
(55, 'BARBARELLA STREETCAR', 'A Awe-Inspiring Story of a Feminist And a Cat who must Conquer a Dog in A Monastery', 2006),
(56, 'BAREFOOT MANCHURIAN', 'A Intrepid Story of a Cat And a Student who must Vanquish a Girl in An Abandoned Amusement Park', 2006),
(57, 'BASIC EASY', 'A Stunning Epistle of a Man And a Husband who must Reach a Mad Scientist in A Jet Boat', 2006),
(58, 'BEACH HEARTBREAKERS', 'A Fateful Display of a Womanizer And a Mad Scientist who must Outgun a A Shark in Soviet Georgia', 2006),
(59, 'BEAR GRACELAND', 'A Astounding Saga of a Dog And a Boy who must Kill a Teacher in The First Manned Space Station', 2006),
(60, 'BEAST HUNCHBACK', 'A Awe-Inspiring Epistle of a Student And a Squirrel who must Defeat a Boy in Ancient China', 2006),
(61, 'BEAUTY GREASE', 'A Fast-Paced Display of a Composer And a Moose who must Sink a Robot in An Abandoned Mine Shaft', 2006),
(62, 'BED HIGHBALL', 'A Astounding Panorama of a Lumberjack And a Dog who must Redeem a Woman in An Abandoned Fun House', 2006),
(63, 'BEDAZZLED MARRIED', 'A Astounding Character Study of a Madman And a Robot who must Meet a Mad Scientist in An Abandoned Fun House', 2006),
(64, 'BEETHOVEN EXORCIST', 'A Epic Display of a Pioneer And a Student who must Challenge a Butler in The Gulf of Mexico', 2006),
(65, 'BEHAVIOR RUNAWAY', 'A Unbelieveable Drama of a Student And a Husband who must Outrace a Sumo Wrestler in Berlin', 2006),
(66, 'BENEATH RUSH', 'A Astounding Panorama of a Man And a Monkey who must Discover a Man in The First Manned Space Station', 2006),
(67, 'BERETS AGENT', 'A Taut Saga of a Crocodile And a Boy who must Overcome a Technical Writer in Ancient China', 2006),
(68, 'BETRAYED REAR', 'A Emotional Character Study of a Boat And a Pioneer who must Find a Explorer in A Shark Tank', 2006),
(69, 'BEVERLY OUTLAW', 'A Fanciful Documentary of a Womanizer And a Boat who must Defeat a Madman in The First Manned Space Station', 2006),
(70, 'BIKINI BORROWERS', 'A Astounding Drama of a Astronaut And a Cat who must Discover a Woman in The First Manned Space Station', 2006),
(71, 'BILKO ANONYMOUS', 'A Emotional Reflection of a Teacher And a Man who must Meet a Cat in The First Manned Space Station', 2006),
(72, 'BILL OTHERS', 'A Stunning Saga of a Mad Scientist And a Forensic Psychologist who must Challenge a Squirrel in A MySQL Convention', 2006),
(73, 'BINGO TALENTED', 'A Touching Tale of a Girl And a Crocodile who must Discover a Waitress in Nigeria', 2006),
(74, 'BIRCH ANTITRUST', 'A Fanciful Panorama of a Husband And a Pioneer who must Outgun a Dog in A Baloon', 2006),
(75, 'BIRD INDEPENDENCE', 'A Thrilling Documentary of a Car And a Student who must Sink a Hunter in The Canadian Rockies', 2006),
(76, 'BIRDCAGE CASPER', 'A Fast-Paced Saga of a Frisbee And a Astronaut who must Overcome a Feminist in Ancient India', 2006),
(77, 'BIRDS PERDITION', 'A Boring Story of a Womanizer And a Pioneer who must Face a Dog in California', 2006),
(78, 'BLACKOUT PRIVATE', 'A Intrepid Yarn of a Pastry Chef And a Mad Scientist who must Challenge a Secret Agent in Ancient Japan', 2006),
(79, 'BLADE POLISH', 'A Thoughtful Character Study of a Frisbee And a Pastry Chef who must Fight a Dentist in The First Manned Space Station', 2006),
(80, 'BLANKET BEVERLY', 'A Emotional Documentary of a Student And a Girl who must Build a Boat in Nigeria', 2006),
(81, 'BLINDNESS GUN', 'A Touching Drama of a Robot And a Dentist who must Meet a Hunter in A Jet Boat', 2006),
(82, 'BLOOD ARGONAUTS', 'A Boring Drama of a Explorer And a Man who must Kill a Lumberjack in A Manhattan Penthouse', 2006),
(83, 'BLUES INSTINCT', 'A Insightful Documentary of a Boat And a Composer who must Meet a Forensic Psychologist in An Abandoned Fun House', 2006),
(84, 'BOILED DARES', 'A Awe-Inspiring Story of a Waitress And a Dog who must Discover a Dentist in Ancient Japan', 2006),
(85, 'BONNIE HOLOCAUST', 'A Fast-Paced Story of a Crocodile And a Robot who must Find a Moose in Ancient Japan', 2006),
(86, 'BOOGIE AMELIE', 'A Lacklusture Character Study of a Husband And a Sumo Wrestler who must Succumb a Technical Writer in The Gulf of Mexico', 2006),
(87, 'BOONDOCK BALLROOM', 'A Fateful Panorama of a Crocodile And a Boy who must Defeat a Monkey in The Gulf of Mexico', 2006),
(88, 'BORN SPINAL', 'A Touching Epistle of a Frisbee And a Husband who must Pursue a Student in Nigeria', 2006),
(89, 'BORROWERS BEDAZZLED', 'A Brilliant Epistle of a Teacher And a Sumo Wrestler who must Defeat a Man in An Abandoned Fun House', 2006),
(90, 'BOULEVARD MOB', 'A Fateful Epistle of a Moose And a Monkey who must Confront a Lumberjack in Ancient China', 2006),
(91, 'BOUND CHEAPER', 'A Thrilling Panorama of a Database Administrator And a Astronaut who must Challenge a Lumberjack in A Baloon', 2006),
(92, 'BOWFINGER GABLES', 'A Fast-Paced Yarn of a Waitress And a Composer who must Outgun a Dentist in California', 2006),
(93, 'BRANNIGAN SUNRISE', 'A Amazing Epistle of a Moose And a Crocodile who must Outrace a Dog in Berlin', 2006),
(94, 'BRAVEHEART HUMAN', 'A Insightful Story of a Dog And a Pastry Chef who must Battle a Girl in Berlin', 2006),
(95, 'BREAKFAST GOLDFINGER', 'A Beautiful Reflection of a Student And a Student who must Fight a Moose in Berlin', 2006),
(96, 'BREAKING HOME', 'A Beautiful Display of a Secret Agent And a Monkey who must Battle a Sumo Wrestler in An Abandoned Mine Shaft', 2006),
(97, 'BRIDE INTRIGUE', 'A Epic Tale of a Robot And a Monkey who must Vanquish a Man in New Orleans', 2006),
(98, 'BRIGHT ENCOUNTERS', 'A Fateful Yarn of a Lumberjack And a Feminist who must Conquer a Student in A Jet Boat', 2006),
(99, 'BRINGING HYSTERICAL', 'A Fateful Saga of a A Shark And a Technical Writer who must Find a Woman in A Jet Boat', 2006),
(100, 'BROOKLYN DESERT', 'A Beautiful Drama of a Dentist And a Composer who must Battle a Sumo Wrestler in The First Manned Space Station', 2006),
(101, 'BROTHERHOOD BLANKET', 'A Fateful Character Study of a Butler And a Technical Writer who must Sink a Astronaut in Ancient Japan', 2006),
(102, 'BUBBLE GROSSE', 'A Awe-Inspiring Panorama of a Crocodile And a Moose who must Confront a Girl in A Baloon', 2006),
(103, 'BUCKET BROTHERHOOD', 'A Amazing Display of a Girl And a Womanizer who must Succumb a Lumberjack in A Baloon Factory', 2006),
(104, 'BUGSY SONG', 'A Awe-Inspiring Character Study of a Secret Agent And a Boat who must Find a Squirrel in The First Manned Space Station', 2006),
(105, 'BULL SHAWSHANK', 'A Fanciful Drama of a Moose And a Squirrel who must Conquer a Pioneer in The Canadian Rockies', 2006),
(106, 'BULWORTH COMMANDMENTS', 'A Amazing Display of a Mad Cow And a Pioneer who must Redeem a Sumo Wrestler in The Outback', 2006),
(107, 'BUNCH MINDS', 'A Emotional Story of a Feminist And a Feminist who must Escape a Pastry Chef in A MySQL Convention', 2006),
(108, 'BUTCH PANTHER', 'A Lacklusture Yarn of a Feminist And a Database Administrator who must Face a Hunter in New Orleans', 2006),
(109, 'BUTTERFLY CHOCOLAT', 'A Fateful Story of a Girl And a Composer who must Conquer a Husband in A Shark Tank', 2006),
(110, 'CABIN FLASH', 'A Stunning Epistle of a Boat And a Man who must Challenge a A Shark in A Baloon Factory', 2006),
(111, 'CADDYSHACK JEDI', 'A Awe-Inspiring Epistle of a Woman And a Madman who must Fight a Robot in Soviet Georgia', 2006),
(112, 'CALENDAR GUNFIGHT', 'A Thrilling Drama of a Frisbee And a Lumberjack who must Sink a Man in Nigeria', 2006),
(113, 'CALIFORNIA BIRDS', 'A Thrilling Yarn of a Database Administrator And a Robot who must Battle a Database Administrator in Ancient India', 2006),
(114, 'CAMELOT VACATION', 'A Touching Character Study of a Woman And a Waitress who must Battle a Pastry Chef in A MySQL Convention', 2006),
(115, 'CAMPUS REMEMBER', 'A Astounding Drama of a Crocodile And a Mad Cow who must Build a Robot in A Jet Boat', 2006),
(116, 'CANDIDATE PERDITION', 'A Brilliant Epistle of a Composer And a Database Administrator who must Vanquish a Mad Scientist in The First Manned Space Station', 2006),
(117, 'CANDLES GRAPES', 'A Fanciful Character Study of a Monkey And a Explorer who must Build a Astronaut in An Abandoned Fun House', 2006),
(118, 'CANYON STOCK', 'A Thoughtful Reflection of a Waitress And a Feminist who must Escape a Squirrel in A Manhattan Penthouse', 2006),
(119, 'CAPER MOTIONS', 'A Fateful Saga of a Moose And a Car who must Pursue a Woman in A MySQL Convention', 2006),
(120, 'CARIBBEAN LIBERTY', 'A Fanciful Tale of a Pioneer And a Technical Writer who must Outgun a Pioneer in A Shark Tank', 2006),
(121, 'CAROL TEXAS', 'A Astounding Character Study of a Composer And a Student who must Overcome a Composer in A Monastery', 2006),
(122, 'CARRIE BUNCH', 'A Amazing Epistle of a Student And a Astronaut who must Discover a Frisbee in The Canadian Rockies', 2006),
(123, 'CASABLANCA SUPER', 'A Amazing Panorama of a Crocodile And a Forensic Psychologist who must Pursue a Secret Agent in The First Manned Space Station', 2006),
(124, 'CASPER DRAGONFLY', 'A Intrepid Documentary of a Boat And a Crocodile who must Chase a Robot in The Sahara Desert', 2006),
(125, 'CASSIDY WYOMING', 'A Intrepid Drama of a Frisbee And a Hunter who must Kill a Secret Agent in New Orleans', 2006),
(126, 'CASUALTIES ENCINO', 'A Insightful Yarn of a A Shark And a Pastry Chef who must Face a Boy in A Monastery', 2006),
(127, 'CAT CONEHEADS', 'A Fast-Paced Panorama of a Girl And a A Shark who must Confront a Boy in Ancient India', 2006),
(128, 'CATCH AMISTAD', 'A Boring Reflection of a Lumberjack And a Feminist who must Discover a Woman in Nigeria', 2006),
(129, 'CAUSE DATE', 'A Taut Tale of a Explorer And a Pastry Chef who must Conquer a Hunter in A MySQL Convention', 2006),
(130, 'CELEBRITY HORN', 'A Amazing Documentary of a Secret Agent And a Astronaut who must Vanquish a Hunter in A Shark Tank', 2006),
(131, 'CENTER DINOSAUR', 'A Beautiful Character Study of a Sumo Wrestler And a Dentist who must Find a Dog in California', 2006),
(132, 'CHAINSAW UPTOWN', 'A Beautiful Documentary of a Boy And a Robot who must Discover a Squirrel in Australia', 2006),
(133, 'CHAMBER ITALIAN', 'A Fateful Reflection of a Moose And a Husband who must Overcome a Monkey in Nigeria', 2006),
(134, 'CHAMPION FLATLINERS', 'A Amazing Story of a Mad Cow And a Dog who must Kill a Husband in A Monastery', 2006),
(135, 'CHANCE RESURRECTION', 'A Astounding Story of a Forensic Psychologist And a Forensic Psychologist who must Overcome a Moose in Ancient China', 2006),
(136, 'CHAPLIN LICENSE', 'A Boring Drama of a Dog And a Forensic Psychologist who must Outrace a Explorer in Ancient India', 2006),
(137, 'CHARADE DUFFEL', 'A Action-Packed Display of a Man And a Waitress who must Build a Dog in A MySQL Convention', 2006),
(138, 'CHARIOTS CONSPIRACY', 'A Unbelieveable Epistle of a Robot And a Husband who must Chase a Robot in The First Manned Space Station', 2006),
(139, 'CHASING FIGHT', 'A Astounding Saga of a Technical Writer And a Butler who must Battle a Butler in A Shark Tank', 2006),
(140, 'CHEAPER CLYDE', 'A Emotional Character Study of a Pioneer And a Girl who must Discover a Dog in Ancient Japan', 2006),
(141, 'CHICAGO NORTH', 'A Fateful Yarn of a Mad Cow And a Waitress who must Battle a Student in California', 2006),
(142, 'CHICKEN HELLFIGHTERS', 'A Emotional Drama of a Dog And a Explorer who must Outrace a Technical Writer in Australia', 2006),
(143, 'CHILL LUCK', 'A Lacklusture Epistle of a Boat And a Technical Writer who must Fight a A Shark in The Canadian Rockies', 2006),
(144, 'CHINATOWN GLADIATOR', 'A Brilliant Panorama of a Technical Writer And a Lumberjack who must Escape a Butler in Ancient India', 2006),
(145, 'CHISUM BEHAVIOR', 'A Epic Documentary of a Sumo Wrestler And a Butler who must Kill a Car in Ancient India', 2006),
(146, 'CHITTY LOCK', 'A Boring Epistle of a Boat And a Database Administrator who must Kill a Sumo Wrestler in The First Manned Space Station', 2006),
(147, 'CHOCOLAT HARRY', 'A Action-Packed Epistle of a Dentist And a Moose who must Meet a Mad Cow in Ancient Japan', 2006),
(148, 'CHOCOLATE DUCK', 'A Unbelieveable Story of a Mad Scientist And a Technical Writer who must Discover a Composer in Ancient China', 2006),
(149, 'CHRISTMAS MOONSHINE', 'A Action-Packed Epistle of a Feminist And a Astronaut who must Conquer a Boat in A Manhattan Penthouse', 2006),
(150, 'CIDER DESIRE', 'A Stunning Character Study of a Composer And a Mad Cow who must Succumb a Cat in Soviet Georgia', 2006),
(151, 'CINCINATTI WHISPERER', 'A Brilliant Saga of a Pastry Chef And a Hunter who must Confront a Butler in Berlin', 2006),
(152, 'CIRCUS YOUTH', 'A Thoughtful Drama of a Pastry Chef And a Dentist who must Pursue a Girl in A Baloon', 2006),
(153, 'CITIZEN SHREK', 'A Fanciful Character Study of a Technical Writer And a Husband who must Redeem a Robot in The Outback', 2006),
(154, 'CLASH FREDDY', 'A Amazing Yarn of a Composer And a Squirrel who must Escape a Astronaut in Australia', 2006),
(155, 'CLEOPATRA DEVIL', 'A Fanciful Documentary of a Crocodile And a Technical Writer who must Fight a A Shark in A Baloon', 2006),
(156, 'CLERKS ANGELS', 'A Thrilling Display of a Sumo Wrestler And a Girl who must Confront a Man in A Baloon', 2006),
(157, 'CLOCKWORK PARADISE', 'A Insightful Documentary of a Technical Writer And a Feminist who must Challenge a Cat in A Baloon', 2006),
(158, 'CLONES PINOCCHIO', 'A Amazing Drama of a Car And a Robot who must Pursue a Dentist in New Orleans', 2006),
(159, 'CLOSER BANG', 'A Unbelieveable Panorama of a Frisbee And a Hunter who must Vanquish a Monkey in Ancient India', 2006),
(160, 'CLUB GRAFFITI', 'A Epic Tale of a Pioneer And a Hunter who must Escape a Girl in A U-Boat', 2006),
(161, 'CLUE GRAIL', 'A Taut Tale of a Butler And a Mad Scientist who must Build a Crocodile in Ancient China', 2006),
(162, 'CLUELESS BUCKET', 'A Taut Tale of a Car And a Pioneer who must Conquer a Sumo Wrestler in An Abandoned Fun House', 2006),
(163, 'CLYDE THEORY', 'A Beautiful Yarn of a Astronaut And a Frisbee who must Overcome a Explorer in A Jet Boat', 2006),
(164, 'COAST RAINBOW', 'A Astounding Documentary of a Mad Cow And a Pioneer who must Challenge a Butler in The Sahara Desert', 2006),
(165, 'COLDBLOODED DARLING', 'A Brilliant Panorama of a Dentist And a Moose who must Find a Student in The Gulf of Mexico', 2006),
(166, 'COLOR PHILADELPHIA', 'A Thoughtful Panorama of a Car And a Crocodile who must Sink a Monkey in The Sahara Desert', 2006),
(167, 'COMA HEAD', 'A Awe-Inspiring Drama of a Boy And a Frisbee who must Escape a Pastry Chef in California', 2006),
(168, 'COMANCHEROS ENEMY', 'A Boring Saga of a Lumberjack And a Monkey who must Find a Monkey in The Gulf of Mexico', 2006),
(169, 'COMFORTS RUSH', 'A Unbelieveable Panorama of a Pioneer And a Husband who must Meet a Mad Cow in An Abandoned Mine Shaft', 2006),
(170, 'COMMAND DARLING', 'A Awe-Inspiring Tale of a Forensic Psychologist And a Woman who must Challenge a Database Administrator in Ancient Japan', 2006),
(171, 'COMMANDMENTS EXPRESS', 'A Fanciful Saga of a Student And a Mad Scientist who must Battle a Hunter in An Abandoned Mine Shaft', 2006),
(172, 'CONEHEADS SMOOCHY', 'A Touching Story of a Womanizer And a Composer who must Pursue a Husband in Nigeria', 2006),
(173, 'CONFESSIONS MAGUIRE', 'A Insightful Story of a Car And a Boy who must Battle a Technical Writer in A Baloon', 2006),
(174, 'CONFIDENTIAL INTERVIEW', 'A Stunning Reflection of a Cat And a Woman who must Find a Astronaut in Ancient Japan', 2006),
(175, 'CONFUSED CANDLES', 'A Stunning Epistle of a Cat And a Forensic Psychologist who must Confront a Pioneer in A Baloon', 2006),
(176, 'CONGENIALITY QUEST', 'A Touching Documentary of a Cat And a Pastry Chef who must Find a Lumberjack in A Baloon', 2006),
(177, 'CONNECTICUT TRAMP', 'A Unbelieveable Drama of a Crocodile And a Mad Cow who must Reach a Dentist in A Shark Tank', 2006),
(178, 'CONNECTION MICROCOSMOS', 'A Fateful Documentary of a Crocodile And a Husband who must Face a Husband in The First Manned Space Station', 2006),
(179, 'CONQUERER NUTS', 'A Taut Drama of a Mad Scientist And a Man who must Escape a Pioneer in An Abandoned Mine Shaft', 2006),
(180, 'CONSPIRACY SPIRIT', 'A Awe-Inspiring Story of a Student And a Frisbee who must Conquer a Crocodile in An Abandoned Mine Shaft', 2006),
(181, 'CONTACT ANONYMOUS', 'A Insightful Display of a A Shark And a Monkey who must Face a Database Administrator in Ancient India', 2006),
(182, 'CONTROL ANTHEM', 'A Fateful Documentary of a Robot And a Student who must Battle a Cat in A Monastery', 2006),
(183, 'CONVERSATION DOWNHILL', 'A Taut Character Study of a Husband And a Waitress who must Sink a Squirrel in A MySQL Convention', 2006),
(184, 'CORE SUIT', 'A Unbelieveable Tale of a Car And a Explorer who must Confront a Boat in A Manhattan Penthouse', 2006),
(185, 'COWBOY DOOM', 'A Astounding Drama of a Boy And a Lumberjack who must Fight a Butler in A Baloon', 2006),
(186, 'CRAFT OUTFIELD', 'A Lacklusture Display of a Explorer And a Hunter who must Succumb a Database Administrator in A Baloon Factory', 2006),
(187, 'CRANES RESERVOIR', 'A Fanciful Documentary of a Teacher And a Dog who must Outgun a Forensic Psychologist in A Baloon Factory', 2006),
(188, 'CRAZY HOME', 'A Fanciful Panorama of a Boy And a Woman who must Vanquish a Database Administrator in The Outback', 2006),
(189, 'CREATURES SHAKESPEARE', 'A Emotional Drama of a Womanizer And a Squirrel who must Vanquish a Crocodile in Ancient India', 2006),
(190, 'CREEPERS KANE', 'A Awe-Inspiring Reflection of a Squirrel And a Boat who must Outrace a Car in A Jet Boat', 2006),
(191, 'CROOKED FROGMEN', 'A Unbelieveable Drama of a Hunter And a Database Administrator who must Battle a Crocodile in An Abandoned Amusement Park', 2006),
(192, 'CROSSING DIVORCE', 'A Beautiful Documentary of a Dog And a Robot who must Redeem a Womanizer in Berlin', 2006),
(193, 'CROSSROADS CASUALTIES', 'A Intrepid Documentary of a Sumo Wrestler And a Astronaut who must Battle a Composer in The Outback', 2006),
(194, 'CROW GREASE', 'A Awe-Inspiring Documentary of a Woman And a Husband who must Sink a Database Administrator in The First Manned Space Station', 2006),
(195, 'CROWDS TELEMARK', 'A Intrepid Documentary of a Astronaut And a Forensic Psychologist who must Find a Frisbee in An Abandoned Fun House', 2006),
(196, 'CRUELTY UNFORGIVEN', 'A Brilliant Tale of a Car And a Moose who must Battle a Dentist in Nigeria', 2006),
(197, 'CRUSADE HONEY', 'A Fast-Paced Reflection of a Explorer And a Butler who must Battle a Madman in An Abandoned Amusement Park', 2006),
(198, 'CRYSTAL BREAKING', 'A Fast-Paced Character Study of a Feminist And a Explorer who must Face a Pastry Chef in Ancient Japan', 2006),
(199, 'CUPBOARD SINNERS', 'A Emotional Reflection of a Frisbee And a Boat who must Reach a Pastry Chef in An Abandoned Amusement Park', 2006),
(200, 'CURTAIN VIDEOTAPE', 'A Boring Reflection of a Dentist And a Mad Cow who must Chase a Secret Agent in A Shark Tank', 2006),
(201, 'CYCLONE FAMILY', 'A Lacklusture Drama of a Student And a Monkey who must Sink a Womanizer in A MySQL Convention', 2006),
(202, 'DADDY PITTSBURGH', 'A Epic Story of a A Shark And a Student who must Confront a Explorer in The Gulf of Mexico', 2006),
(203, 'DAISY MENAGERIE', 'A Fast-Paced Saga of a Pastry Chef And a Monkey who must Sink a Composer in Ancient India', 2006),
(204, 'DALMATIONS SWEDEN', 'A Emotional Epistle of a Moose And a Hunter who must Overcome a Robot in A Manhattan Penthouse', 2006),
(205, 'DANCES NONE', 'A Insightful Reflection of a A Shark And a Dog who must Kill a Butler in An Abandoned Amusement Park', 2006),
(206, 'DANCING FEVER', 'A Stunning Story of a Explorer And a Forensic Psychologist who must Face a Crocodile in A Shark Tank', 2006),
(207, 'DANGEROUS UPTOWN', 'A Unbelieveable Story of a Mad Scientist And a Woman who must Overcome a Dog in California', 2006),
(208, 'DARES PLUTO', 'A Fateful Story of a Robot And a Dentist who must Defeat a Astronaut in New Orleans', 2006),
(209, 'DARKNESS WAR', 'A Touching Documentary of a Husband And a Hunter who must Escape a Boy in The Sahara Desert', 2006),
(210, 'DARKO DORADO', 'A Stunning Reflection of a Frisbee And a Husband who must Redeem a Dog in New Orleans', 2006),
(211, 'DARLING BREAKING', 'A Brilliant Documentary of a Astronaut And a Squirrel who must Succumb a Student in The Gulf of Mexico', 2006),
(212, 'DARN FORRESTER', 'A Fateful Story of a A Shark And a Explorer who must Succumb a Technical Writer in A Jet Boat', 2006),
(213, 'DATE SPEED', 'A Touching Saga of a Composer And a Moose who must Discover a Dentist in A MySQL Convention', 2006),
(214, 'DAUGHTER MADIGAN', 'A Beautiful Tale of a Hunter And a Mad Scientist who must Confront a Squirrel in The First Manned Space Station', 2006),
(215, 'DAWN POND', 'A Thoughtful Documentary of a Dentist And a Forensic Psychologist who must Defeat a Waitress in Berlin', 2006),
(216, 'DAY UNFAITHFUL', 'A Stunning Documentary of a Composer And a Mad Scientist who must Find a Technical Writer in A U-Boat', 2006),
(217, 'DAZED PUNK', 'A Action-Packed Story of a Pioneer And a Technical Writer who must Discover a Forensic Psychologist in An Abandoned Amusement Park', 2006),
(218, 'DECEIVER BETRAYED', 'A Taut Story of a Moose And a Squirrel who must Build a Husband in Ancient India', 2006),
(219, 'DEEP CRUSADE', 'A Amazing Tale of a Crocodile And a Squirrel who must Discover a Composer in Australia', 2006),
(220, 'DEER VIRGINIAN', 'A Thoughtful Story of a Mad Cow And a Womanizer who must Overcome a Mad Scientist in Soviet Georgia', 2006),
(221, 'DELIVERANCE MULHOLLAND', 'A Astounding Saga of a Monkey And a Moose who must Conquer a Butler in A Shark Tank', 2006),
(222, 'DESERT POSEIDON', 'A Brilliant Documentary of a Butler And a Frisbee who must Build a Astronaut in New Orleans', 2006),
(223, 'DESIRE ALIEN', 'A Fast-Paced Tale of a Dog And a Forensic Psychologist who must Meet a Astronaut in The First Manned Space Station', 2006),
(224, 'DESPERATE TRAINSPOTTING', 'A Epic Yarn of a Forensic Psychologist And a Teacher who must Face a Lumberjack in California', 2006),
(225, 'DESTINATION JERK', 'A Beautiful Yarn of a Teacher And a Cat who must Build a Car in A U-Boat', 2006),
(226, 'DESTINY SATURDAY', 'A Touching Drama of a Crocodile And a Crocodile who must Conquer a Explorer in Soviet Georgia', 2006),
(227, 'DETAILS PACKER', 'A Epic Saga of a Waitress And a Composer who must Face a Boat in A U-Boat', 2006),
(228, 'DETECTIVE VISION', 'A Fanciful Documentary of a Pioneer And a Woman who must Redeem a Hunter in Ancient Japan', 2006),
(229, 'DEVIL DESIRE', 'A Beautiful Reflection of a Monkey And a Dentist who must Face a Database Administrator in Ancient Japan', 2006),
(230, 'DIARY PANIC', 'A Thoughtful Character Study of a Frisbee And a Mad Cow who must Outgun a Man in Ancient India', 2006),
(231, 'DINOSAUR SECRETARY', 'A Action-Packed Drama of a Feminist And a Girl who must Reach a Robot in The Canadian Rockies', 2006),
(232, 'DIRTY ACE', 'A Action-Packed Character Study of a Forensic Psychologist And a Girl who must Build a Dentist in The Outback', 2006),
(233, 'DISCIPLE MOTHER', 'A Touching Reflection of a Mad Scientist And a Boat who must Face a Moose in A Shark Tank', 2006),
(234, 'DISTURBING SCARFACE', 'A Lacklusture Display of a Crocodile And a Butler who must Overcome a Monkey in A U-Boat', 2006),
(235, 'DIVIDE MONSTER', 'A Intrepid Saga of a Man And a Forensic Psychologist who must Reach a Squirrel in A Monastery', 2006),
(236, 'DIVINE RESURRECTION', 'A Boring Character Study of a Man And a Womanizer who must Succumb a Teacher in An Abandoned Amusement Park', 2006),
(237, 'DIVORCE SHINING', 'A Unbelieveable Saga of a Crocodile And a Student who must Discover a Cat in Ancient India', 2006),
(238, 'DOCTOR GRAIL', 'A Insightful Drama of a Womanizer And a Waitress who must Reach a Forensic Psychologist in The Outback', 2006),
(239, 'DOGMA FAMILY', 'A Brilliant Character Study of a Database Administrator And a Monkey who must Succumb a Astronaut in New Orleans', 2006),
(240, 'DOLLS RAGE', 'A Thrilling Display of a Pioneer And a Frisbee who must Escape a Teacher in The Outback', 2006),
(241, 'DONNIE ALLEY', 'A Awe-Inspiring Tale of a Butler And a Frisbee who must Vanquish a Teacher in Ancient Japan', 2006),
(242, 'DOOM DANCING', 'A Astounding Panorama of a Car And a Mad Scientist who must Battle a Lumberjack in A MySQL Convention', 2006),
(243, 'DOORS PRESIDENT', 'A Awe-Inspiring Display of a Squirrel And a Woman who must Overcome a Boy in The Gulf of Mexico', 2006),
(244, 'DORADO NOTTING', 'A Action-Packed Tale of a Sumo Wrestler And a A Shark who must Meet a Frisbee in California', 2006),
(245, 'DOUBLE WRATH', 'A Thoughtful Yarn of a Womanizer And a Dog who must Challenge a Madman in The Gulf of Mexico', 2006),
(246, 'DOUBTFIRE LABYRINTH', 'A Intrepid Panorama of a Butler And a Composer who must Meet a Mad Cow in The Sahara Desert', 2006),
(247, 'DOWNHILL ENOUGH', 'A Emotional Tale of a Pastry Chef And a Forensic Psychologist who must Succumb a Monkey in The Sahara Desert', 2006),
(248, 'DOZEN LION', 'A Taut Drama of a Cat And a Girl who must Defeat a Frisbee in The Canadian Rockies', 2006),
(249, 'DRACULA CRYSTAL', 'A Thrilling Reflection of a Feminist And a Cat who must Find a Frisbee in An Abandoned Fun House', 2006),
(250, 'DRAGON SQUAD', 'A Taut Reflection of a Boy And a Waitress who must Outgun a Teacher in Ancient China', 2006),
(251, 'DRAGONFLY STRANGERS', 'A Boring Documentary of a Pioneer And a Man who must Vanquish a Man in Nigeria', 2006),
(252, 'DREAM PICKUP', 'A Epic Display of a Car And a Composer who must Overcome a Forensic Psychologist in The Gulf of Mexico', 2006),
(253, 'DRIFTER COMMANDMENTS', 'A Epic Reflection of a Womanizer And a Squirrel who must Discover a Husband in A Jet Boat', 2006),
(254, 'DRIVER ANNIE', 'A Lacklusture Character Study of a Butler And a Car who must Redeem a Boat in An Abandoned Fun House', 2006),
(255, 'DRIVING POLISH', 'A Action-Packed Yarn of a Feminist And a Technical Writer who must Sink a Boat in An Abandoned Mine Shaft', 2006),
(256, 'DROP WATERFRONT', 'A Fanciful Documentary of a Husband And a Explorer who must Reach a Madman in Ancient China', 2006),
(257, 'DRUMLINE CYCLONE', 'A Insightful Panorama of a Monkey And a Sumo Wrestler who must Outrace a Mad Scientist in The Canadian Rockies', 2006),
(258, 'DRUMS DYNAMITE', 'A Epic Display of a Crocodile And a Crocodile who must Confront a Dog in An Abandoned Amusement Park', 2006),
(259, 'DUCK RACER', 'A Lacklusture Yarn of a Teacher And a Squirrel who must Overcome a Dog in A Shark Tank', 2006),
(260, 'DUDE BLINDNESS', 'A Stunning Reflection of a Husband And a Lumberjack who must Face a Frisbee in An Abandoned Fun House', 2006),
(261, 'DUFFEL APOCALYPSE', 'A Emotional Display of a Boat And a Explorer who must Challenge a Madman in A MySQL Convention', 2006),
(262, 'DUMBO LUST', 'A Touching Display of a Feminist And a Dentist who must Conquer a Husband in The Gulf of Mexico', 2006),
(263, 'DURHAM PANKY', 'A Brilliant Panorama of a Girl And a Boy who must Face a Mad Scientist in An Abandoned Mine Shaft', 2006),
(264, 'DWARFS ALTER', 'A Emotional Yarn of a Girl And a Dog who must Challenge a Composer in Ancient Japan', 2006),
(265, 'DYING MAKER', 'A Intrepid Tale of a Boat And a Monkey who must Kill a Cat in California', 2006),
(266, 'DYNAMITE TARZAN', 'A Intrepid Documentary of a Forensic Psychologist And a Mad Scientist who must Face a Explorer in A U-Boat', 2006),
(267, 'EAGLES PANKY', 'A Thoughtful Story of a Car And a Boy who must Find a A Shark in The Sahara Desert', 2006),
(268, 'EARLY HOME', 'A Amazing Panorama of a Mad Scientist And a Husband who must Meet a Woman in The Outback', 2006),
(269, 'EARRING INSTINCT', 'A Stunning Character Study of a Dentist And a Mad Cow who must Find a Teacher in Nigeria', 2006),
(270, 'EARTH VISION', 'A Stunning Drama of a Butler And a Madman who must Outrace a Womanizer in Ancient India', 2006),
(271, 'EASY GLADIATOR', 'A Fateful Story of a Monkey And a Girl who must Overcome a Pastry Chef in Ancient India', 2006),
(272, 'EDGE KISSING', 'A Beautiful Yarn of a Composer And a Mad Cow who must Redeem a Mad Scientist in A Jet Boat', 2006),
(273, 'EFFECT GLADIATOR', 'A Beautiful Display of a Pastry Chef And a Pastry Chef who must Outgun a Forensic Psychologist in A Manhattan Penthouse', 2006),
(274, 'EGG IGBY', 'A Beautiful Documentary of a Boat And a Sumo Wrestler who must Succumb a Database Administrator in The First Manned Space Station', 2006),
(275, 'EGYPT TENENBAUMS', 'A Intrepid Story of a Madman And a Secret Agent who must Outrace a Astronaut in An Abandoned Amusement Park', 2006),
(276, 'ELEMENT FREDDY', 'A Awe-Inspiring Reflection of a Waitress And a Squirrel who must Kill a Mad Cow in A Jet Boat', 2006),
(277, 'ELEPHANT TROJAN', 'A Beautiful Panorama of a Lumberjack And a Forensic Psychologist who must Overcome a Frisbee in A Baloon', 2006),
(278, 'ELF MURDER', 'A Action-Packed Story of a Frisbee And a Woman who must Reach a Girl in An Abandoned Mine Shaft', 2006),
(279, 'ELIZABETH SHANE', 'A Lacklusture Display of a Womanizer And a Dog who must Face a Sumo Wrestler in Ancient Japan', 2006),
(280, 'EMPIRE MALKOVICH', 'A Amazing Story of a Feminist And a Cat who must Face a Car in An Abandoned Fun House', 2006),
(281, 'ENCINO ELF', 'A Astounding Drama of a Feminist And a Teacher who must Confront a Husband in A Baloon', 2006),
(282, 'ENCOUNTERS CURTAIN', 'A Insightful Epistle of a Pastry Chef And a Womanizer who must Build a Boat in New Orleans', 2006),
(283, 'ENDING CROWDS', 'A Unbelieveable Display of a Dentist And a Madman who must Vanquish a Squirrel in Berlin', 2006),
(284, 'ENEMY ODDS', 'A Fanciful Panorama of a Mad Scientist And a Woman who must Pursue a Astronaut in Ancient India', 2006),
(285, 'ENGLISH BULWORTH', 'A Intrepid Epistle of a Pastry Chef And a Pastry Chef who must Pursue a Crocodile in Ancient China', 2006),
(286, 'ENOUGH RAGING', 'A Astounding Character Study of a Boat And a Secret Agent who must Find a Mad Cow in The Sahara Desert', 2006),
(287, 'ENTRAPMENT SATISFACTION', 'A Thoughtful Panorama of a Hunter And a Teacher who must Reach a Mad Cow in A U-Boat', 2006),
(288, 'ESCAPE METROPOLIS', 'A Taut Yarn of a Astronaut And a Technical Writer who must Outgun a Boat in New Orleans', 2006),
(289, 'EVE RESURRECTION', 'A Awe-Inspiring Yarn of a Pastry Chef And a Database Administrator who must Challenge a Teacher in A Baloon', 2006),
(290, 'EVERYONE CRAFT', 'A Fateful Display of a Waitress And a Dentist who must Reach a Butler in Nigeria', 2006),
(291, 'EVOLUTION ALTER', 'A Fanciful Character Study of a Feminist And a Madman who must Find a Explorer in A Baloon Factory', 2006),
(292, 'EXCITEMENT EVE', 'A Brilliant Documentary of a Monkey And a Car who must Conquer a Crocodile in A Shark Tank', 2006),
(293, 'EXORCIST STING', 'A Touching Drama of a Dog And a Sumo Wrestler who must Conquer a Mad Scientist in Berlin', 2006),
(294, 'EXPECATIONS NATURAL', 'A Amazing Drama of a Butler And a Husband who must Reach a A Shark in A U-Boat', 2006),
(295, 'EXPENDABLE STALLION', 'A Amazing Character Study of a Mad Cow And a Squirrel who must Discover a Hunter in A U-Boat', 2006),
(296, 'EXPRESS LONELY', 'A Boring Drama of a Astronaut And a Boat who must Face a Boat in California', 2006),
(297, 'EXTRAORDINARY CONQUERER', 'A Stunning Story of a Dog And a Feminist who must Face a Forensic Psychologist in Berlin', 2006),
(298, 'EYES DRIVING', 'A Thrilling Story of a Cat And a Waitress who must Fight a Explorer in The Outback', 2006),
(299, 'FACTORY DRAGON', 'A Action-Packed Saga of a Teacher And a Frisbee who must Escape a Lumberjack in The Sahara Desert', 2006),
(300, 'FALCON VOLUME', 'A Fateful Saga of a Sumo Wrestler And a Hunter who must Redeem a A Shark in New Orleans', 2006),
(301, 'FAMILY SWEET', 'A Epic Documentary of a Teacher And a Boy who must Escape a Woman in Berlin', 2006),
(302, 'FANTASIA PARK', 'A Thoughtful Documentary of a Mad Scientist And a A Shark who must Outrace a Feminist in Australia', 2006),
(303, 'FANTASY TROOPERS', 'A Touching Saga of a Teacher And a Monkey who must Overcome a Secret Agent in A MySQL Convention', 2006),
(304, 'FARGO GANDHI', 'A Thrilling Reflection of a Pastry Chef And a Crocodile who must Reach a Teacher in The Outback', 2006),
(305, 'FATAL HAUNTED', 'A Beautiful Drama of a Student And a Secret Agent who must Confront a Dentist in Ancient Japan', 2006),
(306, 'FEATHERS METAL', 'A Thoughtful Yarn of a Monkey And a Teacher who must Find a Dog in Australia', 2006),
(307, 'FELLOWSHIP AUTUMN', 'A Lacklusture Reflection of a Dentist And a Hunter who must Meet a Teacher in A Baloon', 2006),
(308, 'FERRIS MOTHER', 'A Touching Display of a Frisbee And a Frisbee who must Kill a Girl in The Gulf of Mexico', 2006),
(309, 'FEUD FROGMEN', 'A Brilliant Reflection of a Database Administrator And a Mad Cow who must Chase a Woman in The Canadian Rockies', 2006),
(310, 'FEVER EMPIRE', 'A Insightful Panorama of a Cat And a Boat who must Defeat a Boat in The Gulf of Mexico', 2006),
(311, 'FICTION CHRISTMAS', 'A Emotional Yarn of a A Shark And a Student who must Battle a Robot in An Abandoned Mine Shaft', 2006),
(312, 'FIDDLER LOST', 'A Boring Tale of a Squirrel And a Dog who must Challenge a Madman in The Gulf of Mexico', 2006),
(313, 'FIDELITY DEVIL', 'A Awe-Inspiring Drama of a Technical Writer And a Composer who must Reach a Pastry Chef in A U-Boat', 2006),
(314, 'FIGHT JAWBREAKER', 'A Intrepid Panorama of a Womanizer And a Girl who must Escape a Girl in A Manhattan Penthouse', 2006),
(315, 'FINDING ANACONDA', 'A Fateful Tale of a Database Administrator And a Girl who must Battle a Squirrel in New Orleans', 2006),
(316, 'FIRE WOLVES', 'A Intrepid Documentary of a Frisbee And a Dog who must Outrace a Lumberjack in Nigeria', 2006),
(317, 'FIREBALL PHILADELPHIA', 'A Amazing Yarn of a Dentist And a A Shark who must Vanquish a Madman in An Abandoned Mine Shaft', 2006),
(318, 'FIREHOUSE VIETNAM', 'A Awe-Inspiring Character Study of a Boat And a Boy who must Kill a Pastry Chef in The Sahara Desert', 2006),
(319, 'FISH OPUS', 'A Touching Display of a Feminist And a Girl who must Confront a Astronaut in Australia', 2006),
(320, 'FLAMINGOS CONNECTICUT', 'A Fast-Paced Reflection of a Composer And a Composer who must Meet a Cat in The Sahara Desert', 2006),
(321, 'FLASH WARS', 'A Astounding Saga of a Moose And a Pastry Chef who must Chase a Student in The Gulf of Mexico', 2006),
(322, 'FLATLINERS KILLER', 'A Taut Display of a Secret Agent And a Waitress who must Sink a Robot in An Abandoned Mine Shaft', 2006),
(323, 'FLIGHT LIES', 'A Stunning Character Study of a Crocodile And a Pioneer who must Pursue a Teacher in New Orleans', 2006),
(324, 'FLINTSTONES HAPPINESS', 'A Fateful Story of a Husband And a Moose who must Vanquish a Boy in California', 2006),
(325, 'FLOATS GARDEN', 'A Action-Packed Epistle of a Robot And a Car who must Chase a Boat in Ancient Japan', 2006),
(326, 'FLYING HOOK', 'A Thrilling Display of a Mad Cow And a Dog who must Challenge a Frisbee in Nigeria', 2006),
(327, 'FOOL MOCKINGBIRD', 'A Lacklusture Tale of a Crocodile And a Composer who must Defeat a Madman in A U-Boat', 2006),
(328, 'FOREVER CANDIDATE', 'A Unbelieveable Panorama of a Technical Writer And a Man who must Pursue a Frisbee in A U-Boat', 2006),
(329, 'FORREST SONS', 'A Thrilling Documentary of a Forensic Psychologist And a Butler who must Defeat a Explorer in A Jet Boat', 2006),
(330, 'FORRESTER COMANCHEROS', 'A Fateful Tale of a Squirrel And a Forensic Psychologist who must Redeem a Man in Nigeria', 2006),
(331, 'FORWARD TEMPLE', 'A Astounding Display of a Forensic Psychologist And a Mad Scientist who must Challenge a Girl in New Orleans', 2006),
(332, 'FRANKENSTEIN STRANGER', 'A Insightful Character Study of a Feminist And a Pioneer who must Pursue a Pastry Chef in Nigeria', 2006),
(333, 'FREAKY POCUS', 'A Fast-Paced Documentary of a Pastry Chef And a Crocodile who must Chase a Squirrel in The Gulf of Mexico', 2006),
(334, 'FREDDY STORM', 'A Intrepid Saga of a Man And a Lumberjack who must Vanquish a Husband in The Outback', 2006),
(335, 'FREEDOM CLEOPATRA', 'A Emotional Reflection of a Dentist And a Mad Cow who must Face a Squirrel in A Baloon', 2006),
(336, 'FRENCH HOLIDAY', 'A Thrilling Epistle of a Dog And a Feminist who must Kill a Madman in Berlin', 2006),
(337, 'FRIDA SLIPPER', 'A Fateful Story of a Lumberjack And a Car who must Escape a Boat in An Abandoned Mine Shaft', 2006),
(338, 'FRISCO FORREST', 'A Beautiful Documentary of a Woman And a Pioneer who must Pursue a Mad Scientist in A Shark Tank', 2006),
(339, 'FROGMEN BREAKING', 'A Unbelieveable Yarn of a Mad Scientist And a Cat who must Chase a Lumberjack in Australia', 2006),
(340, 'FRONTIER CABIN', 'A Emotional Story of a Madman And a Waitress who must Battle a Teacher in An Abandoned Fun House', 2006),
(341, 'FROST HEAD', 'A Amazing Reflection of a Lumberjack And a Cat who must Discover a Husband in A MySQL Convention', 2006),
(342, 'FUGITIVE MAGUIRE', 'A Taut Epistle of a Feminist And a Sumo Wrestler who must Battle a Crocodile in Australia', 2006),
(343, 'FULL FLATLINERS', 'A Beautiful Documentary of a Astronaut And a Moose who must Pursue a Monkey in A Shark Tank', 2006),
(344, 'FURY MURDER', 'A Lacklusture Reflection of a Boat And a Forensic Psychologist who must Fight a Waitress in A Monastery', 2006),
(345, 'GABLES METROPOLIS', 'A Fateful Display of a Cat And a Pioneer who must Challenge a Pastry Chef in A Baloon Factory', 2006),
(346, 'GALAXY SWEETHEARTS', 'A Emotional Reflection of a Womanizer And a Pioneer who must Face a Squirrel in Berlin', 2006),
(347, 'GAMES BOWFINGER', 'A Astounding Documentary of a Butler And a Explorer who must Challenge a Butler in A Monastery', 2006),
(348, 'GANDHI KWAI', 'A Thoughtful Display of a Mad Scientist And a Secret Agent who must Chase a Boat in Berlin', 2006),
(349, 'GANGS PRIDE', 'A Taut Character Study of a Woman And a A Shark who must Confront a Frisbee in Berlin', 2006),
(350, 'GARDEN ISLAND', 'A Unbelieveable Character Study of a Womanizer And a Madman who must Reach a Man in The Outback', 2006),
(351, 'GASLIGHT CRUSADE', 'A Amazing Epistle of a Boy And a Astronaut who must Redeem a Man in The Gulf of Mexico', 2006),
(352, 'GATHERING CALENDAR', 'A Intrepid Tale of a Pioneer And a Moose who must Conquer a Frisbee in A MySQL Convention', 2006),
(353, 'GENTLEMEN STAGE', 'A Awe-Inspiring Reflection of a Monkey And a Student who must Overcome a Dentist in The First Manned Space Station', 2006),
(354, 'GHOST GROUNDHOG', 'A Brilliant Panorama of a Madman And a Composer who must Succumb a Car in Ancient India', 2006),
(355, 'GHOSTBUSTERS ELF', 'A Thoughtful Epistle of a Dog And a Feminist who must Chase a Composer in Berlin', 2006),
(356, 'GIANT TROOPERS', 'A Fateful Display of a Feminist And a Monkey who must Vanquish a Monkey in The Canadian Rockies', 2006),
(357, 'GILBERT PELICAN', 'A Fateful Tale of a Man And a Feminist who must Conquer a Crocodile in A Manhattan Penthouse', 2006),
(358, 'GILMORE BOILED', 'A Unbelieveable Documentary of a Boat And a Husband who must Succumb a Student in A U-Boat', 2006),
(359, 'GLADIATOR WESTWARD', 'A Astounding Reflection of a Squirrel And a Sumo Wrestler who must Sink a Dentist in Ancient Japan', 2006),
(360, 'GLASS DYING', 'A Astounding Drama of a Frisbee And a Astronaut who must Fight a Dog in Ancient Japan', 2006),
(361, 'GLEAMING JAWBREAKER', 'A Amazing Display of a Composer And a Forensic Psychologist who must Discover a Car in The Canadian Rockies', 2006),
(362, 'GLORY TRACY', 'A Amazing Saga of a Woman And a Womanizer who must Discover a Cat in The First Manned Space Station', 2006),
(363, 'GO PURPLE', 'A Fast-Paced Display of a Car And a Database Administrator who must Battle a Woman in A Baloon', 2006),
(364, 'GODFATHER DIARY', 'A Stunning Saga of a Lumberjack And a Squirrel who must Chase a Car in The Outback', 2006),
(365, 'GOLD RIVER', 'A Taut Documentary of a Database Administrator And a Waitress who must Reach a Mad Scientist in A Baloon Factory', 2006),
(366, 'GOLDFINGER SENSIBILITY', 'A Insightful Drama of a Mad Scientist And a Hunter who must Defeat a Pastry Chef in New Orleans', 2006),
(367, 'GOLDMINE TYCOON', 'A Brilliant Epistle of a Composer And a Frisbee who must Conquer a Husband in The Outback', 2006),
(368, 'GONE TROUBLE', 'A Insightful Character Study of a Mad Cow And a Forensic Psychologist who must Conquer a A Shark in A Manhattan Penthouse', 2006),
(369, 'GOODFELLAS SALUTE', 'A Unbelieveable Tale of a Dog And a Explorer who must Sink a Mad Cow in A Baloon Factory', 2006),
(370, 'GORGEOUS BINGO', 'A Action-Packed Display of a Sumo Wrestler And a Car who must Overcome a Waitress in A Baloon Factory', 2006),
(371, 'GOSFORD DONNIE', 'A Epic Panorama of a Mad Scientist And a Monkey who must Redeem a Secret Agent in Berlin', 2006),
(372, 'GRACELAND DYNAMITE', 'A Taut Display of a Cat And a Girl who must Overcome a Database Administrator in New Orleans', 2006),
(373, 'GRADUATE LORD', 'A Lacklusture Epistle of a Girl And a A Shark who must Meet a Mad Scientist in Ancient China', 2006),
(374, 'GRAFFITI LOVE', 'A Unbelieveable Epistle of a Sumo Wrestler And a Hunter who must Build a Composer in Berlin', 2006),
(375, 'GRAIL FRANKENSTEIN', 'A Unbelieveable Saga of a Teacher And a Monkey who must Fight a Girl in An Abandoned Mine Shaft', 2006),
(376, 'GRAPES FURY', 'A Boring Yarn of a Mad Cow And a Sumo Wrestler who must Meet a Robot in Australia', 2006),
(377, 'GREASE YOUTH', 'A Emotional Panorama of a Secret Agent And a Waitress who must Escape a Composer in Soviet Georgia', 2006),
(378, 'GREATEST NORTH', 'A Astounding Character Study of a Secret Agent And a Robot who must Build a A Shark in Berlin', 2006),
(379, 'GREEDY ROOTS', 'A Amazing Reflection of a A Shark And a Butler who must Chase a Hunter in The Canadian Rockies', 2006),
(380, 'GREEK EVERYONE', 'A Stunning Display of a Butler And a Teacher who must Confront a A Shark in The First Manned Space Station', 2006),
(381, 'GRINCH MASSAGE', 'A Intrepid Display of a Madman And a Feminist who must Pursue a Pioneer in The First Manned Space Station', 2006),
(382, 'GRIT CLOCKWORK', 'A Thoughtful Display of a Dentist And a Squirrel who must Confront a Lumberjack in A Shark Tank', 2006),
(383, 'GROOVE FICTION', 'A Unbelieveable Reflection of a Moose And a A Shark who must Defeat a Lumberjack in An Abandoned Mine Shaft', 2006),
(384, 'GROSSE WONDERFUL', 'A Epic Drama of a Cat And a Explorer who must Redeem a Moose in Australia', 2006),
(385, 'GROUNDHOG UNCUT', 'A Brilliant Panorama of a Astronaut And a Technical Writer who must Discover a Butler in A Manhattan Penthouse', 2006),
(386, 'GUMP DATE', 'A Intrepid Yarn of a Explorer And a Student who must Kill a Husband in An Abandoned Mine Shaft', 2006),
(387, 'GUN BONNIE', 'A Boring Display of a Sumo Wrestler And a Husband who must Build a Waitress in The Gulf of Mexico', 2006),
(388, 'GUNFIGHT MOON', 'A Epic Reflection of a Pastry Chef And a Explorer who must Reach a Dentist in The Sahara Desert', 2006),
(389, 'GUNFIGHTER MUSSOLINI', 'A Touching Saga of a Robot And a Boy who must Kill a Man in Ancient Japan', 2006),
(390, 'GUYS FALCON', 'A Boring Story of a Woman And a Feminist who must Redeem a Squirrel in A U-Boat', 2006),
(391, 'HALF OUTFIELD', 'A Epic Epistle of a Database Administrator And a Crocodile who must Face a Madman in A Jet Boat', 2006);
INSERT INTO `film` (`film_id`, `title`, `description`, `release_year`) VALUES
(392, 'HALL CASSIDY', 'A Beautiful Panorama of a Pastry Chef And a A Shark who must Battle a Pioneer in Soviet Georgia', 2006),
(393, 'HALLOWEEN NUTS', 'A Amazing Panorama of a Forensic Psychologist And a Technical Writer who must Fight a Dentist in A U-Boat', 2006),
(394, 'HAMLET WISDOM', 'A Touching Reflection of a Man And a Man who must Sink a Robot in The Outback', 2006),
(395, 'HANDICAP BOONDOCK', 'A Beautiful Display of a Pioneer And a Squirrel who must Vanquish a Sumo Wrestler in Soviet Georgia', 2006),
(396, 'HANGING DEEP', 'A Action-Packed Yarn of a Boat And a Crocodile who must Build a Monkey in Berlin', 2006),
(397, 'HANKY OCTOBER', 'A Boring Epistle of a Database Administrator And a Explorer who must Pursue a Madman in Soviet Georgia', 2006),
(398, 'HANOVER GALAXY', 'A Stunning Reflection of a Girl And a Secret Agent who must Succumb a Boy in A MySQL Convention', 2006),
(399, 'HAPPINESS UNITED', 'A Action-Packed Panorama of a Husband And a Feminist who must Meet a Forensic Psychologist in Ancient Japan', 2006),
(400, 'HARDLY ROBBERS', 'A Emotional Character Study of a Hunter And a Car who must Kill a Woman in Berlin', 2006),
(401, 'HAROLD FRENCH', 'A Stunning Saga of a Sumo Wrestler And a Student who must Outrace a Moose in The Sahara Desert', 2006),
(402, 'HARPER DYING', 'A Awe-Inspiring Reflection of a Woman And a Cat who must Confront a Feminist in The Sahara Desert', 2006),
(403, 'HARRY IDAHO', 'A Taut Yarn of a Technical Writer And a Feminist who must Outrace a Dog in California', 2006),
(404, 'HATE HANDICAP', 'A Intrepid Reflection of a Mad Scientist And a Pioneer who must Overcome a Hunter in The First Manned Space Station', 2006),
(405, 'HAUNTED ANTITRUST', 'A Amazing Saga of a Man And a Dentist who must Reach a Technical Writer in Ancient India', 2006),
(406, 'HAUNTING PIANIST', 'A Fast-Paced Story of a Database Administrator And a Composer who must Defeat a Squirrel in An Abandoned Amusement Park', 2006),
(407, 'HAWK CHILL', 'A Action-Packed Drama of a Mad Scientist And a Composer who must Outgun a Car in Australia', 2006),
(408, 'HEAD STRANGER', 'A Thoughtful Saga of a Hunter And a Crocodile who must Confront a Dog in The Gulf of Mexico', 2006),
(409, 'HEARTBREAKERS BRIGHT', 'A Awe-Inspiring Documentary of a A Shark And a Dentist who must Outrace a Pastry Chef in The Canadian Rockies', 2006),
(410, 'HEAVEN FREEDOM', 'A Intrepid Story of a Butler And a Car who must Vanquish a Man in New Orleans', 2006),
(411, 'HEAVENLY GUN', 'A Beautiful Yarn of a Forensic Psychologist And a Frisbee who must Battle a Moose in A Jet Boat', 2006),
(412, 'HEAVYWEIGHTS BEAST', 'A Unbelieveable Story of a Composer And a Dog who must Overcome a Womanizer in An Abandoned Amusement Park', 2006),
(413, 'HEDWIG ALTER', 'A Action-Packed Yarn of a Womanizer And a Lumberjack who must Chase a Sumo Wrestler in A Monastery', 2006),
(414, 'HELLFIGHTERS SIERRA', 'A Taut Reflection of a A Shark And a Dentist who must Battle a Boat in Soviet Georgia', 2006),
(415, 'HIGH ENCINO', 'A Fateful Saga of a Waitress And a Hunter who must Outrace a Sumo Wrestler in Australia', 2006),
(416, 'HIGHBALL POTTER', 'A Action-Packed Saga of a Husband And a Dog who must Redeem a Database Administrator in The Sahara Desert', 2006),
(417, 'HILLS NEIGHBORS', 'A Epic Display of a Hunter And a Feminist who must Sink a Car in A U-Boat', 2006),
(418, 'HOBBIT ALIEN', 'A Emotional Drama of a Husband And a Girl who must Outgun a Composer in The First Manned Space Station', 2006),
(419, 'HOCUS FRIDA', 'A Awe-Inspiring Tale of a Girl And a Madman who must Outgun a Student in A Shark Tank', 2006),
(420, 'HOLES BRANNIGAN', 'A Fast-Paced Reflection of a Technical Writer And a Student who must Fight a Boy in The Canadian Rockies', 2006),
(421, 'HOLIDAY GAMES', 'A Insightful Reflection of a Waitress And a Madman who must Pursue a Boy in Ancient Japan', 2006),
(422, 'HOLLOW JEOPARDY', 'A Beautiful Character Study of a Robot And a Astronaut who must Overcome a Boat in A Monastery', 2006),
(423, 'HOLLYWOOD ANONYMOUS', 'A Fast-Paced Epistle of a Boy And a Explorer who must Escape a Dog in A U-Boat', 2006),
(424, 'HOLOCAUST HIGHBALL', 'A Awe-Inspiring Yarn of a Composer And a Man who must Find a Robot in Soviet Georgia', 2006),
(425, 'HOLY TADPOLE', 'A Action-Packed Display of a Feminist And a Pioneer who must Pursue a Dog in A Baloon Factory', 2006),
(426, 'HOME PITY', 'A Touching Panorama of a Man And a Secret Agent who must Challenge a Teacher in A MySQL Convention', 2006),
(427, 'HOMEWARD CIDER', 'A Taut Reflection of a Astronaut And a Squirrel who must Fight a Squirrel in A Manhattan Penthouse', 2006),
(428, 'HOMICIDE PEACH', 'A Astounding Documentary of a Hunter And a Boy who must Confront a Boy in A MySQL Convention', 2006),
(429, 'HONEY TIES', 'A Taut Story of a Waitress And a Crocodile who must Outrace a Lumberjack in A Shark Tank', 2006),
(430, 'HOOK CHARIOTS', 'A Insightful Story of a Boy And a Dog who must Redeem a Boy in Australia', 2006),
(431, 'HOOSIERS BIRDCAGE', 'A Astounding Display of a Explorer And a Boat who must Vanquish a Car in The First Manned Space Station', 2006),
(432, 'HOPE TOOTSIE', 'A Amazing Documentary of a Student And a Sumo Wrestler who must Outgun a A Shark in A Shark Tank', 2006),
(433, 'HORN WORKING', 'A Stunning Display of a Mad Scientist And a Technical Writer who must Succumb a Monkey in A Shark Tank', 2006),
(434, 'HORROR REIGN', 'A Touching Documentary of a A Shark And a Car who must Build a Husband in Nigeria', 2006),
(435, 'HOTEL HAPPINESS', 'A Thrilling Yarn of a Pastry Chef And a A Shark who must Challenge a Mad Scientist in The Outback', 2006),
(436, 'HOURS RAGE', 'A Fateful Story of a Explorer And a Feminist who must Meet a Technical Writer in Soviet Georgia', 2006),
(437, 'HOUSE DYNAMITE', 'A Taut Story of a Pioneer And a Squirrel who must Battle a Student in Soviet Georgia', 2006),
(438, 'HUMAN GRAFFITI', 'A Beautiful Reflection of a Womanizer And a Sumo Wrestler who must Chase a Database Administrator in The Gulf of Mexico', 2006),
(439, 'HUNCHBACK IMPOSSIBLE', 'A Touching Yarn of a Frisbee And a Dentist who must Fight a Composer in Ancient Japan', 2006),
(440, 'HUNGER ROOF', 'A Unbelieveable Yarn of a Student And a Database Administrator who must Outgun a Husband in An Abandoned Mine Shaft', 2006),
(441, 'HUNTER ALTER', 'A Emotional Drama of a Mad Cow And a Boat who must Redeem a Secret Agent in A Shark Tank', 2006),
(442, 'HUNTING MUSKETEERS', 'A Thrilling Reflection of a Pioneer And a Dentist who must Outrace a Womanizer in An Abandoned Mine Shaft', 2006),
(443, 'HURRICANE AFFAIR', 'A Lacklusture Epistle of a Database Administrator And a Woman who must Meet a Hunter in An Abandoned Mine Shaft', 2006),
(444, 'HUSTLER PARTY', 'A Emotional Reflection of a Sumo Wrestler And a Monkey who must Conquer a Robot in The Sahara Desert', 2006),
(445, 'HYDE DOCTOR', 'A Fanciful Documentary of a Boy And a Woman who must Redeem a Womanizer in A Jet Boat', 2006),
(446, 'HYSTERICAL GRAIL', 'A Amazing Saga of a Madman And a Dentist who must Build a Car in A Manhattan Penthouse', 2006),
(447, 'ICE CROSSING', 'A Fast-Paced Tale of a Butler And a Moose who must Overcome a Pioneer in A Manhattan Penthouse', 2006),
(448, 'IDAHO LOVE', 'A Fast-Paced Drama of a Student And a Crocodile who must Meet a Database Administrator in The Outback', 2006),
(449, 'IDENTITY LOVER', 'A Boring Tale of a Composer And a Mad Cow who must Defeat a Car in The Outback', 2006),
(450, 'IDOLS SNATCHERS', 'A Insightful Drama of a Car And a Composer who must Fight a Man in A Monastery', 2006),
(451, 'IGBY MAKER', 'A Epic Documentary of a Hunter And a Dog who must Outgun a Dog in A Baloon Factory', 2006),
(452, 'ILLUSION AMELIE', 'A Emotional Epistle of a Boat And a Mad Scientist who must Outrace a Robot in An Abandoned Mine Shaft', 2006),
(453, 'IMAGE PRINCESS', 'A Lacklusture Panorama of a Secret Agent And a Crocodile who must Discover a Madman in The Canadian Rockies', 2006),
(454, 'IMPACT ALADDIN', 'A Epic Character Study of a Frisbee And a Moose who must Outgun a Technical Writer in A Shark Tank', 2006),
(455, 'IMPOSSIBLE PREJUDICE', 'A Awe-Inspiring Yarn of a Monkey And a Hunter who must Chase a Teacher in Ancient China', 2006),
(456, 'INCH JET', 'A Fateful Saga of a Womanizer And a Student who must Defeat a Butler in A Monastery', 2006),
(457, 'INDEPENDENCE HOTEL', 'A Thrilling Tale of a Technical Writer And a Boy who must Face a Pioneer in A Monastery', 2006),
(458, 'INDIAN LOVE', 'A Insightful Saga of a Mad Scientist And a Mad Scientist who must Kill a Astronaut in An Abandoned Fun House', 2006),
(459, 'INFORMER DOUBLE', 'A Action-Packed Display of a Woman And a Dentist who must Redeem a Forensic Psychologist in The Canadian Rockies', 2006),
(460, 'INNOCENT USUAL', 'A Beautiful Drama of a Pioneer And a Crocodile who must Challenge a Student in The Outback', 2006),
(461, 'INSECTS STONE', 'A Epic Display of a Butler And a Dog who must Vanquish a Crocodile in A Manhattan Penthouse', 2006),
(462, 'INSIDER ARIZONA', 'A Astounding Saga of a Mad Scientist And a Hunter who must Pursue a Robot in A Baloon Factory', 2006),
(463, 'INSTINCT AIRPORT', 'A Touching Documentary of a Mad Cow And a Explorer who must Confront a Butler in A Manhattan Penthouse', 2006),
(464, 'INTENTIONS EMPIRE', 'A Astounding Epistle of a Cat And a Cat who must Conquer a Mad Cow in A U-Boat', 2006),
(465, 'INTERVIEW LIAISONS', 'A Action-Packed Reflection of a Student And a Butler who must Discover a Database Administrator in A Manhattan Penthouse', 2006),
(466, 'INTOLERABLE INTENTIONS', 'A Awe-Inspiring Story of a Monkey And a Pastry Chef who must Succumb a Womanizer in A MySQL Convention', 2006),
(467, 'INTRIGUE WORST', 'A Fanciful Character Study of a Explorer And a Mad Scientist who must Vanquish a Squirrel in A Jet Boat', 2006),
(468, 'INVASION CYCLONE', 'A Lacklusture Character Study of a Mad Scientist And a Womanizer who must Outrace a Explorer in A Monastery', 2006),
(469, 'IRON MOON', 'A Fast-Paced Documentary of a Mad Cow And a Boy who must Pursue a Dentist in A Baloon', 2006),
(470, 'ISHTAR ROCKETEER', 'A Astounding Saga of a Dog And a Squirrel who must Conquer a Dog in An Abandoned Fun House', 2006),
(471, 'ISLAND EXORCIST', 'A Fanciful Panorama of a Technical Writer And a Boy who must Find a Dentist in An Abandoned Fun House', 2006),
(472, 'ITALIAN AFRICAN', 'A Astounding Character Study of a Monkey And a Moose who must Outgun a Cat in A U-Boat', 2006),
(473, 'JACKET FRISCO', 'A Insightful Reflection of a Womanizer And a Husband who must Conquer a Pastry Chef in A Baloon', 2006),
(474, 'JADE BUNCH', 'A Insightful Panorama of a Squirrel And a Mad Cow who must Confront a Student in The First Manned Space Station', 2006),
(475, 'JAPANESE RUN', 'A Awe-Inspiring Epistle of a Feminist And a Girl who must Sink a Girl in The Outback', 2006),
(476, 'JASON TRAP', 'A Thoughtful Tale of a Woman And a A Shark who must Conquer a Dog in A Monastery', 2006),
(477, 'JAWBREAKER BROOKLYN', 'A Stunning Reflection of a Boat And a Pastry Chef who must Succumb a A Shark in A Jet Boat', 2006),
(478, 'JAWS HARRY', 'A Thrilling Display of a Database Administrator And a Monkey who must Overcome a Dog in An Abandoned Fun House', 2006),
(479, 'JEDI BENEATH', 'A Astounding Reflection of a Explorer And a Dentist who must Pursue a Student in Nigeria', 2006),
(480, 'JEEPERS WEDDING', 'A Astounding Display of a Composer And a Dog who must Kill a Pastry Chef in Soviet Georgia', 2006),
(481, 'JEKYLL FROGMEN', 'A Fanciful Epistle of a Student And a Astronaut who must Kill a Waitress in A Shark Tank', 2006),
(482, 'JEOPARDY ENCINO', 'A Boring Panorama of a Man And a Mad Cow who must Face a Explorer in Ancient India', 2006),
(483, 'JERICHO MULAN', 'A Amazing Yarn of a Hunter And a Butler who must Defeat a Boy in A Jet Boat', 2006),
(484, 'JERK PAYCHECK', 'A Touching Character Study of a Pastry Chef And a Database Administrator who must Reach a A Shark in Ancient Japan', 2006),
(485, 'JERSEY SASSY', 'A Lacklusture Documentary of a Madman And a Mad Cow who must Find a Feminist in Ancient Japan', 2006),
(486, 'JET NEIGHBORS', 'A Amazing Display of a Lumberjack And a Teacher who must Outrace a Woman in A U-Boat', 2006),
(487, 'JINGLE SAGEBRUSH', 'A Epic Character Study of a Feminist And a Student who must Meet a Woman in A Baloon', 2006),
(488, 'JOON NORTHWEST', 'A Thrilling Panorama of a Technical Writer And a Car who must Discover a Forensic Psychologist in A Shark Tank', 2006),
(489, 'JUGGLER HARDLY', 'A Epic Story of a Mad Cow And a Astronaut who must Challenge a Car in California', 2006),
(490, 'JUMANJI BLADE', 'A Intrepid Yarn of a Husband And a Womanizer who must Pursue a Mad Scientist in New Orleans', 2006),
(491, 'JUMPING WRATH', 'A Touching Epistle of a Monkey And a Feminist who must Discover a Boat in Berlin', 2006),
(492, 'JUNGLE CLOSER', 'A Boring Character Study of a Boy And a Woman who must Battle a Astronaut in Australia', 2006),
(493, 'KANE EXORCIST', 'A Epic Documentary of a Composer And a Robot who must Overcome a Car in Berlin', 2006),
(494, 'KARATE MOON', 'A Astounding Yarn of a Womanizer And a Dog who must Reach a Waitress in A MySQL Convention', 2006),
(495, 'KENTUCKIAN GIANT', 'A Stunning Yarn of a Woman And a Frisbee who must Escape a Waitress in A U-Boat', 2006),
(496, 'KICK SAVANNAH', 'A Emotional Drama of a Monkey And a Robot who must Defeat a Monkey in New Orleans', 2006),
(497, 'KILL BROTHERHOOD', 'A Touching Display of a Hunter And a Secret Agent who must Redeem a Husband in The Outback', 2006),
(498, 'KILLER INNOCENT', 'A Fanciful Character Study of a Student And a Explorer who must Succumb a Composer in An Abandoned Mine Shaft', 2006),
(499, 'KING EVOLUTION', 'A Action-Packed Tale of a Boy And a Lumberjack who must Chase a Madman in A Baloon', 2006),
(500, 'KISS GLORY', 'A Lacklusture Reflection of a Girl And a Husband who must Find a Robot in The Canadian Rockies', 2006),
(501, 'KISSING DOLLS', 'A Insightful Reflection of a Pioneer And a Teacher who must Build a Composer in The First Manned Space Station', 2006),
(502, 'KNOCK WARLOCK', 'A Unbelieveable Story of a Teacher And a Boat who must Confront a Moose in A Baloon', 2006),
(503, 'KRAMER CHOCOLATE', 'A Amazing Yarn of a Robot And a Pastry Chef who must Redeem a Mad Scientist in The Outback', 2006),
(504, 'KWAI HOMEWARD', 'A Amazing Drama of a Car And a Squirrel who must Pursue a Car in Soviet Georgia', 2006),
(505, 'LABYRINTH LEAGUE', 'A Awe-Inspiring Saga of a Composer And a Frisbee who must Succumb a Pioneer in The Sahara Desert', 2006),
(506, 'LADY STAGE', 'A Beautiful Character Study of a Woman And a Man who must Pursue a Explorer in A U-Boat', 2006),
(507, 'LADYBUGS ARMAGEDDON', 'A Fateful Reflection of a Dog And a Mad Scientist who must Meet a Mad Scientist in New Orleans', 2006),
(508, 'LAMBS CINCINATTI', 'A Insightful Story of a Man And a Feminist who must Fight a Composer in Australia', 2006),
(509, 'LANGUAGE COWBOY', 'A Epic Yarn of a Cat And a Madman who must Vanquish a Dentist in An Abandoned Amusement Park', 2006),
(510, 'LAWLESS VISION', 'A Insightful Yarn of a Boy And a Sumo Wrestler who must Outgun a Car in The Outback', 2006),
(511, 'LAWRENCE LOVE', 'A Fanciful Yarn of a Database Administrator And a Mad Cow who must Pursue a Womanizer in Berlin', 2006),
(512, 'LEAGUE HELLFIGHTERS', 'A Thoughtful Saga of a A Shark And a Monkey who must Outgun a Student in Ancient China', 2006),
(513, 'LEATHERNECKS DWARFS', 'A Fateful Reflection of a Dog And a Mad Cow who must Outrace a Teacher in An Abandoned Mine Shaft', 2006),
(514, 'LEBOWSKI SOLDIERS', 'A Beautiful Epistle of a Secret Agent And a Pioneer who must Chase a Astronaut in Ancient China', 2006),
(515, 'LEGALLY SECRETARY', 'A Astounding Tale of a A Shark And a Moose who must Meet a Womanizer in The Sahara Desert', 2006),
(516, 'LEGEND JEDI', 'A Awe-Inspiring Epistle of a Pioneer And a Student who must Outgun a Crocodile in The Outback', 2006),
(517, 'LESSON CLEOPATRA', 'A Emotional Display of a Man And a Explorer who must Build a Boy in A Manhattan Penthouse', 2006),
(518, 'LIAISONS SWEET', 'A Boring Drama of a A Shark And a Explorer who must Redeem a Waitress in The Canadian Rockies', 2006),
(519, 'LIBERTY MAGNIFICENT', 'A Boring Drama of a Student And a Cat who must Sink a Technical Writer in A Baloon', 2006),
(520, 'LICENSE WEEKEND', 'A Insightful Story of a Man And a Husband who must Overcome a Madman in A Monastery', 2006),
(521, 'LIES TREATMENT', 'A Fast-Paced Character Study of a Dentist And a Moose who must Defeat a Composer in The First Manned Space Station', 2006),
(522, 'LIFE TWISTED', 'A Thrilling Reflection of a Teacher And a Composer who must Find a Man in The First Manned Space Station', 2006),
(523, 'LIGHTS DEER', 'A Unbelieveable Epistle of a Dog And a Woman who must Confront a Moose in The Gulf of Mexico', 2006),
(524, 'LION UNCUT', 'A Intrepid Display of a Pastry Chef And a Cat who must Kill a A Shark in Ancient China', 2006),
(525, 'LOATHING LEGALLY', 'A Boring Epistle of a Pioneer And a Mad Scientist who must Escape a Frisbee in The Gulf of Mexico', 2006),
(526, 'LOCK REAR', 'A Thoughtful Character Study of a Squirrel And a Technical Writer who must Outrace a Student in Ancient Japan', 2006),
(527, 'LOLA AGENT', 'A Astounding Tale of a Mad Scientist And a Husband who must Redeem a Database Administrator in Ancient Japan', 2006),
(528, 'LOLITA WORLD', 'A Thrilling Drama of a Girl And a Robot who must Redeem a Waitress in An Abandoned Mine Shaft', 2006),
(529, 'LONELY ELEPHANT', 'A Intrepid Story of a Student And a Dog who must Challenge a Explorer in Soviet Georgia', 2006),
(530, 'LORD ARIZONA', 'A Action-Packed Display of a Frisbee And a Pastry Chef who must Pursue a Crocodile in A Jet Boat', 2006),
(531, 'LOSE INCH', 'A Stunning Reflection of a Student And a Technical Writer who must Battle a Butler in The First Manned Space Station', 2006),
(532, 'LOSER HUSTLER', 'A Stunning Drama of a Robot And a Feminist who must Outgun a Butler in Nigeria', 2006),
(533, 'LOST BIRD', 'A Emotional Character Study of a Robot And a A Shark who must Defeat a Technical Writer in A Manhattan Penthouse', 2006),
(534, 'LOUISIANA HARRY', 'A Lacklusture Drama of a Girl And a Technical Writer who must Redeem a Monkey in A Shark Tank', 2006),
(535, 'LOVE SUICIDES', 'A Brilliant Panorama of a Hunter And a Explorer who must Pursue a Dentist in An Abandoned Fun House', 2006),
(536, 'LOVELY JINGLE', 'A Fanciful Yarn of a Crocodile And a Forensic Psychologist who must Discover a Crocodile in The Outback', 2006),
(537, 'LOVER TRUMAN', 'A Emotional Yarn of a Robot And a Boy who must Outgun a Technical Writer in A U-Boat', 2006),
(538, 'LOVERBOY ATTACKS', 'A Boring Story of a Car And a Butler who must Build a Girl in Soviet Georgia', 2006),
(539, 'LUCK OPUS', 'A Boring Display of a Moose And a Squirrel who must Outrace a Teacher in A Shark Tank', 2006),
(540, 'LUCKY FLYING', 'A Lacklusture Character Study of a A Shark And a Man who must Find a Forensic Psychologist in A U-Boat', 2006),
(541, 'LUKE MUMMY', 'A Taut Character Study of a Boy And a Robot who must Redeem a Mad Scientist in Ancient India', 2006),
(542, 'LUST LOCK', 'A Fanciful Panorama of a Hunter And a Dentist who must Meet a Secret Agent in The Sahara Desert', 2006),
(543, 'MADIGAN DORADO', 'A Astounding Character Study of a A Shark And a A Shark who must Discover a Crocodile in The Outback', 2006),
(544, 'MADISON TRAP', 'A Awe-Inspiring Reflection of a Monkey And a Dentist who must Overcome a Pioneer in A U-Boat', 2006),
(545, 'MADNESS ATTACKS', 'A Fanciful Tale of a Squirrel And a Boat who must Defeat a Crocodile in The Gulf of Mexico', 2006),
(546, 'MADRE GABLES', 'A Intrepid Panorama of a Sumo Wrestler And a Forensic Psychologist who must Discover a Moose in The First Manned Space Station', 2006),
(547, 'MAGIC MALLRATS', 'A Touching Documentary of a Pastry Chef And a Pastry Chef who must Build a Mad Scientist in California', 2006),
(548, 'MAGNIFICENT CHITTY', 'A Insightful Story of a Teacher And a Hunter who must Face a Mad Cow in California', 2006),
(549, 'MAGNOLIA FORRESTER', 'A Thoughtful Documentary of a Composer And a Explorer who must Conquer a Dentist in New Orleans', 2006),
(550, 'MAGUIRE APACHE', 'A Fast-Paced Reflection of a Waitress And a Hunter who must Defeat a Forensic Psychologist in A Baloon', 2006),
(551, 'MAIDEN HOME', 'A Lacklusture Saga of a Moose And a Teacher who must Kill a Forensic Psychologist in A MySQL Convention', 2006),
(552, 'MAJESTIC FLOATS', 'A Thrilling Character Study of a Moose And a Student who must Escape a Butler in The First Manned Space Station', 2006),
(553, 'MAKER GABLES', 'A Stunning Display of a Moose And a Database Administrator who must Pursue a Composer in A Jet Boat', 2006),
(554, 'MALKOVICH PET', 'A Intrepid Reflection of a Waitress And a A Shark who must Kill a Squirrel in The Outback', 2006),
(555, 'MALLRATS UNITED', 'A Thrilling Yarn of a Waitress And a Dentist who must Find a Hunter in A Monastery', 2006),
(556, 'MALTESE HOPE', 'A Fast-Paced Documentary of a Crocodile And a Sumo Wrestler who must Conquer a Explorer in California', 2006),
(557, 'MANCHURIAN CURTAIN', 'A Stunning Tale of a Mad Cow And a Boy who must Battle a Boy in Berlin', 2006),
(558, 'MANNEQUIN WORST', 'A Astounding Saga of a Mad Cow And a Pastry Chef who must Discover a Husband in Ancient India', 2006),
(559, 'MARRIED GO', 'A Fanciful Story of a Womanizer And a Dog who must Face a Forensic Psychologist in The Sahara Desert', 2006),
(560, 'MARS ROMAN', 'A Boring Drama of a Car And a Dog who must Succumb a Madman in Soviet Georgia', 2006),
(561, 'MASK PEACH', 'A Boring Character Study of a Student And a Robot who must Meet a Woman in California', 2006),
(562, 'MASKED BUBBLE', 'A Fanciful Documentary of a Pioneer And a Boat who must Pursue a Pioneer in An Abandoned Mine Shaft', 2006),
(563, 'MASSACRE USUAL', 'A Fateful Reflection of a Waitress And a Crocodile who must Challenge a Forensic Psychologist in California', 2006),
(564, 'MASSAGE IMAGE', 'A Fateful Drama of a Frisbee And a Crocodile who must Vanquish a Dog in The First Manned Space Station', 2006),
(565, 'MATRIX SNOWMAN', 'A Action-Packed Saga of a Womanizer And a Woman who must Overcome a Student in California', 2006),
(566, 'MAUDE MOD', 'A Beautiful Documentary of a Forensic Psychologist And a Cat who must Reach a Astronaut in Nigeria', 2006),
(567, 'MEET CHOCOLATE', 'A Boring Documentary of a Dentist And a Butler who must Confront a Monkey in A MySQL Convention', 2006),
(568, 'MEMENTO ZOOLANDER', 'A Touching Epistle of a Squirrel And a Explorer who must Redeem a Pastry Chef in The Sahara Desert', 2006),
(569, 'MENAGERIE RUSHMORE', 'A Unbelieveable Panorama of a Composer And a Butler who must Overcome a Database Administrator in The First Manned Space Station', 2006),
(570, 'MERMAID INSECTS', 'A Lacklusture Drama of a Waitress And a Husband who must Fight a Husband in California', 2006),
(571, 'METAL ARMAGEDDON', 'A Thrilling Display of a Lumberjack And a Crocodile who must Meet a Monkey in A Baloon Factory', 2006),
(572, 'METROPOLIS COMA', 'A Emotional Saga of a Database Administrator And a Pastry Chef who must Confront a Teacher in A Baloon Factory', 2006),
(573, 'MICROCOSMOS PARADISE', 'A Touching Character Study of a Boat And a Student who must Sink a A Shark in Nigeria', 2006),
(574, 'MIDNIGHT WESTWARD', 'A Taut Reflection of a Husband And a A Shark who must Redeem a Pastry Chef in A Monastery', 2006),
(575, 'MIDSUMMER GROUNDHOG', 'A Fateful Panorama of a Moose And a Dog who must Chase a Crocodile in Ancient Japan', 2006),
(576, 'MIGHTY LUCK', 'A Astounding Epistle of a Mad Scientist And a Pioneer who must Escape a Database Administrator in A MySQL Convention', 2006),
(577, 'MILE MULAN', 'A Lacklusture Epistle of a Cat And a Husband who must Confront a Boy in A MySQL Convention', 2006),
(578, 'MILLION ACE', 'A Brilliant Documentary of a Womanizer And a Squirrel who must Find a Technical Writer in The Sahara Desert', 2006),
(579, 'MINDS TRUMAN', 'A Taut Yarn of a Mad Scientist And a Crocodile who must Outgun a Database Administrator in A Monastery', 2006),
(580, 'MINE TITANS', 'A Amazing Yarn of a Robot And a Womanizer who must Discover a Forensic Psychologist in Berlin', 2006),
(581, 'MINORITY KISS', 'A Insightful Display of a Lumberjack And a Sumo Wrestler who must Meet a Man in The Outback', 2006),
(582, 'MIRACLE VIRTUAL', 'A Touching Epistle of a Butler And a Boy who must Find a Mad Scientist in The Sahara Desert', 2006),
(583, 'MISSION ZOOLANDER', 'A Intrepid Story of a Sumo Wrestler And a Teacher who must Meet a A Shark in An Abandoned Fun House', 2006),
(584, 'MIXED DOORS', 'A Taut Drama of a Womanizer And a Lumberjack who must Succumb a Pioneer in Ancient India', 2006),
(585, 'MOB DUFFEL', 'A Unbelieveable Documentary of a Frisbee And a Boat who must Meet a Boy in The Canadian Rockies', 2006),
(586, 'MOCKINGBIRD HOLLYWOOD', 'A Thoughtful Panorama of a Man And a Car who must Sink a Composer in Berlin', 2006),
(587, 'MOD SECRETARY', 'A Boring Documentary of a Mad Cow And a Cat who must Build a Lumberjack in New Orleans', 2006),
(588, 'MODEL FISH', 'A Beautiful Panorama of a Boat And a Crocodile who must Outrace a Dog in Australia', 2006),
(589, 'MODERN DORADO', 'A Awe-Inspiring Story of a Butler And a Sumo Wrestler who must Redeem a Boy in New Orleans', 2006),
(590, 'MONEY HAROLD', 'A Touching Tale of a Explorer And a Boat who must Defeat a Robot in Australia', 2006),
(591, 'MONSOON CAUSE', 'A Astounding Tale of a Crocodile And a Car who must Outrace a Squirrel in A U-Boat', 2006),
(592, 'MONSTER SPARTACUS', 'A Fast-Paced Story of a Waitress And a Cat who must Fight a Girl in Australia', 2006),
(593, 'MONTEREY LABYRINTH', 'A Awe-Inspiring Drama of a Monkey And a Composer who must Escape a Feminist in A U-Boat', 2006),
(594, 'MONTEZUMA COMMAND', 'A Thrilling Reflection of a Waitress And a Butler who must Battle a Butler in A Jet Boat', 2006),
(595, 'MOON BUNCH', 'A Beautiful Tale of a Astronaut And a Mad Cow who must Challenge a Cat in A Baloon Factory', 2006),
(596, 'MOONSHINE CABIN', 'A Thoughtful Display of a Astronaut And a Feminist who must Chase a Frisbee in A Jet Boat', 2006),
(597, 'MOONWALKER FOOL', 'A Epic Drama of a Feminist And a Pioneer who must Sink a Composer in New Orleans', 2006),
(598, 'MOSQUITO ARMAGEDDON', 'A Thoughtful Character Study of a Waitress And a Feminist who must Build a Teacher in Ancient Japan', 2006),
(599, 'MOTHER OLEANDER', 'A Boring Tale of a Husband And a Boy who must Fight a Squirrel in Ancient China', 2006),
(600, 'MOTIONS DETAILS', 'A Awe-Inspiring Reflection of a Dog And a Student who must Kill a Car in An Abandoned Fun House', 2006),
(601, 'MOULIN WAKE', 'A Astounding Story of a Forensic Psychologist And a Cat who must Battle a Teacher in An Abandoned Mine Shaft', 2006),
(602, 'MOURNING PURPLE', 'A Lacklusture Display of a Waitress And a Lumberjack who must Chase a Pioneer in New Orleans', 2006),
(603, 'MOVIE SHAKESPEARE', 'A Insightful Display of a Database Administrator And a Student who must Build a Hunter in Berlin', 2006),
(604, 'MULAN MOON', 'A Emotional Saga of a Womanizer And a Pioneer who must Overcome a Dentist in A Baloon', 2006),
(605, 'MULHOLLAND BEAST', 'A Awe-Inspiring Display of a Husband And a Squirrel who must Battle a Sumo Wrestler in A Jet Boat', 2006),
(606, 'MUMMY CREATURES', 'A Fateful Character Study of a Crocodile And a Monkey who must Meet a Dentist in Australia', 2006),
(607, 'MUPPET MILE', 'A Lacklusture Story of a Madman And a Teacher who must Kill a Frisbee in The Gulf of Mexico', 2006),
(608, 'MURDER ANTITRUST', 'A Brilliant Yarn of a Car And a Database Administrator who must Escape a Boy in A MySQL Convention', 2006),
(609, 'MUSCLE BRIGHT', 'A Stunning Panorama of a Sumo Wrestler And a Husband who must Redeem a Madman in Ancient India', 2006),
(610, 'MUSIC BOONDOCK', 'A Thrilling Tale of a Butler And a Astronaut who must Battle a Explorer in The First Manned Space Station', 2006),
(611, 'MUSKETEERS WAIT', 'A Touching Yarn of a Student And a Moose who must Fight a Mad Cow in Australia', 2006),
(612, 'MUSSOLINI SPOILERS', 'A Thrilling Display of a Boat And a Monkey who must Meet a Composer in Ancient China', 2006),
(613, 'MYSTIC TRUMAN', 'A Epic Yarn of a Teacher And a Hunter who must Outgun a Explorer in Soviet Georgia', 2006),
(614, 'NAME DETECTIVE', 'A Touching Saga of a Sumo Wrestler And a Cat who must Pursue a Mad Scientist in Nigeria', 2006),
(615, 'NASH CHOCOLAT', 'A Epic Reflection of a Monkey And a Mad Cow who must Kill a Forensic Psychologist in An Abandoned Mine Shaft', 2006),
(616, 'NATIONAL STORY', 'A Taut Epistle of a Mad Scientist And a Girl who must Escape a Monkey in California', 2006),
(617, 'NATURAL STOCK', 'A Fast-Paced Story of a Sumo Wrestler And a Girl who must Defeat a Car in A Baloon Factory', 2006),
(618, 'NECKLACE OUTBREAK', 'A Astounding Epistle of a Database Administrator And a Mad Scientist who must Pursue a Cat in California', 2006),
(619, 'NEIGHBORS CHARADE', 'A Fanciful Reflection of a Crocodile And a Astronaut who must Outrace a Feminist in An Abandoned Amusement Park', 2006),
(620, 'NEMO CAMPUS', 'A Lacklusture Reflection of a Monkey And a Squirrel who must Outrace a Womanizer in A Manhattan Penthouse', 2006),
(621, 'NETWORK PEAK', 'A Unbelieveable Reflection of a Butler And a Boat who must Outgun a Mad Scientist in California', 2006),
(622, 'NEWSIES STORY', 'A Action-Packed Character Study of a Dog And a Lumberjack who must Outrace a Moose in The Gulf of Mexico', 2006),
(623, 'NEWTON LABYRINTH', 'A Intrepid Character Study of a Moose And a Waitress who must Find a A Shark in Ancient India', 2006),
(624, 'NIGHTMARE CHILL', 'A Brilliant Display of a Robot And a Butler who must Fight a Waitress in An Abandoned Mine Shaft', 2006),
(625, 'NONE SPIKING', 'A Boring Reflection of a Secret Agent And a Astronaut who must Face a Composer in A Manhattan Penthouse', 2006),
(626, 'NOON PAPI', 'A Unbelieveable Character Study of a Mad Scientist And a Astronaut who must Find a Pioneer in A Manhattan Penthouse', 2006),
(627, 'NORTH TEQUILA', 'A Beautiful Character Study of a Mad Cow And a Robot who must Reach a Womanizer in New Orleans', 2006),
(628, 'NORTHWEST POLISH', 'A Boring Character Study of a Boy And a A Shark who must Outrace a Womanizer in The Outback', 2006),
(629, 'NOTORIOUS REUNION', 'A Amazing Epistle of a Woman And a Squirrel who must Fight a Hunter in A Baloon', 2006),
(630, 'NOTTING SPEAKEASY', 'A Thoughtful Display of a Butler And a Womanizer who must Find a Waitress in The Canadian Rockies', 2006),
(631, 'NOVOCAINE FLIGHT', 'A Fanciful Display of a Student And a Teacher who must Outgun a Crocodile in Nigeria', 2006),
(632, 'NUTS TIES', 'A Thoughtful Drama of a Explorer And a Womanizer who must Meet a Teacher in California', 2006),
(633, 'OCTOBER SUBMARINE', 'A Taut Epistle of a Monkey And a Boy who must Confront a Husband in A Jet Boat', 2006),
(634, 'ODDS BOOGIE', 'A Thrilling Yarn of a Feminist And a Madman who must Battle a Hunter in Berlin', 2006),
(635, 'OKLAHOMA JUMANJI', 'A Thoughtful Drama of a Dentist And a Womanizer who must Meet a Husband in The Sahara Desert', 2006),
(636, 'OLEANDER CLUE', 'A Boring Story of a Teacher And a Monkey who must Succumb a Forensic Psychologist in A Jet Boat', 2006),
(637, 'OPEN AFRICAN', 'A Lacklusture Drama of a Secret Agent And a Explorer who must Discover a Car in A U-Boat', 2006),
(638, 'OPERATION OPERATION', 'A Intrepid Character Study of a Man And a Frisbee who must Overcome a Madman in Ancient China', 2006),
(639, 'OPPOSITE NECKLACE', 'A Fateful Epistle of a Crocodile And a Moose who must Kill a Explorer in Nigeria', 2006),
(640, 'OPUS ICE', 'A Fast-Paced Drama of a Hunter And a Boy who must Discover a Feminist in The Sahara Desert', 2006),
(641, 'ORANGE GRAPES', 'A Astounding Documentary of a Butler And a Womanizer who must Face a Dog in A U-Boat', 2006),
(642, 'ORDER BETRAYED', 'A Amazing Saga of a Dog And a A Shark who must Challenge a Cat in The Sahara Desert', 2006),
(643, 'ORIENT CLOSER', 'A Astounding Epistle of a Technical Writer And a Teacher who must Fight a Squirrel in The Sahara Desert', 2006),
(644, 'OSCAR GOLD', 'A Insightful Tale of a Database Administrator And a Dog who must Face a Madman in Soviet Georgia', 2006),
(645, 'OTHERS SOUP', 'A Lacklusture Documentary of a Mad Cow And a Madman who must Sink a Moose in The Gulf of Mexico', 2006),
(646, 'OUTBREAK DIVINE', 'A Unbelieveable Yarn of a Database Administrator And a Woman who must Succumb a A Shark in A U-Boat', 2006),
(647, 'OUTFIELD MASSACRE', 'A Thoughtful Drama of a Husband And a Secret Agent who must Pursue a Database Administrator in Ancient India', 2006),
(648, 'OUTLAW HANKY', 'A Thoughtful Story of a Astronaut And a Composer who must Conquer a Dog in The Sahara Desert', 2006),
(649, 'OZ LIAISONS', 'A Epic Yarn of a Mad Scientist And a Cat who must Confront a Womanizer in A Baloon Factory', 2006),
(650, 'PACIFIC AMISTAD', 'A Thrilling Yarn of a Dog And a Moose who must Kill a Pastry Chef in A Manhattan Penthouse', 2006),
(651, 'PACKER MADIGAN', 'A Epic Display of a Sumo Wrestler And a Forensic Psychologist who must Build a Woman in An Abandoned Amusement Park', 2006),
(652, 'PAJAMA JAWBREAKER', 'A Emotional Drama of a Boy And a Technical Writer who must Redeem a Sumo Wrestler in California', 2006),
(653, 'PANIC CLUB', 'A Fanciful Display of a Teacher And a Crocodile who must Succumb a Girl in A Baloon', 2006),
(654, 'PANKY SUBMARINE', 'A Touching Documentary of a Dentist And a Sumo Wrestler who must Overcome a Boy in The Gulf of Mexico', 2006),
(655, 'PANTHER REDS', 'A Brilliant Panorama of a Moose And a Man who must Reach a Teacher in The Gulf of Mexico', 2006),
(656, 'PAPI NECKLACE', 'A Fanciful Display of a Car And a Monkey who must Escape a Squirrel in Ancient Japan', 2006),
(657, 'PARADISE SABRINA', 'A Intrepid Yarn of a Car And a Moose who must Outrace a Crocodile in A Manhattan Penthouse', 2006),
(658, 'PARIS WEEKEND', 'A Intrepid Story of a Squirrel And a Crocodile who must Defeat a Monkey in The Outback', 2006),
(659, 'PARK CITIZEN', 'A Taut Epistle of a Sumo Wrestler And a Girl who must Face a Husband in Ancient Japan', 2006),
(660, 'PARTY KNOCK', 'A Fateful Display of a Technical Writer And a Butler who must Battle a Sumo Wrestler in An Abandoned Mine Shaft', 2006),
(661, 'PAST SUICIDES', 'A Intrepid Tale of a Madman And a Astronaut who must Challenge a Hunter in A Monastery', 2006),
(662, 'PATHS CONTROL', 'A Astounding Documentary of a Butler And a Cat who must Find a Frisbee in Ancient China', 2006),
(663, 'PATIENT SISTER', 'A Emotional Epistle of a Squirrel And a Robot who must Confront a Lumberjack in Soviet Georgia', 2006),
(664, 'PATRIOT ROMAN', 'A Taut Saga of a Robot And a Database Administrator who must Challenge a Astronaut in California', 2006),
(665, 'PATTON INTERVIEW', 'A Thrilling Documentary of a Composer And a Secret Agent who must Succumb a Cat in Berlin', 2006),
(666, 'PAYCHECK WAIT', 'A Awe-Inspiring Reflection of a Boy And a Man who must Discover a Moose in The Sahara Desert', 2006),
(667, 'PEACH INNOCENT', 'A Action-Packed Drama of a Monkey And a Dentist who must Chase a Butler in Berlin', 2006),
(668, 'PEAK FOREVER', 'A Insightful Reflection of a Boat And a Secret Agent who must Vanquish a Astronaut in An Abandoned Mine Shaft', 2006),
(669, 'PEARL DESTINY', 'A Lacklusture Yarn of a Astronaut And a Pastry Chef who must Sink a Dog in A U-Boat', 2006),
(670, 'PELICAN COMFORTS', 'A Epic Documentary of a Boy And a Monkey who must Pursue a Astronaut in Berlin', 2006),
(671, 'PERDITION FARGO', 'A Fast-Paced Story of a Car And a Cat who must Outgun a Hunter in Berlin', 2006),
(672, 'PERFECT GROOVE', 'A Thrilling Yarn of a Dog And a Dog who must Build a Husband in A Baloon', 2006),
(673, 'PERSONAL LADYBUGS', 'A Epic Saga of a Hunter And a Technical Writer who must Conquer a Cat in Ancient Japan', 2006),
(674, 'PET HAUNTING', 'A Unbelieveable Reflection of a Explorer And a Boat who must Conquer a Woman in California', 2006),
(675, 'PHANTOM GLORY', 'A Beautiful Documentary of a Astronaut And a Crocodile who must Discover a Madman in A Monastery', 2006),
(676, 'PHILADELPHIA WIFE', 'A Taut Yarn of a Hunter And a Astronaut who must Conquer a Database Administrator in The Sahara Desert', 2006),
(677, 'PIANIST OUTFIELD', 'A Intrepid Story of a Boy And a Technical Writer who must Pursue a Lumberjack in A Monastery', 2006),
(678, 'PICKUP DRIVING', 'A Touching Documentary of a Husband And a Boat who must Meet a Pastry Chef in A Baloon Factory', 2006),
(679, 'PILOT HOOSIERS', 'A Awe-Inspiring Reflection of a Crocodile And a Sumo Wrestler who must Meet a Forensic Psychologist in An Abandoned Mine Shaft', 2006),
(680, 'PINOCCHIO SIMON', 'A Action-Packed Reflection of a Mad Scientist And a A Shark who must Find a Feminist in California', 2006),
(681, 'PIRATES ROXANNE', 'A Stunning Drama of a Woman And a Lumberjack who must Overcome a A Shark in The Canadian Rockies', 2006),
(682, 'PITTSBURGH HUNCHBACK', 'A Thrilling Epistle of a Boy And a Boat who must Find a Student in Soviet Georgia', 2006),
(683, 'PITY BOUND', 'A Boring Panorama of a Feminist And a Moose who must Defeat a Database Administrator in Nigeria', 2006),
(684, 'PIZZA JUMANJI', 'A Epic Saga of a Cat And a Squirrel who must Outgun a Robot in A U-Boat', 2006),
(685, 'PLATOON INSTINCT', 'A Thrilling Panorama of a Man And a Woman who must Reach a Woman in Australia', 2006),
(686, 'PLUTO OLEANDER', 'A Action-Packed Reflection of a Car And a Moose who must Outgun a Car in A Shark Tank', 2006),
(687, 'POCUS PULP', 'A Intrepid Yarn of a Frisbee And a Dog who must Build a Astronaut in A Baloon Factory', 2006),
(688, 'POLISH BROOKLYN', 'A Boring Character Study of a Database Administrator And a Lumberjack who must Reach a Madman in The Outback', 2006),
(689, 'POLLOCK DELIVERANCE', 'A Intrepid Story of a Madman And a Frisbee who must Outgun a Boat in The Sahara Desert', 2006),
(690, 'POND SEATTLE', 'A Stunning Drama of a Teacher And a Boat who must Battle a Feminist in Ancient China', 2006),
(691, 'POSEIDON FOREVER', 'A Thoughtful Epistle of a Womanizer And a Monkey who must Vanquish a Dentist in A Monastery', 2006),
(692, 'POTLUCK MIXED', 'A Beautiful Story of a Dog And a Technical Writer who must Outgun a Student in A Baloon', 2006),
(693, 'POTTER CONNECTICUT', 'A Thrilling Epistle of a Frisbee And a Cat who must Fight a Technical Writer in Berlin', 2006),
(694, 'PREJUDICE OLEANDER', 'A Epic Saga of a Boy And a Dentist who must Outrace a Madman in A U-Boat', 2006),
(695, 'PRESIDENT BANG', 'A Fateful Panorama of a Technical Writer And a Moose who must Battle a Robot in Soviet Georgia', 2006),
(696, 'PRIDE ALAMO', 'A Thoughtful Drama of a A Shark And a Forensic Psychologist who must Vanquish a Student in Ancient India', 2006),
(697, 'PRIMARY GLASS', 'A Fateful Documentary of a Pastry Chef And a Butler who must Build a Dog in The Canadian Rockies', 2006),
(698, 'PRINCESS GIANT', 'A Thrilling Yarn of a Pastry Chef And a Monkey who must Battle a Monkey in A Shark Tank', 2006),
(699, 'PRIVATE DROP', 'A Stunning Story of a Technical Writer And a Hunter who must Succumb a Secret Agent in A Baloon', 2006),
(700, 'PRIX UNDEFEATED', 'A Stunning Saga of a Mad Scientist And a Boat who must Overcome a Dentist in Ancient China', 2006),
(701, 'PSYCHO SHRUNK', 'A Amazing Panorama of a Crocodile And a Explorer who must Fight a Husband in Nigeria', 2006),
(702, 'PULP BEVERLY', 'A Unbelieveable Display of a Dog And a Crocodile who must Outrace a Man in Nigeria', 2006),
(703, 'PUNK DIVORCE', 'A Fast-Paced Tale of a Pastry Chef And a Boat who must Face a Frisbee in The Canadian Rockies', 2006),
(704, 'PURE RUNNER', 'A Thoughtful Documentary of a Student And a Madman who must Challenge a Squirrel in A Manhattan Penthouse', 2006),
(705, 'PURPLE MOVIE', 'A Boring Display of a Pastry Chef And a Sumo Wrestler who must Discover a Frisbee in An Abandoned Amusement Park', 2006),
(706, 'QUEEN LUKE', 'A Astounding Story of a Girl And a Boy who must Challenge a Composer in New Orleans', 2006),
(707, 'QUEST MUSSOLINI', 'A Fateful Drama of a Husband And a Sumo Wrestler who must Battle a Pastry Chef in A Baloon Factory', 2006),
(708, 'QUILLS BULL', 'A Thoughtful Story of a Pioneer And a Woman who must Reach a Moose in Australia', 2006),
(709, 'RACER EGG', 'A Emotional Display of a Monkey And a Waitress who must Reach a Secret Agent in California', 2006),
(710, 'RAGE GAMES', 'A Fast-Paced Saga of a Astronaut And a Secret Agent who must Escape a Hunter in An Abandoned Amusement Park', 2006),
(711, 'RAGING AIRPLANE', 'A Astounding Display of a Secret Agent And a Technical Writer who must Escape a Mad Scientist in A Jet Boat', 2006),
(712, 'RAIDERS ANTITRUST', 'A Amazing Drama of a Teacher And a Feminist who must Meet a Woman in The First Manned Space Station', 2006),
(713, 'RAINBOW SHOCK', 'A Action-Packed Story of a Hunter And a Boy who must Discover a Lumberjack in Ancient India', 2006),
(714, 'RANDOM GO', 'A Fateful Drama of a Frisbee And a Student who must Confront a Cat in A Shark Tank', 2006),
(715, 'RANGE MOONWALKER', 'A Insightful Documentary of a Hunter And a Dentist who must Confront a Crocodile in A Baloon', 2006),
(716, 'REAP UNFAITHFUL', 'A Thrilling Epistle of a Composer And a Sumo Wrestler who must Challenge a Mad Cow in A MySQL Convention', 2006),
(717, 'REAR TRADING', 'A Awe-Inspiring Reflection of a Forensic Psychologist And a Secret Agent who must Succumb a Pastry Chef in Soviet Georgia', 2006),
(718, 'REBEL AIRPORT', 'A Intrepid Yarn of a Database Administrator And a Boat who must Outrace a Husband in Ancient India', 2006),
(719, 'RECORDS ZORRO', 'A Amazing Drama of a Mad Scientist And a Composer who must Build a Husband in The Outback', 2006),
(720, 'REDEMPTION COMFORTS', 'A Emotional Documentary of a Dentist And a Woman who must Battle a Mad Scientist in Ancient China', 2006),
(721, 'REDS POCUS', 'A Lacklusture Yarn of a Sumo Wrestler And a Squirrel who must Redeem a Monkey in Soviet Georgia', 2006),
(722, 'REEF SALUTE', 'A Action-Packed Saga of a Teacher And a Lumberjack who must Battle a Dentist in A Baloon', 2006),
(723, 'REIGN GENTLEMEN', 'A Emotional Yarn of a Composer And a Man who must Escape a Butler in The Gulf of Mexico', 2006),
(724, 'REMEMBER DIARY', 'A Insightful Tale of a Technical Writer And a Waitress who must Conquer a Monkey in Ancient India', 2006),
(725, 'REQUIEM TYCOON', 'A Unbelieveable Character Study of a Cat And a Database Administrator who must Pursue a Teacher in A Monastery', 2006),
(726, 'RESERVOIR ADAPTATION', 'A Intrepid Drama of a Teacher And a Moose who must Kill a Car in California', 2006),
(727, 'RESURRECTION SILVERADO', 'A Epic Yarn of a Robot And a Explorer who must Challenge a Girl in A MySQL Convention', 2006),
(728, 'REUNION WITCHES', 'A Unbelieveable Documentary of a Database Administrator And a Frisbee who must Redeem a Mad Scientist in A Baloon Factory', 2006),
(729, 'RIDER CADDYSHACK', 'A Taut Reflection of a Monkey And a Womanizer who must Chase a Moose in Nigeria', 2006),
(730, 'RIDGEMONT SUBMARINE', 'A Unbelieveable Drama of a Waitress And a Composer who must Sink a Mad Cow in Ancient Japan', 2006),
(731, 'RIGHT CRANES', 'A Fateful Character Study of a Boat And a Cat who must Find a Database Administrator in A Jet Boat', 2006),
(732, 'RINGS HEARTBREAKERS', 'A Amazing Yarn of a Sumo Wrestler And a Boat who must Conquer a Waitress in New Orleans', 2006),
(733, 'RIVER OUTLAW', 'A Thrilling Character Study of a Squirrel And a Lumberjack who must Face a Hunter in A MySQL Convention', 2006),
(734, 'ROAD ROXANNE', 'A Boring Character Study of a Waitress And a Astronaut who must Fight a Crocodile in Ancient Japan', 2006),
(735, 'ROBBERS JOON', 'A Thoughtful Story of a Mad Scientist And a Waitress who must Confront a Forensic Psychologist in Soviet Georgia', 2006),
(736, 'ROBBERY BRIGHT', 'A Taut Reflection of a Robot And a Squirrel who must Fight a Boat in Ancient Japan', 2006),
(737, 'ROCK INSTINCT', 'A Astounding Character Study of a Robot And a Moose who must Overcome a Astronaut in Ancient India', 2006),
(738, 'ROCKETEER MOTHER', 'A Awe-Inspiring Character Study of a Robot And a Sumo Wrestler who must Discover a Womanizer in A Shark Tank', 2006),
(739, 'ROCKY WAR', 'A Fast-Paced Display of a Squirrel And a Explorer who must Outgun a Mad Scientist in Nigeria', 2006),
(740, 'ROLLERCOASTER BRINGING', 'A Beautiful Drama of a Robot And a Lumberjack who must Discover a Technical Writer in A Shark Tank', 2006),
(741, 'ROMAN PUNK', 'A Thoughtful Panorama of a Mad Cow And a Student who must Battle a Forensic Psychologist in Berlin', 2006),
(742, 'ROOF CHAMPION', 'A Lacklusture Reflection of a Car And a Explorer who must Find a Monkey in A Baloon', 2006),
(743, 'ROOM ROMAN', 'A Awe-Inspiring Panorama of a Composer And a Secret Agent who must Sink a Composer in A Shark Tank', 2006),
(744, 'ROOTS REMEMBER', 'A Brilliant Drama of a Mad Cow And a Hunter who must Escape a Hunter in Berlin', 2006),
(745, 'ROSES TREASURE', 'A Astounding Panorama of a Monkey And a Secret Agent who must Defeat a Woman in The First Manned Space Station', 2006),
(746, 'ROUGE SQUAD', 'A Awe-Inspiring Drama of a Astronaut And a Frisbee who must Conquer a Mad Scientist in Australia', 2006),
(747, 'ROXANNE REBEL', 'A Astounding Story of a Pastry Chef And a Database Administrator who must Fight a Man in The Outback', 2006),
(748, 'RUGRATS SHAKESPEARE', 'A Touching Saga of a Crocodile And a Crocodile who must Discover a Technical Writer in Nigeria', 2006),
(749, 'RULES HUMAN', 'A Beautiful Epistle of a Astronaut And a Student who must Confront a Monkey in An Abandoned Fun House', 2006),
(750, 'RUN PACIFIC', 'A Touching Tale of a Cat And a Pastry Chef who must Conquer a Pastry Chef in A MySQL Convention', 2006),
(751, 'RUNAWAY TENENBAUMS', 'A Thoughtful Documentary of a Boat And a Man who must Meet a Boat in An Abandoned Fun House', 2006),
(752, 'RUNNER MADIGAN', 'A Thoughtful Documentary of a Crocodile And a Robot who must Outrace a Womanizer in The Outback', 2006),
(753, 'RUSH GOODFELLAS', 'A Emotional Display of a Man And a Dentist who must Challenge a Squirrel in Australia', 2006),
(754, 'RUSHMORE MERMAID', 'A Boring Story of a Woman And a Moose who must Reach a Husband in A Shark Tank', 2006),
(755, 'SABRINA MIDNIGHT', 'A Emotional Story of a Squirrel And a Crocodile who must Succumb a Husband in The Sahara Desert', 2006),
(756, 'SADDLE ANTITRUST', 'A Stunning Epistle of a Feminist And a A Shark who must Battle a Woman in An Abandoned Fun House', 2006),
(757, 'SAGEBRUSH CLUELESS', 'A Insightful Story of a Lumberjack And a Hunter who must Kill a Boy in Ancient Japan', 2006),
(758, 'SAINTS BRIDE', 'A Fateful Tale of a Technical Writer And a Composer who must Pursue a Explorer in The Gulf of Mexico', 2006),
(759, 'SALUTE APOLLO', 'A Awe-Inspiring Character Study of a Boy And a Feminist who must Sink a Crocodile in Ancient China', 2006),
(760, 'SAMURAI LION', 'A Fast-Paced Story of a Pioneer And a Astronaut who must Reach a Boat in A Baloon', 2006),
(761, 'SANTA PARIS', 'A Emotional Documentary of a Moose And a Car who must Redeem a Mad Cow in A Baloon Factory', 2006),
(762, 'SASSY PACKER', 'A Fast-Paced Documentary of a Dog And a Teacher who must Find a Moose in A Manhattan Penthouse', 2006),
(763, 'SATISFACTION CONFIDENTIAL', 'A Lacklusture Yarn of a Dentist And a Butler who must Meet a Secret Agent in Ancient China', 2006),
(764, 'SATURDAY LAMBS', 'A Thoughtful Reflection of a Mad Scientist And a Moose who must Kill a Husband in A Baloon', 2006),
(765, 'SATURN NAME', 'A Fateful Epistle of a Butler And a Boy who must Redeem a Teacher in Berlin', 2006),
(766, 'SAVANNAH TOWN', 'A Awe-Inspiring Tale of a Astronaut And a Database Administrator who must Chase a Secret Agent in The Gulf of Mexico', 2006),
(767, 'SCALAWAG DUCK', 'A Fateful Reflection of a Car And a Teacher who must Confront a Waitress in A Monastery', 2006),
(768, 'SCARFACE BANG', 'A Emotional Yarn of a Teacher And a Girl who must Find a Teacher in A Baloon Factory', 2006),
(769, 'SCHOOL JACKET', 'A Intrepid Yarn of a Monkey And a Boy who must Fight a Composer in A Manhattan Penthouse', 2006),
(770, 'SCISSORHANDS SLUMS', 'A Awe-Inspiring Drama of a Girl And a Technical Writer who must Meet a Feminist in The Canadian Rockies', 2006),
(771, 'SCORPION APOLLO', 'A Awe-Inspiring Documentary of a Technical Writer And a Husband who must Meet a Monkey in An Abandoned Fun House', 2006),
(772, 'SEA VIRGIN', 'A Fast-Paced Documentary of a Technical Writer And a Pastry Chef who must Escape a Moose in A U-Boat', 2006),
(773, 'SEABISCUIT PUNK', 'A Insightful Saga of a Man And a Forensic Psychologist who must Discover a Mad Cow in A MySQL Convention', 2006),
(774, 'SEARCHERS WAIT', 'A Fast-Paced Tale of a Car And a Mad Scientist who must Kill a Womanizer in Ancient Japan', 2006),
(775, 'SEATTLE EXPECATIONS', 'A Insightful Reflection of a Crocodile And a Sumo Wrestler who must Meet a Technical Writer in The Sahara Desert', 2006),
(776, 'SECRET GROUNDHOG', 'A Astounding Story of a Cat And a Database Administrator who must Build a Technical Writer in New Orleans', 2006),
(777, 'SECRETARY ROUGE', 'A Action-Packed Panorama of a Mad Cow And a Composer who must Discover a Robot in A Baloon Factory', 2006),
(778, 'SECRETS PARADISE', 'A Fateful Saga of a Cat And a Frisbee who must Kill a Girl in A Manhattan Penthouse', 2006),
(779, 'SENSE GREEK', 'A Taut Saga of a Lumberjack And a Pastry Chef who must Escape a Sumo Wrestler in An Abandoned Fun House', 2006),
(780, 'SENSIBILITY REAR', 'A Emotional Tale of a Robot And a Sumo Wrestler who must Redeem a Pastry Chef in A Baloon Factory', 2006),
(781, 'SEVEN SWARM', 'A Unbelieveable Character Study of a Dog And a Mad Cow who must Kill a Monkey in Berlin', 2006),
(782, 'SHAKESPEARE SADDLE', 'A Fast-Paced Panorama of a Lumberjack And a Database Administrator who must Defeat a Madman in A MySQL Convention', 2006),
(783, 'SHANE DARKNESS', 'A Action-Packed Saga of a Moose And a Lumberjack who must Find a Woman in Berlin', 2006),
(784, 'SHANGHAI TYCOON', 'A Fast-Paced Character Study of a Crocodile And a Lumberjack who must Build a Husband in An Abandoned Fun House', 2006),
(785, 'SHAWSHANK BUBBLE', 'A Lacklusture Story of a Moose And a Monkey who must Confront a Butler in An Abandoned Amusement Park', 2006),
(786, 'SHEPHERD MIDSUMMER', 'A Thoughtful Drama of a Robot And a Womanizer who must Kill a Lumberjack in A Baloon', 2006);
INSERT INTO `film` (`film_id`, `title`, `description`, `release_year`) VALUES
(787, 'SHINING ROSES', 'A Awe-Inspiring Character Study of a Astronaut And a Forensic Psychologist who must Challenge a Madman in Ancient India', 2006),
(788, 'SHIP WONDERLAND', 'A Thrilling Saga of a Monkey And a Frisbee who must Escape a Explorer in The Outback', 2006),
(789, 'SHOCK CABIN', 'A Fateful Tale of a Mad Cow And a Crocodile who must Meet a Husband in New Orleans', 2006),
(790, 'SHOOTIST SUPERFLY', 'A Fast-Paced Story of a Crocodile And a A Shark who must Sink a Pioneer in Berlin', 2006),
(791, 'SHOW LORD', 'A Fanciful Saga of a Student And a Girl who must Find a Butler in Ancient Japan', 2006),
(792, 'SHREK LICENSE', 'A Fateful Yarn of a Secret Agent And a Feminist who must Find a Feminist in A Jet Boat', 2006),
(793, 'SHRUNK DIVINE', 'A Fateful Character Study of a Waitress And a Technical Writer who must Battle a Hunter in A Baloon', 2006),
(794, 'SIDE ARK', 'A Stunning Panorama of a Crocodile And a Womanizer who must Meet a Feminist in The Canadian Rockies', 2006),
(795, 'SIEGE MADRE', 'A Boring Tale of a Frisbee And a Crocodile who must Vanquish a Moose in An Abandoned Mine Shaft', 2006),
(796, 'SIERRA DIVIDE', 'A Emotional Character Study of a Frisbee And a Mad Scientist who must Build a Madman in California', 2006),
(797, 'SILENCE KANE', 'A Emotional Drama of a Sumo Wrestler And a Dentist who must Confront a Sumo Wrestler in A Baloon', 2006),
(798, 'SILVERADO GOLDFINGER', 'A Stunning Epistle of a Sumo Wrestler And a Man who must Challenge a Waitress in Ancient India', 2006),
(799, 'SIMON NORTH', 'A Thrilling Documentary of a Technical Writer And a A Shark who must Face a Pioneer in A Shark Tank', 2006),
(800, 'SINNERS ATLANTIS', 'A Epic Display of a Dog And a Boat who must Succumb a Mad Scientist in An Abandoned Mine Shaft', 2006),
(801, 'SISTER FREDDY', 'A Stunning Saga of a Butler And a Woman who must Pursue a Explorer in Australia', 2006),
(802, 'SKY MIRACLE', 'A Epic Drama of a Mad Scientist And a Explorer who must Succumb a Waitress in An Abandoned Fun House', 2006),
(803, 'SLACKER LIAISONS', 'A Fast-Paced Tale of a A Shark And a Student who must Meet a Crocodile in Ancient China', 2006),
(804, 'SLEEPING SUSPECTS', 'A Stunning Reflection of a Sumo Wrestler And a Explorer who must Sink a Frisbee in A MySQL Convention', 2006),
(805, 'SLEEPLESS MONSOON', 'A Amazing Saga of a Moose And a Pastry Chef who must Escape a Butler in Australia', 2006),
(806, 'SLEEPY JAPANESE', 'A Emotional Epistle of a Moose And a Composer who must Fight a Technical Writer in The Outback', 2006),
(807, 'SLEUTH ORIENT', 'A Fateful Character Study of a Husband And a Dog who must Find a Feminist in Ancient India', 2006),
(808, 'SLING LUKE', 'A Intrepid Character Study of a Robot And a Monkey who must Reach a Secret Agent in An Abandoned Amusement Park', 2006),
(809, 'SLIPPER FIDELITY', 'A Taut Reflection of a Secret Agent And a Man who must Redeem a Explorer in A MySQL Convention', 2006),
(810, 'SLUMS DUCK', 'A Amazing Character Study of a Teacher And a Database Administrator who must Defeat a Waitress in A Jet Boat', 2006),
(811, 'SMILE EARRING', 'A Intrepid Drama of a Teacher And a Butler who must Build a Pastry Chef in Berlin', 2006),
(812, 'SMOKING BARBARELLA', 'A Lacklusture Saga of a Mad Cow And a Mad Scientist who must Sink a Cat in A MySQL Convention', 2006),
(813, 'SMOOCHY CONTROL', 'A Thrilling Documentary of a Husband And a Feminist who must Face a Mad Scientist in Ancient China', 2006),
(814, 'SNATCH SLIPPER', 'A Insightful Panorama of a Woman And a Feminist who must Defeat a Forensic Psychologist in Berlin', 2006),
(815, 'SNATCHERS MONTEZUMA', 'A Boring Epistle of a Sumo Wrestler And a Woman who must Escape a Man in The Canadian Rockies', 2006),
(816, 'SNOWMAN ROLLERCOASTER', 'A Fateful Display of a Lumberjack And a Girl who must Succumb a Mad Cow in A Manhattan Penthouse', 2006),
(817, 'SOLDIERS EVOLUTION', 'A Lacklusture Panorama of a A Shark And a Pioneer who must Confront a Student in The First Manned Space Station', 2006),
(818, 'SOMETHING DUCK', 'A Boring Character Study of a Car And a Husband who must Outgun a Frisbee in The First Manned Space Station', 2006),
(819, 'SONG HEDWIG', 'A Amazing Documentary of a Man And a Husband who must Confront a Squirrel in A MySQL Convention', 2006),
(820, 'SONS INTERVIEW', 'A Taut Character Study of a Explorer And a Mad Cow who must Battle a Hunter in Ancient China', 2006),
(821, 'SORORITY QUEEN', 'A Fast-Paced Display of a Squirrel And a Composer who must Fight a Forensic Psychologist in A Jet Boat', 2006),
(822, 'SOUP WISDOM', 'A Fast-Paced Display of a Robot And a Butler who must Defeat a Butler in A MySQL Convention', 2006),
(823, 'SOUTH WAIT', 'A Amazing Documentary of a Car And a Robot who must Escape a Lumberjack in An Abandoned Amusement Park', 2006),
(824, 'SPARTACUS CHEAPER', 'A Thrilling Panorama of a Pastry Chef And a Secret Agent who must Overcome a Student in A Manhattan Penthouse', 2006),
(825, 'SPEAKEASY DATE', 'A Lacklusture Drama of a Forensic Psychologist And a Car who must Redeem a Man in A Manhattan Penthouse', 2006),
(826, 'SPEED SUIT', 'A Brilliant Display of a Frisbee And a Mad Scientist who must Succumb a Robot in Ancient China', 2006),
(827, 'SPICE SORORITY', 'A Fateful Display of a Pioneer And a Hunter who must Defeat a Husband in An Abandoned Mine Shaft', 2006),
(828, 'SPIKING ELEMENT', 'A Lacklusture Epistle of a Dentist And a Technical Writer who must Find a Dog in A Monastery', 2006),
(829, 'SPINAL ROCKY', 'A Lacklusture Epistle of a Sumo Wrestler And a Squirrel who must Defeat a Explorer in California', 2006),
(830, 'SPIRIT FLINTSTONES', 'A Brilliant Yarn of a Cat And a Car who must Confront a Explorer in Ancient Japan', 2006),
(831, 'SPIRITED CASUALTIES', 'A Taut Story of a Waitress And a Man who must Face a Car in A Baloon Factory', 2006),
(832, 'SPLASH GUMP', 'A Taut Saga of a Crocodile And a Boat who must Conquer a Hunter in A Shark Tank', 2006),
(833, 'SPLENDOR PATTON', 'A Taut Story of a Dog And a Explorer who must Find a Astronaut in Berlin', 2006),
(834, 'SPOILERS HELLFIGHTERS', 'A Fanciful Story of a Technical Writer And a Squirrel who must Defeat a Dog in The Gulf of Mexico', 2006),
(835, 'SPY MILE', 'A Thrilling Documentary of a Feminist And a Feminist who must Confront a Feminist in A Baloon', 2006),
(836, 'SQUAD FISH', 'A Fast-Paced Display of a Pastry Chef And a Dog who must Kill a Teacher in Berlin', 2006),
(837, 'STAGE WORLD', 'A Lacklusture Panorama of a Woman And a Frisbee who must Chase a Crocodile in A Jet Boat', 2006),
(838, 'STAGECOACH ARMAGEDDON', 'A Touching Display of a Pioneer And a Butler who must Chase a Car in California', 2006),
(839, 'STALLION SUNDANCE', 'A Fast-Paced Tale of a Car And a Dog who must Outgun a A Shark in Australia', 2006),
(840, 'STAMPEDE DISTURBING', 'A Unbelieveable Tale of a Woman And a Lumberjack who must Fight a Frisbee in A U-Boat', 2006),
(841, 'STAR OPERATION', 'A Insightful Character Study of a Girl And a Car who must Pursue a Mad Cow in A Shark Tank', 2006),
(842, 'STATE WASTELAND', 'A Beautiful Display of a Cat And a Pastry Chef who must Outrace a Mad Cow in A Jet Boat', 2006),
(843, 'STEEL SANTA', 'A Fast-Paced Yarn of a Composer And a Frisbee who must Face a Moose in Nigeria', 2006),
(844, 'STEERS ARMAGEDDON', 'A Stunning Character Study of a Car And a Girl who must Succumb a Car in A MySQL Convention', 2006),
(845, 'STEPMOM DREAM', 'A Touching Epistle of a Crocodile And a Teacher who must Build a Forensic Psychologist in A MySQL Convention', 2006),
(846, 'STING PERSONAL', 'A Fanciful Drama of a Frisbee And a Dog who must Fight a Madman in A Jet Boat', 2006),
(847, 'STOCK GLASS', 'A Boring Epistle of a Crocodile And a Lumberjack who must Outgun a Moose in Ancient China', 2006),
(848, 'STONE FIRE', 'A Intrepid Drama of a Astronaut And a Crocodile who must Find a Boat in Soviet Georgia', 2006),
(849, 'STORM HAPPINESS', 'A Insightful Drama of a Feminist And a A Shark who must Vanquish a Boat in A Shark Tank', 2006),
(850, 'STORY SIDE', 'A Lacklusture Saga of a Boy And a Cat who must Sink a Dentist in An Abandoned Mine Shaft', 2006),
(851, 'STRAIGHT HOURS', 'A Boring Panorama of a Secret Agent And a Girl who must Sink a Waitress in The Outback', 2006),
(852, 'STRANGELOVE DESIRE', 'A Awe-Inspiring Panorama of a Lumberjack And a Waitress who must Defeat a Crocodile in An Abandoned Amusement Park', 2006),
(853, 'STRANGER STRANGERS', 'A Awe-Inspiring Yarn of a Womanizer And a Explorer who must Fight a Woman in The First Manned Space Station', 2006),
(854, 'STRANGERS GRAFFITI', 'A Brilliant Character Study of a Secret Agent And a Man who must Find a Cat in The Gulf of Mexico', 2006),
(855, 'STREAK RIDGEMONT', 'A Astounding Character Study of a Hunter And a Waitress who must Sink a Man in New Orleans', 2006),
(856, 'STREETCAR INTENTIONS', 'A Insightful Character Study of a Waitress And a Crocodile who must Sink a Waitress in The Gulf of Mexico', 2006),
(857, 'STRICTLY SCARFACE', 'A Touching Reflection of a Crocodile And a Dog who must Chase a Hunter in An Abandoned Fun House', 2006),
(858, 'SUBMARINE BED', 'A Amazing Display of a Car And a Monkey who must Fight a Teacher in Soviet Georgia', 2006),
(859, 'SUGAR WONKA', 'A Touching Story of a Dentist And a Database Administrator who must Conquer a Astronaut in An Abandoned Amusement Park', 2006),
(860, 'SUICIDES SILENCE', 'A Emotional Character Study of a Car And a Girl who must Face a Composer in A U-Boat', 2006),
(861, 'SUIT WALLS', 'A Touching Panorama of a Lumberjack And a Frisbee who must Build a Dog in Australia', 2006),
(862, 'SUMMER SCARFACE', 'A Emotional Panorama of a Lumberjack And a Hunter who must Meet a Girl in A Shark Tank', 2006),
(863, 'SUN CONFESSIONS', 'A Beautiful Display of a Mad Cow And a Dog who must Redeem a Waitress in An Abandoned Amusement Park', 2006),
(864, 'SUNDANCE INVASION', 'A Epic Drama of a Lumberjack And a Explorer who must Confront a Hunter in A Baloon Factory', 2006),
(865, 'SUNRISE LEAGUE', 'A Beautiful Epistle of a Madman And a Butler who must Face a Crocodile in A Manhattan Penthouse', 2006),
(866, 'SUNSET RACER', 'A Awe-Inspiring Reflection of a Astronaut And a A Shark who must Defeat a Forensic Psychologist in California', 2006),
(867, 'SUPER WYOMING', 'A Action-Packed Saga of a Pastry Chef And a Explorer who must Discover a A Shark in The Outback', 2006),
(868, 'SUPERFLY TRIP', 'A Beautiful Saga of a Lumberjack And a Teacher who must Build a Technical Writer in An Abandoned Fun House', 2006),
(869, 'SUSPECTS QUILLS', 'A Emotional Epistle of a Pioneer And a Crocodile who must Battle a Man in A Manhattan Penthouse', 2006),
(870, 'SWARM GOLD', 'A Insightful Panorama of a Crocodile And a Boat who must Conquer a Sumo Wrestler in A MySQL Convention', 2006),
(871, 'SWEDEN SHINING', 'A Taut Documentary of a Car And a Robot who must Conquer a Boy in The Canadian Rockies', 2006),
(872, 'SWEET BROTHERHOOD', 'A Unbelieveable Epistle of a Sumo Wrestler And a Hunter who must Chase a Forensic Psychologist in A Baloon', 2006),
(873, 'SWEETHEARTS SUSPECTS', 'A Brilliant Character Study of a Frisbee And a Sumo Wrestler who must Confront a Woman in The Gulf of Mexico', 2006),
(874, 'TADPOLE PARK', 'A Beautiful Tale of a Frisbee And a Moose who must Vanquish a Dog in An Abandoned Amusement Park', 2006),
(875, 'TALENTED HOMICIDE', 'A Lacklusture Panorama of a Dentist And a Forensic Psychologist who must Outrace a Pioneer in A U-Boat', 2006),
(876, 'TARZAN VIDEOTAPE', 'A Fast-Paced Display of a Lumberjack And a Mad Scientist who must Succumb a Sumo Wrestler in The Sahara Desert', 2006),
(877, 'TAXI KICK', 'A Amazing Epistle of a Girl And a Woman who must Outrace a Waitress in Soviet Georgia', 2006),
(878, 'TEEN APOLLO', 'A Awe-Inspiring Drama of a Dog And a Man who must Escape a Robot in A Shark Tank', 2006),
(879, 'TELEGRAPH VOYAGE', 'A Fateful Yarn of a Husband And a Dog who must Battle a Waitress in A Jet Boat', 2006),
(880, 'TELEMARK HEARTBREAKERS', 'A Action-Packed Panorama of a Technical Writer And a Man who must Build a Forensic Psychologist in A Manhattan Penthouse', 2006),
(881, 'TEMPLE ATTRACTION', 'A Action-Packed Saga of a Forensic Psychologist And a Woman who must Battle a Womanizer in Soviet Georgia', 2006),
(882, 'TENENBAUMS COMMAND', 'A Taut Display of a Pioneer And a Man who must Reach a Girl in The Gulf of Mexico', 2006),
(883, 'TEQUILA PAST', 'A Action-Packed Panorama of a Mad Scientist And a Robot who must Challenge a Student in Nigeria', 2006),
(884, 'TERMINATOR CLUB', 'A Touching Story of a Crocodile And a Girl who must Sink a Man in The Gulf of Mexico', 2006),
(885, 'TEXAS WATCH', 'A Awe-Inspiring Yarn of a Student And a Teacher who must Fight a Teacher in An Abandoned Amusement Park', 2006),
(886, 'THEORY MERMAID', 'A Fateful Yarn of a Composer And a Monkey who must Vanquish a Womanizer in The First Manned Space Station', 2006),
(887, 'THIEF PELICAN', 'A Touching Documentary of a Madman And a Mad Scientist who must Outrace a Feminist in An Abandoned Mine Shaft', 2006),
(888, 'THIN SAGEBRUSH', 'A Emotional Drama of a Husband And a Lumberjack who must Build a Cat in Ancient India', 2006),
(889, 'TIES HUNGER', 'A Insightful Saga of a Astronaut And a Explorer who must Pursue a Mad Scientist in A U-Boat', 2006),
(890, 'TIGHTS DAWN', 'A Thrilling Epistle of a Boat And a Secret Agent who must Face a Boy in A Baloon', 2006),
(891, 'TIMBERLAND SKY', 'A Boring Display of a Man And a Dog who must Redeem a Girl in A U-Boat', 2006),
(892, 'TITANIC BOONDOCK', 'A Brilliant Reflection of a Feminist And a Dog who must Fight a Boy in A Baloon Factory', 2006),
(893, 'TITANS JERK', 'A Unbelieveable Panorama of a Feminist And a Sumo Wrestler who must Challenge a Technical Writer in Ancient China', 2006),
(894, 'TOMATOES HELLFIGHTERS', 'A Thoughtful Epistle of a Madman And a Astronaut who must Overcome a Monkey in A Shark Tank', 2006),
(895, 'TOMORROW HUSTLER', 'A Thoughtful Story of a Moose And a Husband who must Face a Secret Agent in The Sahara Desert', 2006),
(896, 'TOOTSIE PILOT', 'A Awe-Inspiring Documentary of a Womanizer And a Pastry Chef who must Kill a Lumberjack in Berlin', 2006),
(897, 'TORQUE BOUND', 'A Emotional Display of a Crocodile And a Husband who must Reach a Man in Ancient Japan', 2006),
(898, 'TOURIST PELICAN', 'A Boring Story of a Butler And a Astronaut who must Outrace a Pioneer in Australia', 2006),
(899, 'TOWERS HURRICANE', 'A Fateful Display of a Monkey And a Car who must Sink a Husband in A MySQL Convention', 2006),
(900, 'TOWN ARK', 'A Awe-Inspiring Documentary of a Moose And a Madman who must Meet a Dog in An Abandoned Mine Shaft', 2006),
(901, 'TRACY CIDER', 'A Touching Reflection of a Database Administrator And a Madman who must Build a Lumberjack in Nigeria', 2006),
(902, 'TRADING PINOCCHIO', 'A Emotional Character Study of a Student And a Explorer who must Discover a Frisbee in The First Manned Space Station', 2006),
(903, 'TRAFFIC HOBBIT', 'A Amazing Epistle of a Squirrel And a Lumberjack who must Succumb a Database Administrator in A U-Boat', 2006),
(904, 'TRAIN BUNCH', 'A Thrilling Character Study of a Robot And a Squirrel who must Face a Dog in Ancient India', 2006),
(905, 'TRAINSPOTTING STRANGERS', 'A Fast-Paced Drama of a Pioneer And a Mad Cow who must Challenge a Madman in Ancient Japan', 2006),
(906, 'TRAMP OTHERS', 'A Brilliant Display of a Composer And a Cat who must Succumb a A Shark in Ancient India', 2006),
(907, 'TRANSLATION SUMMER', 'A Touching Reflection of a Man And a Monkey who must Pursue a Womanizer in A MySQL Convention', 2006),
(908, 'TRAP GUYS', 'A Unbelieveable Story of a Boy And a Mad Cow who must Challenge a Database Administrator in The Sahara Desert', 2006),
(909, 'TREASURE COMMAND', 'A Emotional Saga of a Car And a Madman who must Discover a Pioneer in California', 2006),
(910, 'TREATMENT JEKYLL', 'A Boring Story of a Teacher And a Student who must Outgun a Cat in An Abandoned Mine Shaft', 2006),
(911, 'TRIP NEWTON', 'A Fanciful Character Study of a Lumberjack And a Car who must Discover a Cat in An Abandoned Amusement Park', 2006),
(912, 'TROJAN TOMORROW', 'A Astounding Panorama of a Husband And a Sumo Wrestler who must Pursue a Boat in Ancient India', 2006),
(913, 'TROOPERS METAL', 'A Fanciful Drama of a Monkey And a Feminist who must Sink a Man in Berlin', 2006),
(914, 'TROUBLE DATE', 'A Lacklusture Panorama of a Forensic Psychologist And a Woman who must Kill a Explorer in Ancient Japan', 2006),
(915, 'TRUMAN CRAZY', 'A Thrilling Epistle of a Moose And a Boy who must Meet a Database Administrator in A Monastery', 2006),
(916, 'TURN STAR', 'A Stunning Tale of a Man And a Monkey who must Chase a Student in New Orleans', 2006),
(917, 'TUXEDO MILE', 'A Boring Drama of a Man And a Forensic Psychologist who must Face a Frisbee in Ancient India', 2006),
(918, 'TWISTED PIRATES', 'A Touching Display of a Frisbee And a Boat who must Kill a Girl in A MySQL Convention', 2006),
(919, 'TYCOON GATHERING', 'A Emotional Display of a Husband And a A Shark who must Succumb a Madman in A Manhattan Penthouse', 2006),
(920, 'UNBREAKABLE KARATE', 'A Amazing Character Study of a Robot And a Student who must Chase a Robot in Australia', 2006),
(921, 'UNCUT SUICIDES', 'A Intrepid Yarn of a Explorer And a Pastry Chef who must Pursue a Mad Cow in A U-Boat', 2006),
(922, 'UNDEFEATED DALMATIONS', 'A Unbelieveable Display of a Crocodile And a Feminist who must Overcome a Moose in An Abandoned Amusement Park', 2006),
(923, 'UNFAITHFUL KILL', 'A Taut Documentary of a Waitress And a Mad Scientist who must Battle a Technical Writer in New Orleans', 2006),
(924, 'UNFORGIVEN ZOOLANDER', 'A Taut Epistle of a Monkey And a Sumo Wrestler who must Vanquish a A Shark in A Baloon Factory', 2006),
(925, 'UNITED PILOT', 'A Fast-Paced Reflection of a Cat And a Mad Cow who must Fight a Car in The Sahara Desert', 2006),
(926, 'UNTOUCHABLES SUNRISE', 'A Amazing Documentary of a Woman And a Astronaut who must Outrace a Teacher in An Abandoned Fun House', 2006),
(927, 'UPRISING UPTOWN', 'A Fanciful Reflection of a Boy And a Butler who must Pursue a Woman in Berlin', 2006),
(928, 'UPTOWN YOUNG', 'A Fateful Documentary of a Dog And a Hunter who must Pursue a Teacher in An Abandoned Amusement Park', 2006),
(929, 'USUAL UNTOUCHABLES', 'A Touching Display of a Explorer And a Lumberjack who must Fight a Forensic Psychologist in A Shark Tank', 2006),
(930, 'VACATION BOONDOCK', 'A Fanciful Character Study of a Secret Agent And a Mad Scientist who must Reach a Teacher in Australia', 2006),
(931, 'VALENTINE VANISHING', 'A Thrilling Display of a Husband And a Butler who must Reach a Pastry Chef in California', 2006),
(932, 'VALLEY PACKER', 'A Astounding Documentary of a Astronaut And a Boy who must Outrace a Sumo Wrestler in Berlin', 2006),
(933, 'VAMPIRE WHALE', 'A Epic Story of a Lumberjack And a Monkey who must Confront a Pioneer in A MySQL Convention', 2006),
(934, 'VANILLA DAY', 'A Fast-Paced Saga of a Girl And a Forensic Psychologist who must Redeem a Girl in Nigeria', 2006),
(935, 'VANISHED GARDEN', 'A Intrepid Character Study of a Squirrel And a A Shark who must Kill a Lumberjack in California', 2006),
(936, 'VANISHING ROCKY', 'A Brilliant Reflection of a Man And a Woman who must Conquer a Pioneer in A MySQL Convention', 2006),
(937, 'VARSITY TRIP', 'A Action-Packed Character Study of a Astronaut And a Explorer who must Reach a Monkey in A MySQL Convention', 2006),
(938, 'VELVET TERMINATOR', 'A Lacklusture Tale of a Pastry Chef And a Technical Writer who must Confront a Crocodile in An Abandoned Amusement Park', 2006),
(939, 'VERTIGO NORTHWEST', 'A Unbelieveable Display of a Mad Scientist And a Mad Scientist who must Outgun a Mad Cow in Ancient Japan', 2006),
(940, 'VICTORY ACADEMY', 'A Insightful Epistle of a Mad Scientist And a Explorer who must Challenge a Cat in The Sahara Desert', 2006),
(941, 'VIDEOTAPE ARSENIC', 'A Lacklusture Display of a Girl And a Astronaut who must Succumb a Student in Australia', 2006),
(942, 'VIETNAM SMOOCHY', 'A Lacklusture Display of a Butler And a Man who must Sink a Explorer in Soviet Georgia', 2006),
(943, 'VILLAIN DESPERATE', 'A Boring Yarn of a Pioneer And a Feminist who must Redeem a Cat in An Abandoned Amusement Park', 2006),
(944, 'VIRGIN DAISY', 'A Awe-Inspiring Documentary of a Robot And a Mad Scientist who must Reach a Database Administrator in A Shark Tank', 2006),
(945, 'VIRGINIAN PLUTO', 'A Emotional Panorama of a Dentist And a Crocodile who must Meet a Boy in Berlin', 2006),
(946, 'VIRTUAL SPOILERS', 'A Fateful Tale of a Database Administrator And a Squirrel who must Discover a Student in Soviet Georgia', 2006),
(947, 'VISION TORQUE', 'A Thoughtful Documentary of a Dog And a Man who must Sink a Man in A Shark Tank', 2006),
(948, 'VOICE PEACH', 'A Amazing Panorama of a Pioneer And a Student who must Overcome a Mad Scientist in A Manhattan Penthouse', 2006),
(949, 'VOLCANO TEXAS', 'A Awe-Inspiring Yarn of a Hunter And a Feminist who must Challenge a Dentist in The Outback', 2006),
(950, 'VOLUME HOUSE', 'A Boring Tale of a Dog And a Woman who must Meet a Dentist in California', 2006),
(951, 'VOYAGE LEGALLY', 'A Epic Tale of a Squirrel And a Hunter who must Conquer a Boy in An Abandoned Mine Shaft', 2006),
(952, 'WAGON JAWS', 'A Intrepid Drama of a Moose And a Boat who must Kill a Explorer in A Manhattan Penthouse', 2006),
(953, 'WAIT CIDER', 'A Intrepid Epistle of a Woman And a Forensic Psychologist who must Succumb a Astronaut in A Manhattan Penthouse', 2006),
(954, 'WAKE JAWS', 'A Beautiful Saga of a Feminist And a Composer who must Challenge a Moose in Berlin', 2006),
(955, 'WALLS ARTIST', 'A Insightful Panorama of a Teacher And a Teacher who must Overcome a Mad Cow in An Abandoned Fun House', 2006),
(956, 'WANDA CHAMBER', 'A Insightful Drama of a A Shark And a Pioneer who must Find a Womanizer in The Outback', 2006),
(957, 'WAR NOTTING', 'A Boring Drama of a Teacher And a Sumo Wrestler who must Challenge a Secret Agent in The Canadian Rockies', 2006),
(958, 'WARDROBE PHANTOM', 'A Action-Packed Display of a Mad Cow And a Astronaut who must Kill a Car in Ancient India', 2006),
(959, 'WARLOCK WEREWOLF', 'A Astounding Yarn of a Pioneer And a Crocodile who must Defeat a A Shark in The Outback', 2006),
(960, 'WARS PLUTO', 'A Taut Reflection of a Teacher And a Database Administrator who must Chase a Madman in The Sahara Desert', 2006),
(961, 'WASH HEAVENLY', 'A Awe-Inspiring Reflection of a Cat And a Pioneer who must Escape a Hunter in Ancient China', 2006),
(962, 'WASTELAND DIVINE', 'A Fanciful Story of a Database Administrator And a Womanizer who must Fight a Database Administrator in Ancient China', 2006),
(963, 'WATCH TRACY', 'A Fast-Paced Yarn of a Dog And a Frisbee who must Conquer a Hunter in Nigeria', 2006),
(964, 'WATERFRONT DELIVERANCE', 'A Unbelieveable Documentary of a Dentist And a Technical Writer who must Build a Womanizer in Nigeria', 2006),
(965, 'WATERSHIP FRONTIER', 'A Emotional Yarn of a Boat And a Crocodile who must Meet a Moose in Soviet Georgia', 2006),
(966, 'WEDDING APOLLO', 'A Action-Packed Tale of a Student And a Waitress who must Conquer a Lumberjack in An Abandoned Mine Shaft', 2006),
(967, 'WEEKEND PERSONAL', 'A Fast-Paced Documentary of a Car And a Butler who must Find a Frisbee in A Jet Boat', 2006),
(968, 'WEREWOLF LOLA', 'A Fanciful Story of a Man And a Sumo Wrestler who must Outrace a Student in A Monastery', 2006),
(969, 'WEST LION', 'A Intrepid Drama of a Butler And a Lumberjack who must Challenge a Database Administrator in A Manhattan Penthouse', 2006),
(970, 'WESTWARD SEABISCUIT', 'A Lacklusture Tale of a Butler And a Husband who must Face a Boy in Ancient China', 2006),
(971, 'WHALE BIKINI', 'A Intrepid Story of a Pastry Chef And a Database Administrator who must Kill a Feminist in A MySQL Convention', 2006),
(972, 'WHISPERER GIANT', 'A Intrepid Story of a Dentist And a Hunter who must Confront a Monkey in Ancient Japan', 2006),
(973, 'WIFE TURN', 'A Awe-Inspiring Epistle of a Teacher And a Feminist who must Confront a Pioneer in Ancient Japan', 2006),
(974, 'WILD APOLLO', 'A Beautiful Story of a Monkey And a Sumo Wrestler who must Conquer a A Shark in A MySQL Convention', 2006),
(975, 'WILLOW TRACY', 'A Brilliant Panorama of a Boat And a Astronaut who must Challenge a Teacher in A Manhattan Penthouse', 2006),
(976, 'WIND PHANTOM', 'A Touching Saga of a Madman And a Forensic Psychologist who must Build a Sumo Wrestler in An Abandoned Mine Shaft', 2006),
(977, 'WINDOW SIDE', 'A Astounding Character Study of a Womanizer And a Hunter who must Escape a Robot in A Monastery', 2006),
(978, 'WISDOM WORKER', 'A Unbelieveable Saga of a Forensic Psychologist And a Student who must Face a Squirrel in The First Manned Space Station', 2006),
(979, 'WITCHES PANIC', 'A Awe-Inspiring Drama of a Secret Agent And a Hunter who must Fight a Moose in Nigeria', 2006),
(980, 'WIZARD COLDBLOODED', 'A Lacklusture Display of a Robot And a Girl who must Defeat a Sumo Wrestler in A MySQL Convention', 2006),
(981, 'WOLVES DESIRE', 'A Fast-Paced Drama of a Squirrel And a Robot who must Succumb a Technical Writer in A Manhattan Penthouse', 2006),
(982, 'WOMEN DORADO', 'A Insightful Documentary of a Waitress And a Butler who must Vanquish a Composer in Australia', 2006),
(983, 'WON DARES', 'A Unbelieveable Documentary of a Teacher And a Monkey who must Defeat a Explorer in A U-Boat', 2006),
(984, 'WONDERFUL DROP', 'A Boring Panorama of a Woman And a Madman who must Overcome a Butler in A U-Boat', 2006),
(985, 'WONDERLAND CHRISTMAS', 'A Awe-Inspiring Character Study of a Waitress And a Car who must Pursue a Mad Scientist in The First Manned Space Station', 2006),
(986, 'WONKA SEA', 'A Brilliant Saga of a Boat And a Mad Scientist who must Meet a Moose in Ancient India', 2006),
(987, 'WORDS HUNTER', 'A Action-Packed Reflection of a Composer And a Mad Scientist who must Face a Pioneer in A MySQL Convention', 2006),
(988, 'WORKER TARZAN', 'A Action-Packed Yarn of a Secret Agent And a Technical Writer who must Battle a Sumo Wrestler in The First Manned Space Station', 2006),
(989, 'WORKING MICROCOSMOS', 'A Stunning Epistle of a Dentist And a Dog who must Kill a Madman in Ancient China', 2006),
(990, 'WORLD LEATHERNECKS', 'A Unbelieveable Tale of a Pioneer And a Astronaut who must Overcome a Robot in An Abandoned Amusement Park', 2006),
(991, 'WORST BANGER', 'A Thrilling Drama of a Madman And a Dentist who must Conquer a Boy in The Outback', 2006),
(992, 'WRATH MILE', 'A Intrepid Reflection of a Technical Writer And a Hunter who must Defeat a Sumo Wrestler in A Monastery', 2006),
(993, 'WRONG BEHAVIOR', 'A Emotional Saga of a Crocodile And a Sumo Wrestler who must Discover a Mad Cow in New Orleans', 2006),
(994, 'WYOMING STORM', 'A Awe-Inspiring Panorama of a Robot And a Boat who must Overcome a Feminist in A U-Boat', 2006),
(995, 'YENTL IDAHO', 'A Amazing Display of a Robot And a Astronaut who must Fight a Womanizer in Berlin', 2006),
(996, 'YOUNG LANGUAGE', 'A Unbelieveable Yarn of a Boat And a Database Administrator who must Meet a Boy in The First Manned Space Station', 2006),
(997, 'YOUTH KICK', 'A Touching Drama of a Teacher And a Cat who must Challenge a Technical Writer in A U-Boat', 2006),
(998, 'ZHIVAGO CORE', 'A Fateful Yarn of a Composer And a Man who must Face a Boy in The Canadian Rockies', 2006),
(999, 'ZOOLANDER FICTION', 'A Fateful Reflection of a Waitress And a Boat who must Discover a Sumo Wrestler in Ancient China', 2006),
(1000, 'ZORRO ARK', 'A Intrepid Panorama of a Mad Scientist And a Boy who must Redeem a Boy in A Monastery', 2006);

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

CREATE TABLE `komentar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(32) NOT NULL,
  `pesan` varchar(255) NOT NULL,
  `waktu` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nama` (`nama`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=90 ;

--
-- Dumping data for table `komentar`
--

INSERT INTO `komentar` (`id`, `nama`, `pesan`, `waktu`) VALUES
(82, 'sandhika', 'komentar 1', '12 Dec 2013 12:47:49'),
(83, 'galih', 'komentar 2', '12 Dec 2013 12:49:42'),
(84, 'hahaha', 'hihihi', '12 Dec 2013 12:50:00'),
(85, 'hohoho', 'hohoho', '12 Dec 2013 12:50:14'),
(86, 'ahiy', 'hiyae', '12 Dec 2013 12:50:30'),
(87, 'aaa', 'bbb', '12 Dec 2013 12:51:00'),
(88, '&lt;h1&gt;hahaha&lt;/h1&gt;', 'hihi', '13 Dec 2013 20:16:54'),
(89, 'tes', 'komentar lagi', '13 Dec 2013 20:18:28');

-- --------------------------------------------------------

--
-- Table structure for table `kota`
--

CREATE TABLE `kota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_provinsi` int(11) NOT NULL,
  `nama` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_provinsi` (`id_provinsi`,`nama`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;

--
-- Dumping data for table `kota`
--

INSERT INTO `kota` (`id`, `id_provinsi`, `nama`) VALUES
(3, 1, 'Jakarta Barat'),
(1, 1, 'Jakarta Pusat'),
(4, 1, 'Jakarta Selatan'),
(5, 1, 'Jakarta Timur'),
(2, 1, 'Jakarta Utara'),
(6, 2, 'Bandung'),
(14, 2, 'Banjar'),
(10, 2, 'Bekasi'),
(7, 2, 'Bogor'),
(12, 2, 'Cimahi'),
(9, 2, 'Cirebon'),
(11, 2, 'Depok'),
(8, 2, 'Sukabumi'),
(13, 2, 'Tasikmalaya'),
(19, 3, 'Magelang'),
(23, 3, 'Pekalongan'),
(21, 3, 'Salatiga'),
(22, 3, 'Semarang'),
(20, 3, 'Surakarta'),
(24, 3, 'Tegal'),
(25, 4, 'Yogyakarta'),
(34, 5, 'Batu'),
(31, 5, 'Blitar'),
(29, 5, 'Kediri'),
(28, 5, 'Madiun'),
(27, 5, 'Malang'),
(30, 5, 'Mojokerto'),
(32, 5, 'Pasuruan'),
(33, 5, 'Probolinggo'),
(26, 5, 'Surabaya'),
(36, 6, 'Banda Aceh'),
(38, 6, 'Langsa'),
(37, 6, 'Lhokseumawe'),
(35, 6, 'Sabang'),
(39, 6, 'Subulussalam'),
(40, 7, 'Binjai'),
(46, 7, 'Gunung Sitoli'),
(45, 7, 'Padang Sidempuan'),
(42, 7, 'Pematang Siantar'),
(44, 7, 'Sibolga'),
(43, 7, 'Tanjung Balai'),
(41, 7, 'Tebing Tinggi'),
(47, 8, 'Bukittinggi'),
(48, 8, 'Padang'),
(49, 8, 'Padang Panjang'),
(53, 8, 'Pariaman'),
(52, 8, 'Payakumbuh'),
(50, 8, 'Sawah Lunto'),
(51, 8, 'Solok'),
(55, 9, 'Dumai'),
(54, 9, 'Pekanbaru'),
(56, 10, 'Batam'),
(57, 10, 'Tanjungpinang'),
(58, 11, 'Jambi'),
(59, 11, 'Sungai Penuh'),
(62, 12, 'Lubuk Linggau'),
(63, 12, 'Pagar Alam'),
(60, 12, 'Palembang'),
(61, 12, 'Prabumulih'),
(64, 13, 'Pangkalpinang'),
(65, 14, 'Bengkulu'),
(66, 15, 'Batam'),
(67, 15, 'Tanjungpinang'),
(68, 16, 'Bandar Lampung'),
(69, 16, 'Metro'),
(70, 17, 'Pontianak'),
(71, 17, 'Singkawang'),
(72, 18, 'Palangka Raya'),
(74, 19, 'Banjarbaru'),
(73, 19, 'Banjarmasin'),
(76, 20, 'Balikpapan'),
(78, 20, 'Bontang'),
(75, 20, 'Samarinda'),
(77, 20, 'Tarakan'),
(80, 21, 'Bitung'),
(82, 21, 'Kotamobagu'),
(79, 21, 'Manado'),
(81, 21, 'Tomohon'),
(15, 28, 'Cilegon'),
(17, 28, 'Serang'),
(16, 28, 'Tangerang'),
(18, 28, 'Tangerang Selatan');

-- --------------------------------------------------------

--
-- Table structure for table `provinsi`
--

CREATE TABLE `provinsi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nama` (`nama`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `provinsi`
--

INSERT INTO `provinsi` (`id`, `nama`) VALUES
(22, 'Bali'),
(28, 'Banten'),
(26, 'Bengkulu'),
(4, 'DI Yogyakarta'),
(1, 'DKI Jakarta'),
(30, 'Gorontalo'),
(10, 'Jambi'),
(2, 'Jawa Barat'),
(3, 'Jawa Tengah'),
(5, 'Jawa Timur'),
(13, 'Kalimantan Barat'),
(15, 'Kalimantan Selatan'),
(14, 'Kalimantan Tengah'),
(16, 'Kalimantan Timur'),
(29, 'Kep. Bangka Belitung'),
(31, 'Kep. Riau'),
(12, 'Lampung'),
(21, 'Maluku'),
(27, 'Maluku Utara'),
(6, 'Nanggroe Aceh Darussalam'),
(23, 'Nusa Tenggara Barat'),
(24, 'Nusa Tenggara Timur'),
(25, 'Papua'),
(32, 'Papua Barat '),
(9, 'Riau'),
(33, 'Sulawesi Barat'),
(19, 'Sulawesi Selatan'),
(18, 'Sulawesi Tengah'),
(20, 'Sulawesi Tenggara'),
(17, 'Sulawesi Utara'),
(8, 'Sumatera Barat'),
(11, 'Sumatera Selatan'),
(7, 'Sumatera Utara');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kota`
--
ALTER TABLE `kota`
  ADD CONSTRAINT `kota_ibfk_1` FOREIGN KEY (`id_provinsi`) REFERENCES `provinsi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
