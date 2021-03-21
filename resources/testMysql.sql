
SET NAMES utf8mb4 ;

--
-- Table structure for table `cars`
--
DROP TABLE IF EXISTS `cars`;
SET character_set_client = utf8mb4 ;
CREATE TABLE `cars` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `owner_girl` int(11) DEFAULT NULL,
  `is_fast` enum('no','yes') COLLATE utf8mb4_polish_ci DEFAULT NULL,
  `name` varchar(45) COLLATE utf8mb4_polish_ci DEFAULT NULL,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `cars`
--
INSERT INTO `cars` VALUES (1,1,'yes','Toyota'),(2,1,'no','Nissan'),(3,2,'yes','Opel'),(4,3,'yes','VW'),(5,3,'no','BMW');

--
-- Table structure for table `girls`
--
DROP TABLE IF EXISTS `girls`;
SET character_set_client = utf8mb4 ;
CREATE TABLE `girls` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_polish_ci DEFAULT NULL,
  `webs` set('sc','pt','is','fb') COLLATE utf8mb4_polish_ci DEFAULT NULL,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `girls`
--
INSERT INTO `girls` VALUES (1,'Adriana','sc,is'),(2,'Kylie','fb'),(3,'Diamond','pt'),(4,'Makenzie','sc'),(5,'Mikaela','sc,fb'),(6,'Krystal','is'),(7,'Maliyah','pt,fb'),(8,'Paityn','sc,pt,is'),(9,'Lillianna','fb'),(10,'Annabella','pt'),(11,'Avah','is'),(12,'Kaley','sc,pt,is,fb');

--
-- Table structure for table `things`
--
DROP TABLE IF EXISTS `things`;
SET character_set_client = utf8mb4 ;
CREATE TABLE `things` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_polish_ci DEFAULT NULL,
  `json_data` varchar(5000) COLLATE utf8mb4_polish_ci DEFAULT NULL,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `things`
--
INSERT INTO `things` VALUES (1,'something','{ \"data1\": true, \"data2\": 5, \"data3\": \"string\" }');
