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
-- Table structure for table `site_acos`
--

DROP TABLE IF EXISTS `site_acos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=169 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_acos`
--

LOCK TABLES `site_acos` WRITE;
/*!40000 ALTER TABLE `site_acos` DISABLE KEYS */;
INSERT INTO `site_acos` VALUES (1,NULL,NULL,NULL,'controllers',1,162),(2,1,NULL,NULL,'Pages',2,5),(3,2,NULL,NULL,'display',3,4),(4,1,NULL,NULL,'Logs',6,9),(5,4,NULL,NULL,'index',7,8),(6,1,NULL,NULL,'Caches',10,17),(7,6,NULL,NULL,'index',11,12),(8,6,NULL,NULL,'delete',13,14),(9,6,NULL,NULL,'clearcache',15,16),(10,1,NULL,NULL,'Acl',18,25),(11,10,NULL,NULL,'set_permission',19,20),(12,10,NULL,NULL,'index',21,22),(13,10,NULL,NULL,'build_acl',23,24),(14,1,NULL,NULL,'Grupos',26,35),(15,14,NULL,NULL,'index',27,28),(16,14,NULL,NULL,'add',29,30),(17,14,NULL,NULL,'edit',31,32),(18,14,NULL,NULL,'delete',33,34),(19,1,NULL,NULL,'Usuarios',36,53),(20,19,NULL,NULL,'login_refresh',37,38),(21,19,NULL,NULL,'login',39,40),(22,19,NULL,NULL,'logout',41,42),(23,19,NULL,NULL,'change_pass',43,44),(24,19,NULL,NULL,'index',45,46),(25,19,NULL,NULL,'add',47,48),(26,19,NULL,NULL,'edit',49,50),(27,19,NULL,NULL,'delete',51,52),(60,1,NULL,NULL,'Sitemaps',54,67),(61,60,NULL,NULL,'movedown',55,56),(62,60,NULL,NULL,'moveup',57,58),(63,60,NULL,NULL,'index',59,60),(64,60,NULL,NULL,'add',61,62),(65,60,NULL,NULL,'edit',63,64),(66,60,NULL,NULL,'delete',65,66),(67,1,NULL,NULL,'Paginas',68,77),(68,67,NULL,NULL,'index',69,70),(69,67,NULL,NULL,'add',71,72),(70,67,NULL,NULL,'edit',73,74),(71,67,NULL,NULL,'delete',75,76),(82,1,NULL,NULL,'Noticias',78,89),(83,82,NULL,NULL,'index',79,80),(84,82,NULL,NULL,'add',81,82),(85,82,NULL,NULL,'edit',83,84),(86,82,NULL,NULL,'delete',85,86),(87,82,NULL,NULL,'autocomplete',87,88),(93,1,NULL,NULL,'Galerias',90,103),(94,93,NULL,NULL,'movedown',91,92),(95,93,NULL,NULL,'moveup',93,94),(96,93,NULL,NULL,'index',95,96),(97,93,NULL,NULL,'add',97,98),(98,93,NULL,NULL,'edit',99,100),(99,93,NULL,NULL,'delete',101,102),(100,1,NULL,NULL,'GaleriaArquivos',104,113),(101,100,NULL,NULL,'index',105,106),(102,100,NULL,NULL,'add',107,108),(103,100,NULL,NULL,'edit',109,110),(104,100,NULL,NULL,'delete',111,112),(105,1,NULL,NULL,'ExportFiles',114,117),(106,105,NULL,NULL,'index',115,116),(118,1,NULL,NULL,'Banners',118,127),(119,118,NULL,NULL,'index',119,120),(120,118,NULL,NULL,'add',121,122),(121,118,NULL,NULL,'edit',123,124),(122,118,NULL,NULL,'delete',125,126),(129,1,NULL,NULL,'Configurations',128,139),(130,129,NULL,NULL,'index',129,130),(131,129,NULL,NULL,'add',131,132),(132,129,NULL,NULL,'edit',133,134),(133,129,NULL,NULL,'edit_parcial',135,136),(134,129,NULL,NULL,'delete',137,138),(158,1,NULL,NULL,'Vitrines',140,149),(159,158,NULL,NULL,'index',141,142),(160,158,NULL,NULL,'add',143,144),(161,158,NULL,NULL,'edit',145,146),(162,158,NULL,NULL,'delete',147,148),(163,1,NULL,NULL,'FormNewsletters',150,161),(164,163,NULL,NULL,'index',151,152),(165,163,NULL,NULL,'add',153,154),(166,163,NULL,NULL,'edit',155,156),(167,163,NULL,NULL,'delete',157,158),(168,163,NULL,NULL,'export_xls',159,160);
/*!40000 ALTER TABLE `site_acos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_aros`
--

DROP TABLE IF EXISTS `site_aros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_aros`
--

LOCK TABLES `site_aros` WRITE;
/*!40000 ALTER TABLE `site_aros` DISABLE KEYS */;
INSERT INTO `site_aros` VALUES (1,NULL,'Grupo',1,NULL,1,12),(2,1,'Usuario',1,NULL,2,3),(3,1,'Usuario',3,NULL,4,5),(4,12,'Usuario',2,NULL,14,15),(9,1,'Usuario',8,NULL,6,7),(10,1,'Usuario',9,NULL,8,9),(12,NULL,'Grupo',2,NULL,13,16),(15,1,'Usuario',13,NULL,10,11);
/*!40000 ALTER TABLE `site_aros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_aros_acos`
--

DROP TABLE IF EXISTS `site_aros_acos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_aros_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_aros_acos`
--

LOCK TABLES `site_aros_acos` WRITE;
/*!40000 ALTER TABLE `site_aros_acos` DISABLE KEYS */;
INSERT INTO `site_aros_acos` VALUES (1,1,1,'1','1','1','1'),(2,12,1,'1','1','1','1'),(3,12,10,'-1','-1','-1','-1'),(4,12,14,'-1','-1','-1','-1'),(5,12,71,'-1','-1','-1','-1'),(6,12,69,'-1','-1','-1','-1'),(7,12,60,'-1','-1','-1','-1'),(8,12,19,'-1','-1','-1','-1'),(9,12,20,'1','1','1','1'),(11,12,131,'-1','-1','-1','-1'),(12,12,132,'-1','-1','-1','-1'),(13,12,134,'-1','-1','-1','-1'),(24,1,133,'-1','-1','-1','-1');
/*!40000 ALTER TABLE `site_aros_acos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_banners`
--

DROP TABLE IF EXISTS `site_banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_banners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `area` varchar(16) CHARACTER SET utf8 DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `imagem` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `peso` decimal(2,0) unsigned NOT NULL DEFAULT '1',
  `data_inicio` datetime DEFAULT NULL,
  `data_fim` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `area` (`area`),
  KEY `data` (`data_inicio`,`data_fim`),
  KEY `data_inicio` (`data_inicio`),
  KEY `data_fim` (`data_fim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_banners`
--

LOCK TABLES `site_banners` WRITE;
/*!40000 ALTER TABLE `site_banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_configurations`
--

DROP TABLE IF EXISTS `site_configurations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_configurations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `config` varchar(255) DEFAULT NULL,
  `value` text,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `config` (`config`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_configurations`
--

LOCK TABLES `site_configurations` WRITE;
/*!40000 ALTER TABLE `site_configurations` DISABLE KEYS */;
INSERT INTO `site_configurations` VALUES (1,'site.title','%install%','Título do site'),(2,'site.keywords','%install%','5 a 10 palavras separadas por vírgula'),(3,'site.description','%install%','Descrição do site com até 160 caracteres'),(4,'smtp.port','%install%\n','Porta do servidor SMTP'),(5,'smtp.host','%install%','Endereço do servidor de SMTP'),(6,'smtp.username','%install%','Nome de usuário para envio de emails'),(7,'smtp.password','%install%','Senha para envio de e-mails'),(8,'form_contato.to','%install%','Formulário de contato - E-mail de destino'),(9,'form_contato.subject','%install%','Formulário de contato - Assunto da mensagem');
/*!40000 ALTER TABLE `site_configurations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_form_newsletter`
--

DROP TABLE IF EXISTS `site_form_newsletter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_form_newsletter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `removed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_form_newsletter`
--

LOCK TABLES `site_form_newsletter` WRITE;
/*!40000 ALTER TABLE `site_form_newsletter` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_form_newsletter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_galeria_arquivos`
--

DROP TABLE IF EXISTS `site_galeria_arquivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_galeria_arquivos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `galeria_id` int(10) unsigned DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `arquivo` varchar(255) DEFAULT NULL,
  `legenda` varchar(255) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `friendly_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_galeria_arquivos`
--

LOCK TABLES `site_galeria_arquivos` WRITE;
/*!40000 ALTER TABLE `site_galeria_arquivos` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_galeria_arquivos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_galerias`
--

DROP TABLE IF EXISTS `site_galerias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_galerias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `friendly_url` varchar(255) DEFAULT NULL,
  `descricao` text,
  `imagem_capa` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_friendly_url` (`parent_id`,`friendly_url`),
  KEY `parent_id` (`parent_id`),
  KEY `friendly_url` (`friendly_url`),
  KEY `lft` (`lft`),
  KEY `rght` (`rght`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_galerias`
--

LOCK TABLES `site_galerias` WRITE;
/*!40000 ALTER TABLE `site_galerias` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_galerias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_grupos`
--

DROP TABLE IF EXISTS `site_grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_grupos` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_grupos`
--

LOCK TABLES `site_grupos` WRITE;
/*!40000 ALTER TABLE `site_grupos` DISABLE KEYS */;
INSERT INTO `site_grupos` VALUES (1,'Administrador','2011-02-18 08:31:01','2011-02-18 08:31:01'),(2,'Webmaster','2011-05-11 19:40:00','2011-05-11 19:40:00');
/*!40000 ALTER TABLE `site_grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_logs`
--

DROP TABLE IF EXISTS `site_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `description` text,
  `model` varchar(40) DEFAULT NULL,
  `model_id` int(10) unsigned DEFAULT NULL,
  `action` varchar(25) DEFAULT NULL,
  `usuario_id` int(10) unsigned DEFAULT NULL,
  `change` text,
  `changes` tinyint(3) unsigned DEFAULT NULL,
  `ip` char(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `model` (`model`),
  KEY `model_id` (`model_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_logs`
--

LOCK TABLES `site_logs` WRITE;
/*!40000 ALTER TABLE `site_logs` DISABLE KEYS */;
INSERT INTO `site_logs` VALUES (1,'Endereço do servidor de SMTP','2011-09-04 20:03:37','Configuration \"Endereço do servidor de SMTP\" (5) incluído pelo Usuario \"Rodrigo\" (1).','Configuration',5,'add',1,'config () => (smtp.host), description () => (Endereço do servidor de SMTP)',2,'127.0.0.1'),(2,'Endereço do servidor de SMTP','2011-09-04 20:03:45','Configuration \"Endereço do servidor de SMTP\" (5) atualizado pelo Usuario \"Rodrigo\" (1).','Configuration',5,'edit',1,'value () => (%install%)',1,'127.0.0.1'),(3,'Título do site','2011-09-04 20:03:54','Configuration \"Título do site\" (1) atualizado pelo Usuario \"Rodrigo\" (1).','Configuration',1,'edit',1,'value (Título do Site) => (%install%)',1,'127.0.0.1'),(4,'Nome de usuário para envio de emails','2011-09-04 20:05:23','Configuration \"Nome de usuário para envio de emails\" (6) incluído pelo Usuario \"Rodrigo\" (1).','Configuration',6,'add',1,'config () => (smtp.username), description () => (Nome de usuário para envio de emails), value () => (%install%)',3,'127.0.0.1'),(5,'Senha para envio de e-mails','2011-09-04 20:05:45','Configuration \"Senha para envio de e-mails\" (7) incluído pelo Usuario \"Rodrigo\" (1).','Configuration',7,'add',1,'config () => (smtp.password), description () => (Senha para envio de e-mails), value () => (%install%)',3,'127.0.0.1'),(6,'Formulário de contato - e-mail de destino','2011-09-04 20:06:31','Configuration \"Formulário de contato - e-mail de destino\" (8) incluído pelo Usuario \"Rodrigo\" (1).','Configuration',8,'add',1,'config () => (form_contato.to), description () => (Formulário de contato - e-mail de destino), value () => (%install%)',3,'127.0.0.1'),(7,'Formulário de contato - Assunto da mensagem','2011-09-04 20:06:59','Configuration \"Formulário de contato - Assunto da mensagem\" (9) incluído pelo Usuario \"Rodrigo\" (1).','Configuration',9,'add',1,'config () => (form_contato.subject), description () => (Formulário de contato - Assunto da mensagem), value () => (%install%)',3,'127.0.0.1'),(8,'Formulário de contato - E-mail de destino','2011-09-04 20:07:10','Configuration \"Formulário de contato - E-mail de destino\" (8) atualizado pelo Usuario \"Rodrigo\" (1).','Configuration',8,'edit',1,'description (Formulário de contato - e-mail de destino) => (Formulário de contato - E-mail de destino)',1,'127.0.0.1'),(9,'Aco (107)','2011-09-04 20:42:24','Aco (107) removido pelo Sistema.','Aco',107,'delete',NULL,NULL,NULL,NULL),(10,'Aco (152)','2011-09-04 21:06:01','Aco (152) incluído pelo Usuario \"Rodrigo\" (1).','Aco',152,'add',1,'parent_id () => (1), alias () => (FormDepoimentos)',2,'127.0.0.1'),(11,'Aco (153)','2011-09-04 21:06:01','Aco (153) incluído pelo Usuario \"Rodrigo\" (1).','Aco',153,'add',1,'parent_id () => (1), alias () => (FormNewsletters)',2,'127.0.0.1'),(12,'Aco (154)','2011-09-04 21:06:01','Aco (154) incluído pelo Usuario \"Rodrigo\" (1).','Aco',154,'add',1,'parent_id () => (1), alias () => (FormOrcamentos)',2,'127.0.0.1'),(13,'Aco (155)','2011-09-04 21:06:01','Aco (155) incluído pelo Usuario \"Rodrigo\" (1).','Aco',155,'add',1,'parent_id () => (1), alias () => (HomeBanners)',2,'127.0.0.1'),(14,'Aco (156)','2011-09-04 21:06:01','Aco (156) incluído pelo Usuario \"Rodrigo\" (1).','Aco',156,'add',1,'parent_id () => (1), alias () => (Newsletters)',2,'127.0.0.1'),(15,'Aco (157)','2011-09-04 21:06:01','Aco (157) incluído pelo Usuario \"Rodrigo\" (1).','Aco',157,'add',1,'parent_id () => (1), alias () => (Orcamentos)',2,'127.0.0.1'),(16,'Aco (152)','2011-09-04 21:06:03','Aco (152) removido pelo Sistema.','Aco',152,'delete',NULL,NULL,NULL,NULL),(17,'Aco (153)','2011-09-04 21:06:03','Aco (153) removido pelo Sistema.','Aco',153,'delete',NULL,NULL,NULL,NULL),(18,'Aco (154)','2011-09-04 21:06:03','Aco (154) removido pelo Sistema.','Aco',154,'delete',NULL,NULL,NULL,NULL),(19,'Aco (155)','2011-09-04 21:06:03','Aco (155) removido pelo Sistema.','Aco',155,'delete',NULL,NULL,NULL,NULL),(20,'Aco (156)','2011-09-04 21:06:03','Aco (156) removido pelo Sistema.','Aco',156,'delete',NULL,NULL,NULL,NULL),(21,'Aco (157)','2011-09-04 21:06:03','Aco (157) removido pelo Sistema.','Aco',157,'delete',NULL,NULL,NULL,NULL),(22,'Aco (158)','2011-09-04 21:57:40','Aco (158) incluído pelo Usuario \"Rodrigo\" (1).','Aco',158,'add',1,'parent_id () => (1), alias () => (Vitrines)',2,'127.0.0.1'),(23,'Aco (159)','2011-09-04 21:57:40','Aco (159) incluído pelo Usuario \"Rodrigo\" (1).','Aco',159,'add',1,'parent_id () => (158), alias () => (index)',2,'127.0.0.1'),(24,'Aco (160)','2011-09-04 21:57:40','Aco (160) incluído pelo Usuario \"Rodrigo\" (1).','Aco',160,'add',1,'parent_id () => (158), alias () => (add)',2,'127.0.0.1'),(25,'Aco (161)','2011-09-04 21:57:40','Aco (161) incluído pelo Usuario \"Rodrigo\" (1).','Aco',161,'add',1,'parent_id () => (158), alias () => (edit)',2,'127.0.0.1'),(26,'Aco (162)','2011-09-04 21:57:40','Aco (162) incluído pelo Usuario \"Rodrigo\" (1).','Aco',162,'add',1,'parent_id () => (158), alias () => (delete)',2,'127.0.0.1'),(27,'ArosAco (10)','2011-09-04 22:00:38','ArosAco (10) removido pelo Usuario \"Rodrigo\" (1).','ArosAco',10,'delete',1,NULL,NULL,'127.0.0.1'),(28,'ArosAco (15)','2011-09-04 22:08:20','ArosAco (15) removido pelo Usuario \"Rodrigo\" (1).','ArosAco',15,'delete',1,NULL,NULL,'127.0.0.1'),(29,'ArosAco (16)','2011-09-04 22:08:35','ArosAco (16) removido pelo Usuario \"Rodrigo\" (1).','ArosAco',16,'delete',1,NULL,NULL,'127.0.0.1'),(30,'ArosAco (17)','2011-09-04 23:47:09','ArosAco (17) removido pelo Usuario \"Rodrigo\" (1).','ArosAco',17,'delete',1,NULL,NULL,'127.0.0.1'),(31,'ArosAco (14)','2011-09-05 09:11:17','ArosAco (14) removido pelo Usuario \"Rodrigo\" (1).','ArosAco',14,'delete',1,NULL,NULL,'127.0.0.1'),(32,'Erick','2011-09-05 09:32:33','Usuario \"Erick\" (2) atualizado pelo Usuario \"Rodrigo\" (1).','Usuario',2,'edit',1,'grupo_id (1) => (2)',1,'127.0.0.1'),(33,'ArosAco (22)','2011-09-05 09:55:43','ArosAco (22) removido pelo Usuario \"Rodrigo\" (1).','ArosAco',22,'delete',1,NULL,NULL,'127.0.0.1'),(34,'ArosAco (18)','2011-09-05 11:49:24','ArosAco (18) removido pelo Usuario \"Rodrigo\" (1).','ArosAco',18,'delete',1,NULL,NULL,'127.0.0.1'),(35,'Aco (163)','2011-09-06 13:55:56','Aco (163) incluído pelo Usuario \"Rodrigo\" (1).','Aco',163,'add',1,'parent_id () => (1), alias () => (FormNewsletters)',2,'127.0.0.1'),(36,'Aco (164)','2011-09-06 13:55:56','Aco (164) incluído pelo Usuario \"Rodrigo\" (1).','Aco',164,'add',1,'parent_id () => (163), alias () => (index)',2,'127.0.0.1'),(37,'Aco (165)','2011-09-06 13:55:56','Aco (165) incluído pelo Usuario \"Rodrigo\" (1).','Aco',165,'add',1,'parent_id () => (163), alias () => (add)',2,'127.0.0.1'),(38,'Aco (166)','2011-09-06 13:55:56','Aco (166) incluído pelo Usuario \"Rodrigo\" (1).','Aco',166,'add',1,'parent_id () => (163), alias () => (edit)',2,'127.0.0.1'),(39,'Aco (167)','2011-09-06 13:55:56','Aco (167) incluído pelo Usuario \"Rodrigo\" (1).','Aco',167,'add',1,'parent_id () => (163), alias () => (delete)',2,'127.0.0.1'),(40,'Aco (168)','2011-09-06 13:55:56','Aco (168) incluído pelo Usuario \"Rodrigo\" (1).','Aco',168,'add',1,'parent_id () => (163), alias () => (export_xls)',2,'127.0.0.1');
/*!40000 ALTER TABLE `site_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_noticias`
--

DROP TABLE IF EXISTS `site_noticias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_noticias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `friendly_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `data_noticia` datetime DEFAULT NULL,
  `tipo` enum('noticia') CHARACTER SET utf8 DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `olho` text CHARACTER SET utf8,
  `conteudo_preview` text CHARACTER SET utf8,
  `conteudo` text CHARACTER SET utf8,
  `usuario_id` int(10) unsigned DEFAULT NULL,
  `status` enum('rascunho','em_aprovacao','aprovada') CHARACTER SET utf8 DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `image_align` enum('left','center','right') CHARACTER SET utf8 DEFAULT NULL,
  `image_legenda` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `fk_noticias_usuario` (`usuario_id`),
  KEY `friendly_url` (`friendly_url`),
  KEY `tipo` (`tipo`),
  CONSTRAINT `fk_noticias_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `site_usuarios` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_noticias`
--

LOCK TABLES `site_noticias` WRITE;
/*!40000 ALTER TABLE `site_noticias` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_noticias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_noticias_noticias`
--

DROP TABLE IF EXISTS `site_noticias_noticias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_noticias_noticias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `noticia_id` int(10) unsigned DEFAULT NULL,
  `related_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `noticia_id` (`noticia_id`),
  KEY `related_id` (`related_id`),
  CONSTRAINT `fk_noticias_noticias_noticia` FOREIGN KEY (`noticia_id`) REFERENCES `site_noticias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_noticias_noticias_related` FOREIGN KEY (`related_id`) REFERENCES `site_noticias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_noticias_noticias`
--

LOCK TABLES `site_noticias_noticias` WRITE;
/*!40000 ALTER TABLE `site_noticias_noticias` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_noticias_noticias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_paginas`
--

DROP TABLE IF EXISTS `site_paginas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_paginas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `friendly_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `texto_aspas` text,
  `conteudo` text CHARACTER SET utf8,
  `image` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `image_align` enum('left','center','right') CHARACTER SET utf8 DEFAULT NULL,
  `image_legenda` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `friendly_url` (`friendly_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_paginas`
--

LOCK TABLES `site_paginas` WRITE;
/*!40000 ALTER TABLE `site_paginas` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_paginas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_sitemaps`
--

DROP TABLE IF EXISTS `site_sitemaps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_sitemaps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `friendly_url` varchar(255) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `show_footer` enum('1') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_friendly_url` (`parent_id`,`friendly_url`),
  KEY `parent_id` (`parent_id`),
  KEY `friendly_url` (`friendly_url`),
  KEY `lft` (`lft`),
  KEY `rght` (`rght`),
  KEY `show_footer` (`show_footer`),
  KEY `route` (`route`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_sitemaps`
--

LOCK TABLES `site_sitemaps` WRITE;
/*!40000 ALTER TABLE `site_sitemaps` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_sitemaps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_usuarios`
--

DROP TABLE IF EXISTS `site_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `senha` char(32) DEFAULT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `apelido` varchar(45) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `grupo_id` tinyint(2) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `senha` (`senha`),
  KEY `status` (`status`),
  KEY `fk_usuario_grupo` (`grupo_id`),
  CONSTRAINT `fk_usuario_grupo` FOREIGN KEY (`grupo_id`) REFERENCES `site_grupos` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_usuarios`
--

LOCK TABLES `site_usuarios` WRITE;
/*!40000 ALTER TABLE `site_usuarios` DISABLE KEYS */;
INSERT INTO `site_usuarios` VALUES (1,'rodrigo@webjump.com.br','1a5ee758d08884cae83decd78b9438c2','Rodrigo Mourão','Rodrigo','1',1,'2011-02-18 08:36:51','2011-02-18 08:36:51'),(2,'erick@webjump.com.br','72e649e5be1d6526cab9d1cfb571f072','Erick Melo','Erick','1',2,'2011-02-19 23:15:57','2011-02-19 23:15:57'),(3,'ivan@webjump.com.br','dce1116861a1cec047fe4cf3310015e6','Ivan Bastos','Ivan','1',1,'2011-03-06 14:07:35','2011-03-06 14:07:35'),(8,'tiago@webjump.com.br','72e649e5be1d6526cab9d1cfb571f072','Tiago Mateus','tiago','1',1,'2011-04-26 08:56:19','2011-04-26 08:56:19'),(9,'felipe@webjump.com.br','8b59d5436322bccf4961cfcc61d94e22','Felipe Frade Domingues','felipe','1',1,'2011-04-29 10:40:14','2011-04-29 10:40:14'),(13,'tom@webjump.com.br','6a50f1f4a0016a36d5b412f6a053e097','Tomas Calil','tom','1',1,'2011-06-20 15:45:34','2011-06-20 15:45:34');
/*!40000 ALTER TABLE `site_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_vitrines`
--

DROP TABLE IF EXISTS `site_vitrines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_vitrines` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) DEFAULT NULL,
  `chamada` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `peso` decimal(2,0) unsigned DEFAULT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `data_fim` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `data` (`data_inicio`,`data_fim`),
  KEY `data_inicio` (`data_inicio`),
  KEY `data_fim` (`data_fim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_vitrines`
--

LOCK TABLES `site_vitrines` WRITE;
/*!40000 ALTER TABLE `site_vitrines` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_vitrines` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-09-08 14:56:28
