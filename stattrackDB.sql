-- MariaDB dump 10.19  Distrib 10.4.22-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: riot
-- ------------------------------------------------------
-- Server version	10.4.22-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `favmatches`
--

DROP TABLE IF EXISTS `favmatches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favmatches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `APIMatchID` varchar(255) DEFAULT NULL,
  `continent` varchar(45) DEFAULT NULL,
  `mainPlayerPUUID` varchar(255) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `userId_idx` (`userId`),
  CONSTRAINT `userId` FOREIGN KEY (`userId`) REFERENCES `user` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favmatches`
--

LOCK TABLES `favmatches` WRITE;
/*!40000 ALTER TABLE `favmatches` DISABLE KEYS */;
INSERT INTO `favmatches` VALUES (37,'EUN1_3168953699','europe','RrrjwpQHidYmA62ikOk7idGtxtuHpCgeBCbWCV1mKbaeRDF2i_IHBIFvTlHH0fWpmhFZT-K60HgIew',10),(38,'EUN1_3168971806','europe','RrrjwpQHidYmA62ikOk7idGtxtuHpCgeBCbWCV1mKbaeRDF2i_IHBIFvTlHH0fWpmhFZT-K60HgIew',10),(39,'EUN1_3169571434','europe','RrrjwpQHidYmA62ikOk7idGtxtuHpCgeBCbWCV1mKbaeRDF2i_IHBIFvTlHH0fWpmhFZT-K60HgIew',10),(40,'EUN1_3169566353','europe','RrrjwpQHidYmA62ikOk7idGtxtuHpCgeBCbWCV1mKbaeRDF2i_IHBIFvTlHH0fWpmhFZT-K60HgIew',10);
/*!40000 ALTER TABLE `favmatches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favourites`
--

DROP TABLE IF EXISTS `favourites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favourites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `summonerName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serverId` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  CONSTRAINT `favourites_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favourites`
--

LOCK TABLES `favourites` WRITE;
/*!40000 ALTER TABLE `favourites` DISABLE KEYS */;
INSERT INTO `favourites` VALUES (1,'Condemn for Stun','eun1',10),(2,'samiras midriff','eun1',10);
/*!40000 ALTER TABLE `favourites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recentsearches`
--

DROP TABLE IF EXISTS `recentsearches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recentsearches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `summonerName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `puuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `encryptedSummonerId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profileIconId` int(12) DEFAULT NULL,
  `summonerLevel` int(12) DEFAULT NULL,
  `timeUpdated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recentsearches`
--

LOCK TABLES `recentsearches` WRITE;
/*!40000 ALTER TABLE `recentsearches` DISABLE KEYS */;
INSERT INTO `recentsearches` VALUES (21,'Condemn for Stun','eun1','bFIevMKyxaPWODOXdHmEz8G5fwQ_C6QmHl0R3jNpuc5HgCRDOQ4oPZ-miFQK7GSj1BoDq-obtFt76Q','jAcOJoArjCtbg1CiwxGI01MgIZE80tCQc12UCJPYdEI2faw',5141,251,'2022-07-13 16:02:12'),(22,'samiras midriff','eun1','RrrjwpQHidYmA62ikOk7idGtxtuHpCgeBCbWCV1mKbaeRDF2i_IHBIFvTlHH0fWpmhFZT-K60HgIew','0Yp465woB1s6HeOvoBU0KdRh6VHyxqumddCCvI-JeLQxvSs',5381,331,'2022-07-13 16:01:53');
/*!40000 ALTER TABLE `recentsearches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tips`
--

DROP TABLE IF EXISTS `tips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tips` (
  `tipId` int(11) NOT NULL AUTO_INCREMENT,
  `tipText` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`tipId`)
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tips`
--

LOCK TABLES `tips` WRITE;
/*!40000 ALTER TABLE `tips` DISABLE KEYS */;
INSERT INTO `tips` VALUES (1,'The capital city of the Noxian Empire was founded around the Immortal Bastion; the citadel of a dreaded revenant who terrorized Valoran for centuries.'),(2,'One of Kled\'s many titles is \"High Major Commodore of the First Legion Third Multiplication Double Admiral Artillery Vanguard Company.\"'),(3,'The Brackern, a race of enormous, crystal-bodied scorpions, slumber in the sands beneath a hidden valley in Shurima.'),(4,'Sai Kahleek is one of Shurima\'s most feared deserts, as it is populated by the ravenous Xer\'Sai.'),(5,'Many Voidborn have names originating from the ancient Shuriman language.'),(6,'The God-Willow was an ancient tree found in Ionia, before being felled by Ivern the Cruel.'),(7,'The man who preceded Shen as \"Eye of Twilight\" was his father, Kusho.'),(8,'Much of Bilgewater\'s export comes from the valuable goods harvested from the carcasses of sea monsters gutted at the Slaughter Docks.'),(9,'Miss Fortune\'s guns were crafted by her mother for a young Gangplank, who turned the guns on their creator.'),(10,'Legends speak of a realm beyond Mount Targon\'s peak, inhabited by great celestial beings known as the Aspects.'),(11,'Using lime, ash and the fossilized bark of ancient trees, Demacians create petricite; magic absorbing stone with which they built their walls.'),(12,'Sometimes known as silver steel or rune steel, Demacian steel is coveted for its durability, light weight and its unique interactions with magic.'),(13,'Garen and Lux are siblings of the Crownguard family, who traditionally serve as the royal protectors.'),(14,'Fiora serves as the head of House Laurent in Demacia, a position she claimed upon killing her father in a duel.'),(15,'When Zaun\'s plans to create a waterway between east and west Valoran went awry, Janna prevented thousands of deaths and is now revered by many as a goddess.'),(16,'Many frost trolls have rallied behind the self proclaimed \"Troll King,\" Trundle.'),(17,'Artifacts of True Ice hold incredible power, but only the Iceborn can wield them.'),(18,'Before becoming an Ice Witch, Lissandra was permanently blinded by an Ursine\'s claws.'),(19,'Trundle\'s True Ice club is named \"Boneshiver.\"'),(20,'It is said the passage of time is different in Bandle City, giving yordles a timeless nature.'),(21,'Because of their glamour, the true form of a yordle is difficult for normal humans to perceive.'),(22,'Before the Ruination, the Shadow Isles were known as the Blessed Isles.'),(23,'Mordekaiser\'s mace is named \"Nightfall.\"'),(24,'To stave off a great danger beneath the ocean, the Marai illuminate the depths with a moonstone.'),(25,'The vastaya are chimeric beings who owe their heritage to a union of humans and an ancient, long extinct race.'),(26,'Basilisks of the Kumungu are sometimes raised from eggs as Noxian war beasts.'),(27,'Drakehounds, a distant relative of dragons, are kept by some rich, and perhaps foolish, Noxians as pets.'),(28,'Warmasons are tasked by Noxus not only to plot and plan infrastructure, but also to scout out enemy territory for future invasions.'),(29,'The sisters Katarina and Cassiopeia are a part of the Du Couteau family; one of the most prestigious noble houses in Noxus.'),(30,'Native to the rocky crags of northern Demacia are the rare, ferocious Raptors. Only a few individuals have been known to befriend and ride these beasts.'),(31,'Legends speak of how Avarosa, ancient queen of the Freljord, was killed by her own sister.'),(32,'The Incognium Runeterra in Piltover was designed to locate anyone in Runeterra, yet its creator mysteriously died before revealing the formula that powers it, leaving it inoperable.'),(33,'Zaun\'s wealthiest can often be found on the Promenade level of Zaun, which intersects with Piltover\'s lowest regions.'),(34,'The Entresol level of Zaun is where most of the city\'s trade and business occurs.'),(35,'The Sump comprises Zaun\'s lower levels. Most of the working class live here, showcasing their vibrant culture.'),(36,'Souls claimed by the Black Mist are known to some as \"the Fallen.\" While usually incorporeal, they can be harmed with the right tools, such as magic, silver or even sunlight.'),(37,'The Blessed Isles were once home to an ancient society of scholars who gathered magical artifacts and historical records from across Runeterra.'),(38,'Above the city of Nashramae stands a replica Sun Disc, built long ago to honor the lost legacy of ancient Shurima.'),(39,'In the Shuriman city of Nashramae, a festival is held celebrating Rammus. Thousands gather to roll and somersault around the city in his honor.'),(40,'Within the bowels of Shurima\'s capital lies the \"Oasis of the Dawn,\" from which almost all rivers in the land flow from.'),(41,'Some Shuriman nomads have created entire towns upon the backs of the Dormun; enormous, armor-plated beasts that wander the wastes.'),(42,'The Shuriman city of Zuretta is ruled by Hierophant Hadiya Nejem, who believes herself to be a descendant of the Ascended warrior Setaka.'),(43,'In Shurima, those who survived a failed Ascension ritual are known as the Baccai, who often are twisted and malformed.'),(44,'The Solari revere the sun and denounce all other forms of light as inferior. Their faith dominates the slopes of Mount Targon.'),(45,'The Rakkor of Mount Targon spend much of their days battling raiders, slaying otherworldly beings, and sparring amongst themselves.'),(46,'There is a garden in southern Ionia where flowers consume memories.'),(47,'Garen left home to join the Dauntless Vanguard, one of Demacia\'s most elite military forces, when he was only twelve years old.'),(48,'Khada Jhin is a stage name. Jhin\'s true identity remains a mystery.'),(49,'In life, Hecarim was the leader of the Iron Order; an infamous brotherhood of mounted knights.'),(50,'Using enchanted waters buried deep in the earth, Maokai turned the desolate islands he was born on into a verdant paradise.'),(51,'With his dying breath, Sion killed Jarvan I. The King\'s crown now serves as Sion\'s jaw.'),(52,'Wukong was once known as Kong, but upon besting his teacher in combat, earned an honorific reserved only for the brightest of Wuju students, becoming Wukong.'),(53,'Rumble\'s mech is named \"Tristy,\" in honor of a yordle who is very important to him.'),(54,'Poppy was once close friends with a man named Orlon. She took up his hammer upon his death.'),(55,'Blitzcrank was created by Viktor to be a stronger, faster and smarter golem; one that could better help the people of Zaun.'),(56,'Ekko is one of the \"Lost Children of Zaun,\" a band of Zaunite youths known for their mischief across the city.'),(57,'Illaoi wields the \"Eye of God,\" an idol of one of the gods of Buhru.'),(58,'The Tidecaller\'s staff allows its wielder to command water, and is drawn to the power of the moon.'),(59,'Thanks to her hextech heart, Camille has managed to retain some of her youthful looks despite her age.'),(60,'Jinx\'s rocket launcher, mini-gun and electric pistol are named Fishbones, Pow-Pow and Zapper.'),(61,'Dr. Mundo isn\'t actually a doctor. He just thinks he is.'),(62,'Pantheon is one of the Aspects of Targon, and currently inhabits the body of a Rakkoran warrior named Atreus.'),(63,'Galio was created over a period of two years by a legendary Demacian sculptor named Durand.'),(64,'Quinn and her twin brother Caleb were born in the rural Demacian town of Uwendale.'),(65,'Rengar is one of the Kiilash, a vastayan tribe from Shurima.'),(66,'Nami is one of the Marai; a vastayan tribe from the seas west of Mount Targon.'),(67,'Xayah and Rakan are of the Lhotlan vastaya, who used to thrive in the Ionian highlands.'),(68,'Wukong\'s vastayan tribe, the Shimon, dwells amongst the canopies of Ionia\'s tallest trees.'),(69,'Jax\'s favorite food is eggs, particularly those from a certain caravansary in the Shuriman city of Uzeris.'),(70,'The people of Bilgewater have a great respect for the monstrous creatures of the deep, so much so, that their currency is Golden Krakens and Silver Serpents.'),(71,'The Ecliptic Vaults were once considered to be Piltover\'s most secure bank, before Jinx breached its heavily reinforced walls.'),(72,'Caitlyn\'s hextech rifle was crafted by her parents for her twenty-first birthday.'),(73,'Jayce\'s transforming hammer is powered by a shard from a Brackern crystal from the depths of the Shuriman sands.'),(74,'Taric, a former Demacian soldier, is host to one of the Aspects of Targon: The Protector.'),(75,'It was upon Mount Targon that the Star Forger first met the Targonians, who gave him the name Aurelion Sol.'),(76,'When the human girl Orianna succumbed to poisonous fumes, her father Corin replaced her failing body parts with mechanical augments, transforming her into a lady of clockwork.'),(77,'Before becoming a member of the Wardens, Vi wielded a pair of gauntlets taken from a chem-powered mining golem.'),(78,'The callers of the Serpent Isles sound enormous horns carved into cliffs to beckon forth great sea monsters.'),(79,'Twisted Fate was born to the nomadic river-folk of the Serpentine, a river flowing near Bilgewater.'),(80,'Nunu\'s yeti companion is named Willump.'),(81,'Darius and Draven are brothers.'),(82,'The name Tahm Kench was that of a gambler who made a bargain with the River King. That name has since become synonymous with the demon.'),(83,'When the wharf rats of Bilgewater gather in great swarms, they can devour a grown adult in minutes.'),(84,'Gateways of dark stone known as Noxtoraa are raised over roads in territories conquered by Noxus.'),(85,'Hextech is the fusion of magic and technology, harnessing the power contained within extremely rare crystals.'),(86,'Piltover is the center of mercantile trade in Valoran.'),(87,'Zaun\'s powerful Chem-Barons keep a loose alliance that prevents the city from descending into chaos.'),(88,'The Zaun Gray is the thick, chemical atmosphere of Zaun that can be fatal to breathe where densely settled.'),(89,'Ionian architecture is known for its harmonious fluidity with nature.'),(90,'The Black Mist can manifest anywhere in Valoran during the Harrowing, though it strikes Bilgewater most.'),(91,'Many structures in Bilgewater are made from remnants of ships gutted upon its rocky shores.'),(92,'In Bilgewater, the dead are not buried - they are given back to the ocean in submerged caskets below bobbing tombstones.'),(93,'True Ice never melts. Dark Ice is True Ice that has been corrupted.'),(94,'The Frozen Watchers are believed to be trapped in the Howling Abyss.'),(95,'Sivir\'s crossblade, the Chalicar, once belonged to Setaka, an Ascended Warrior Queen of Shurima.'),(96,'Illaoi was Gangplank\'s first love.'),(97,'Fantastical occurrences may indicate where the veil separating Runeterra and the Void has worn thin.'),(98,'Some say yordles know secret ways through Runeterra others cannot perceive.'),(99,'The Bearded Lady, Mother Below, and Nagakabouros are all names Bilgewater natives call their deity.'),(100,'Located far from civilization, Mount Targon is utterly remote, reachable only by the most determined seeker.'),(101,'Those Ascended by the Sun Disc of Shurima can live for thousands of years.'),(102,'Demacia\'s army is often outnumbered, but is arguably the most elite, well-trained army in Runeterra.'),(103,'Your champion will continue attacking after only a single click.'),(104,'Brush, the grassy areas around Summoner\'s Rift, hides you from enemies.'),(105,'You gain the benefits of items right when you buy them. No need to equip!'),(106,'In the Options menu, you can assign a keybind that lets you chat with other players on your team.'),(107,'The monsters in the jungle won\'t hurt you unless you attack them.'),(108,'Abilities don\'t usually affect buildings.'),(109,'Gold can be spent to upgrade items to make you MUCH more powerful.'),(110,'While there are no enemy Minions nearby, Towers only take 33% of incoming damage.'),(111,'In the Options menu, you can assign a keybind to make your champion stop attacking.'),(112,'You can buy items while dead.'),(113,'Item effects marked as a UNIQUE Passive provides its benefit only once.'),(114,'Using Wards will let you see where you\'re not, even in bushes!'),(115,'If you hold down Ctrl or Alt, you can click and drag with your mouse to access a menu of special pings.'),(116,'Individually, Minions don\'t do a lot of damage. But in a group, their attacks can really add up!'),(117,'Enemy Minions will turn to attack you if you attack a champion near them.'),(118,'A few abilities, like Garen\'s Decisive Strike, can damage Towers.'),(119,'At Level 9, you can upgrade your trinket for free.'),(120,'Some champions can use abilities to move over walls, allowing them to attack or escape unexpectedly.'),(121,'The colors in your ability tooltips can give you hints, such as which item stats make that ability stronger.'),(122,'If you land the last hit that kills a Minion or jungle monster, you earn the gold for that kill.'),(123,'Ability Power increases the overall effectiveness of many abilities, not just damage.'),(124,'You can hold down Alt to cast an ability on yourself.'),(125,'You can quickly level-up an ability by holding Ctrl and pressing the ability\'s hotkey.'),(126,'Unlike most channeled spells, Recall can be interrupted by damage. This can be used to disrupt your opponent\'s plans!'),(127,'Minions that spawn together are commonly referred to as a \"wave.\"'),(128,'Each section of a champion\'s health bar represents 100 HP.'),(129,'You can right click to both buy and sell items.'),(130,'Killing 3 waves of Minions is worth more gold than killing 1 champion at the game\'s start. Minions are worth even more later!'),(131,'The Item Shop can be resized by dragging its bottom right corner.'),(132,'You can look at a Tower\'s item inventory to see many kinds of special effects that Towers have.'),(133,'The Dragon respawns 6 minutes after its death.'),(134,'Towers deal increasingly more damage with each hit.'),(135,'The green dot on your teammates\' portraits shows when their ultimate abilities are ready!'),(136,'Invisible champions can be revealed with the Sweeping Lens and Oracle Alteration trinkets.'),(137,'Champions with empowered autoattacks, like Jax, can reset their autoattacks with said abilities.'),(138,'Tower icons on the minimap become empty as their towers become damaged.'),(139,'Slaying Baron Nashor grants your team a lot of gold and a significantly faster recall that lets you get back and upgrade your items quickly.'),(140,'The first Tower taken in a game gives out extra gold.'),(141,'Champion kills are worth more gold to your team if multiple people contributed to the kill.'),(142,'Sometimes the threat of an ability is more dangerous than the usage of the ability.'),(143,'While there are no enemy Minions nearby, Towers become immune to True Damage and only take 33% of all other damage.'),(144,'You can have a maximum of 40% Cooldown Reduction, but a mastery can change this!'),(145,'The Rift Scuttler loses her Armor and Magic Resist after being immobilized.'),(146,'In the Item Shop, you can hover over underlined words to see more information.'),(147,'If you Flash into a wall, you will appear at the nearest location. Use this to gain extra distance.'),(148,'You can cast Flash during the cast time of some abilities, letting you surprise your opponents.'),(149,'If you have both Rapid Firecannon and Statikk Shiv, the Shiv lightning can hit Towers.'),(150,'While there\'s no Dragon present, you can see which kind of Dragon will arrive next by looking at the symbol in its pit.'),(151,'The Rift Herald returns to the Void at 19:30, but if it\'s in combat it can stay for another 20 seconds.'),(152,'Some monsters have negative Magic Resist. Magic Damage deals extra damage to them.'),(153,'If you find yourself attacking an enemy when you meant to be standing alone in a bush, the bush you\'re in likely has an enemy ward in it.'),(154,'Warding Totem and Sightstone Wards only last for a limited time. You can click on them to view their remaining duration.'),(155,'It takes just over 30 seconds for a Minion to reach the center of Top Lane from when it spawns. It takes just over 20 seconds for Mid Lane.'),(156,'Destroyed Wards leave behind debris. The brighter they are, the more recently they expired.'),(157,'You can place Wards and drink Potions while channeling a spell, such as Recall.'),(158,'You can watch your own Minions on the minimap to figure out where your opponent\'s Minions are.'),(159,'Leaving a few enemy Minions alive in a lane will cause your Minions to slowly push forward without you needing to be there.'),(160,'While you have the scoreboard open, clicking the monster timers in the top communicates them to your teammates. No need to press Alt.'),(161,'At game start, an attack from a group of 3 caster Minions deals more total damage than a single attack from any unequipped champion.'),(162,'League of Legends changes frequently. Make sure to read your tooltips regularly to keep up to date!'),(163,'Champions are most vulnerable to attack when they are in the middle of an action, like attacking a Minion.'),(164,'Baron Nashor does much more damage to those behind him.'),(165,'Attacking an enemy while you\'re inside brush will reveal you.'),(166,'Many objects created by champions can be Teleported to. This includes Thresh\'s lantern, Jarvan\'s flag, or even Zac\'s exploded blobs.'),(167,'The stats gained from a champion level is usually worth more gold than the gold from a champion kill.'),(168,'When items that share a UNIQUE Passive are both equipped, the best part of each Passive applies, but the individual effects do not duplicate.'),(169,'Jinx\'s classic splash background is a demolished version of Vi\'s.'),(170,'A sushi restaurant in China once offered discounts based on League rank.'),(171,'It takes 3 hours and 20 minutes to travel from fountain to fountain only using flash (without runes).');
/*!40000 ALTER TABLE `tips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `iduser_UNIQUE` (`iduser`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'1','1','1'),(3,'idk','idk@gmail.com','bad3f741f25beddc6812db4514d1bfc7'),(5,'idk2','idk2@gmail.com','bad3f741f25beddc6812db4514d1bfc7'),(6,'idk1','idk1@gmail.com','bad3f741f25beddc6812db4514d1bfc7'),(7,'idk3','idk3@gmail.com','bad3f741f25beddc6812db4514d1bfc7'),(8,'idk4','idk4@gmail.com','bad3f741f25beddc6812db4514d1bfc7'),(9,'idk5','idk5@gmail.com','bad3f741f25beddc6812db4514d1bfc7'),(10,'test','test@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(11,'test123','test123@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(12,'test12','test12@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(14,'test1','test1@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(17,'test2','test2@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(18,'test3','test3@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(20,'test4','test4@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(23,'test5','test5@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(26,'test6','test6@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(31,'testk','testk@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(33,'testkk','testkk@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(34,'testkkk','testkkk@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(36,'testkkkk','testkkkk@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(37,'testkkkkk','testkkkkk@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(38,'lol','lol@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(39,'lol1','lol1@gmail.com','e10adc3949ba59abbe56e057f20f883e'),(41,'lol2','lol2@gmail.com','e10adc3949ba59abbe56e057f20f883e');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-07-15 10:04:01
