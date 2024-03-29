<?php
/*
 * 2007-2014 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2014 PrestaShop SA
 *  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'globkurier_order` (
          `id_globkurier_order` int(111) NOT NULL AUTO_INCREMENT,
          `id_order` int(111) NOT NULL,
          `id_cart` int(111) NOT NULL,
          `id_customer` int(111) NOT NULL,
          `order_number` varchar(25) NOT NULL,
          `gk_number` varchar(20) DEFAULT NULL,
          `flag` tinyint(11) NOT NULL,
          PRIMARY KEY (`id_globkurier_order`),
          UNIQUE KEY `order_number` (`order_number`)
        ) ENGINE='._MYSQL_ENGINE_.'  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'globkurier_country` (
          `id_globkurier_country` smallint(6) NOT NULL AUTO_INCREMENT,
          `name` varchar(50) NOT NULL,
          `road` tinyint(1) NOT NULL DEFAULT 1,
          `ue` tinyint(1) NOT NULL DEFAULT 1,
          `zone_fly` tinyint(4) NOT NULL DEFAULT 0,
          `zone_road` tinyint(4) NOT NULL DEFAULT 0,
          `time_fly` varchar(5) DEFAULT NULL,
          `time_road` varchar(5) DEFAULT NULL,
          `country_code` varchar(4) DEFAULT NULL,
          PRIMARY KEY (`id_globkurier_country`)
        ) ENGINE='._MYSQL_ENGINE_.'  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'INSERT INTO `'._DB_PREFIX_.'globkurier_country` 
		(`id_globkurier_country`, `name`, `road`, `ue`, 
		`zone_fly`, `zone_road`, `time_fly`, `time_road`, `country_code`
		) VALUES
        (1, "Anglia", 1, 1, 2, 3, "1-2", "3-5", "GB"),
        (2, "Australia", 0, 0, 8, 0, "3-6", "3-5", "AU"),
        (3, "Austria", 1, 1, 2, 2, "1-2", "2-4", "AT"),
        (4, "Belgia", 1, 1, 2, 2, "1-2", "3-5", "BE"),
        (5, "Bułgaria", 1, 1, 3, 3, "1-4", "4-7", "BG"),
        (6, "Chiny", 0, 0, 7, 0, "3-5", "4-7", "CN"),
        (7, "Czechy", 1, 1, 1, 1, "1-2", "2-4", "CZ"),
        (8, "Dania", 1, 1, 3, 2, "1-2", "3-5", "DK"),
        (9, "Estonia", 1, 1, 3, 2, "1-2", "3-5", "EE"),
        (10, "Finlandia", 1, 1, 3, 4, "1-2", "4-7", "FI"),
        (11, "Francja", 1, 1, 2, 3, "1-2", "3-6", "FR"),
        (12, "Grecja", 1, 1, 3, 3, "1-2", "4-6", "GR"),
        (13, "Hiszpania", 1, 1, 3, 4, "1-2", "4-6", "ES"),
        (14, "Holandia", 1, 1, 2, 2, "1-2", "3-5", "NL"),
        (15, "Irlandia (Republika)", 1, 1, 3, 4, "1-2", "3-6", "IR"),
        (16, "Irlandia Pn.", 1, 1, 3, 4, "1-2", "3-6", "IR"),
        (17, "Kanada", 0, 0, 6, 0, "3-6", "3-6", "CA"),
        (18, "Litwa", 1, 1, 3, 2, "1-2", "2-4", "LT"),
        (19, "Luksemburg", 1, 1, 2, 2, "1-2", "3-5", "LU"),
        (20, "Łotwa", 1, 1, 3, 2, "1-2", "2-4", "LV"),
        (21, "Monako", 1, 1, 2, 3, "1-2", "3-5", "MO"),
        (22, "Niemcy", 1, 1, 1, 1, "1-2", "2-4", "DE"),
        (23, "Portugalia", 1, 1, 3, 4, "1-2", "4-7", "PT"),
        (24, "Rumunia", 1, 1, 3, 3, "1-4", "3-5", "RO"),
        (25, "Słowacja", 1, 1, 1, 3, "1-2", "3-5", "SK"),
        (26, "Słowenia", 1, 1, 3, 3, "1-2", "2-4", "SI"),
        (27, "Szkocja", 1, 1, 2, 3, "1-2", "3-5", "GB"),
        (28, "Szwecja", 1, 1, 3, 3, "1-2", "3-6", "SE"),
        (29, "Stany Zjednoczone", 0, 0, 4, 0, "1-4", "3-6", "US"),
        (30, "Walia", 1, 1, 2, 3, "1-2", "3-5", "GB"),
        (31, "Węgry", 1, 1, 3, 3, "1-2", "3-5", "HU"),
        (32, "Włochy", 1, 1, 2, 3, "1-2", "3-6", "IT"),
        (100, "Albania", 1, 0, 8, 4, "1-4", "3-6", "AL"),
        (101, "Afganistan", 0, 0, 5, 0, "3-7", "3-6", "AF"),
        (102, "Algieria", 0, 0, 8, 0, "1-4", "3-6", "AL"),
        (103, "Andora", 1, 0, 5, 4, "1-2", "6-8", "AD"),
        (104, "Angola", 0, 0, 8, 0, "3-6", "6-8", "AO"),
        (105, "Anguilla", 0, 0, 8, 0, "3-5", "6-8", "AI"),
        (106, "Antigua", 0, 0, 8, 0, "4-6", "6-8", "AG"),
        (107, "Arabia Saudyjska", 0, 0, 7, 0, "2-4", "6-8", "SA"),
        (108, "Argentyna", 0, 0, 8, 0, "3-6", "6-8", "AR"),
        (109, "Armenia", 0, 0, 7, 0, "2-5", "6-8", "AM"),
        (110, "Aruba", 0, 0, 8, 0, "2-4", "6-8", "AW"),
        (111, "Azerbejdżan", 0, 0, 7, 0, "2-4", "6-8", "AZ"),
        (112, "Bahamy", 0, 0, 8, 0, "3-5", "6-8", "BS"),
        (113, "Bahrajn", 0, 0, 7, 0, "2-4", "6-8", "BH"),
        (114, "Bangladesz", 0, 0, 8, 0, "3-5", "6-8", "BD"),
        (115, "Barbados", 0, 0, 8, 0, "2-4", "6-8", "BB"),
        (116, "Belize", 0, 0, 8, 0, "3-5", "6-8", "BZ"),
        (117, "Benin", 0, 0, 8, 0, "2-6", "6-8", "BJ"),
        (118, "Bermudy", 0, 0, 8, 0, "3-5", "6-8", "BM"),
        (119, "Bhutan", 0, 0, 8, 0, "4-6", "6-8", "BT"),
        (120, "Białoruś", 1, 0, 5, 4, "2-6", "6-8", "BY"),
        (121, "Birma (Myanmar)", 0, 0, 8, 0, "2-6", "6-8", "BI"),
        (122, "Boliwia", 0, 0, 8, 0, "3-7", "6-8", "BO"),
        (123, "Bonaire", 0, 0, 8, 0, "3-5", "6-8", "AN"),
        (124, "Bośnia i Harcegowina", 1, 0, 5, 4, "1-4", "6-8", "BA"),
        (125, "Botswana", 0, 0, 8, 0, "3-5", "6-8", "BW"),
        (126, "Brazylia", 0, 0, 8, 0, "3-6", "6-8", "BR"),
        (127, "Brunei", 0, 0, 8, 0, "3-5", "6-8", "BN"),
        (128, "Burkina Faso", 0, 0, 8, 0, "2-6", "6-8", "BF"),
        (129, "Burundi", 0, 0, 8, 0, "3-5", "6-8", "BI"),
        (130, "Chile", 0, 0, 8, 0, "3-5", "6-8", "CL"),
        (131, "Chorwacja", 1, 0, 5, 4, "1-4", "5-7", "HR"),
        (132, "Curacao", 0, 0, 8, 0, "3-5", "5-7", "AN"),
        (133, "Cypr", 1, 1, 3, 4, "1-4", "5-7", "CY"),
        (134, "Czad", 0, 0, 8, 0, "5-7", "5-7", "TD"),
        (135, "Dominika", 0, 0, 8, 0, "3-5", "5-7", "DM"),
        (136, "Dominikana", 0, 0, 8, 0, "3-6", "5-7", "DO"),
        (137, "Dżibuti", 0, 0, 8, 0, "2-5", "5-7", "DJ"),
        (138, "Egipt", 0, 0, 8, 0, "2-5", "5-7", "EG"),
        (139, "Ekwador", 0, 0, 8, 0, "3-5", "5-7", "EC"),
        (140, "Erytrea", 0, 0, 8, 0, "2-4", "5-7", "ER"),
        (141, "Etiopia", 0, 0, 8, 0, "2-4", "5-7", "ET"),
        (142, "Falklandy (Malwiny)", 0, 0, 8, 0, "2-5", "5-7", "ML"),
        (143, "Fidżi", 0, 0, 8, 0, "4-6", "5-7", "FJ"),
        (144, "Filipiny", 0, 0, 8, 0, "2-6", "5-7", "PH"),
        (145, "Gabon", 0, 0, 8, 0, "3-5", "5-7", "GA"),
        (146, "Gambia", 0, 0, 8, 0, "3-5", "5-7", "GM"),
        (147, "Ghana", 0, 0, 8, 0, "2-5", "5-7", "GH"),
        (148, "Gibraltar", 1, 1, 6, 4, "2-5", "5-7", "GI"),
        (149, "Grenada", 0, 0, 8, 0, "2-4", "5-7", "GD"),
        (150, "Grenlandia", 1, 0, 6, 4, "2-4", "5-7", "GL"),
        (151, "Gruzja", 0, 0, 7, 0, "2-5", "5-7", "GE"),
        (152, "Guam (Mariany)", 0, 0, 8, 0, "3-5", "5-7", "GU"),
        (153, "Guernsey", 1, 0, 5, 3, "2-4", "4-8", "GU"),
        (154, "Gujana Brytyjska", 0, 0, 8, 0, "3-5", "4-8", "GY"),
        (155, "Gwadelupa", 0, 0, 8, 0, "3-5", "4-8", "GP"),
        (156, "Gwatemala", 0, 0, 8, 0, "2-4", "4-8", "CT"),
        (157, "Gwinea Republic", 0, 0, 8, 0, "3-5", "4-8", "GN"),
        (158, "Gwinea Bisseau", 0, 0, 8, 0, "2-4", "4-8", "GN"),
        (159, "Gwinea Równikowa", 0, 0, 8, 0, "2-5", "4-8", "GN"),
        (160, "Haiti", 0, 0, 8, 0, "2-5", "4-8", "HT"),
        (161, "Honduras", 0, 0, 8, 0, "2-4", "4-8", "HN"),
        (162, "Hongkong", 0, 0, 7, 0, "2-4", "4-8", "HK"),
        (163, "Indie", 0, 0, 7, 0, "2-5", "4-8", "IN"),
        (164, "Indonezja", 0, 0, 7, 0, "3-6", "4-8", "ID"),
        (165, "Irak", 0, 0, 8, 0, "4-6", "4-8", "IQ"),
        (166, "Iran", 0, 0, 8, 0, "3-5", "4-8", "IR"),
        (167, "Islandia", 1, 0, 6, 4, "1-2", "4-8", "IS"),
        (168, "Izrael", 0, 0, 6, 0, "2-4", "4-8", "IL"),
        (169, "Jamajka", 0, 0, 8, 0, "2-6", "4-8", "JM"),
        (170, "Japonia", 0, 0, 7, 0, "2-5", "4-8", "JP"),
        (171, "Jemen", 0, 0, 8, 0, "2-5", "4-8", "YE"),
        (172, "Jersey", 1, 0, 5, 3, "1-4", "4-8", "JY"),
        (173, "Jordania", 0, 0, 7, 0, "2-4", "4-8", "JO"),
        (174, "Kajmany", 0, 0, 8, 0, "2-5", "4-8", "KY"),
        (175, "Kambodża", 0, 0, 8, 0, "2-4", "4-8", "KH"),
        (176, "Kamerun", 0, 0, 8, 0, "2-4", "4-8", "CM"),
        (177, "Katar", 0, 0, 7, 0, "2-5", "4-8", "QA"),
        (178, "Kazachstan", 0, 0, 7, 0, "2-5", "4-8", "KZ"),
        (179, "Kenia", 0, 0, 8, 0, "2-5", "4-8", "KE"),
        (180, "Kirgistan", 0, 0, 7, 0, "3-5", "4-8", "KG"),
        (181, "Kiribati", 0, 0, 8, 0, "5-7", "4-8", "KI"),
        (182, "Kolumbia", 0, 0, 8, 0, "3-6", "4-8", "CO"),
        (183, "Komory", 0, 0, 8, 0, "3-5", "4-8", "KM"),
        (184, "Kongo", 0, 0, 8, 0, "2-5", "4-8", "CG"),
        (185, "Kongo, Rep. Demokratyczna", 0, 0, 8, 0, "3-5", "4-8", "CG"),
        (186, "Korea Południowa", 0, 0, 7, 0, "2-5", "4-8", "KR"),
        (187, "Korea Północna", 0, 0, 8, 0, "4-6", "4-8", "KR"),
        (188, "Kosowo", 0, 0, 5, 0, "2-5", "4-8", "KV"),
        (189, "Kostaryka", 0, 0, 8, 0, "2-5", "4-8", "CR"),
        (190, "Kuba", 0, 0, 8, 0, "3-6", "4-8", "CU"),
        (191, "Kuwejt", 0, 0, 7, 0, "2-4", "4-8", "KW"),
        (192, "Laos", 0, 0, 8, 0, "2-5", "4-8", "LA"),
        (193, "Lesotho", 0, 0, 8, 0, "2-4", "4-8", "LS"),
        (194, "Liban", 0, 0, 8, 0, "2-4", "4-8", "LB"),
        (195, "Liberia", 0, 0, 8, 0, "3-5", "4-8", "LR"),
        (196, "Libia", 0, 0, 8, 0, "3-6", "4-8", "LY"),
        (197, "Liechtenstein", 1, 0, 4, 4, "1-4", "5-7", "LI"),
        (198, "Macedonia", 1, 0, 5, 4, "2-5", "5-7", "MK"),
        (199, "Madagaskar", 0, 0, 8, 0, "3-5", "5-7", "MG"),
        (200, "Majotta", 0, 0, 8, 0, "3-5", "5-7", "YT"),
        (201, "Makau", 0, 0, 8, 0, "2-4", "5-7", "MO"),
        (202, "Malawi", 0, 0, 8, 0, "3-5", "5-7", "MW"),
        (203, "Malediwy", 0, 0, 8, 0, "4-6", "5-7", "MV"),
        (204, "Malezja", 0, 0, 8, 0, "2-5", "5-7", "MY"),
        (205, "Mali", 0, 0, 8, 0, "2-6", "5-7", "ML"),
        (206, "Malta", 1, 1, 3, 4, "1-2", "5-7", "MT"),
        (207, "Maroko", 0, 0, 8, 0, "2-5", "5-7", "MA"),
        (208, "Martynika", 0, 0, 8, 0, "2-6", "5-7", "MQ"),
        (209, "Mauretania", 0, 0, 8, 0, "2-6", "5-7", "MR"),
        (210, "Mauritius", 0, 0, 8, 0, "2-6", "5-7", "MU"),
        (211, "Meksyk", 0, 0, 6, 0, "2-6", "5-7", "MX"),
        (212, "Mołdawia", 1, 0, 5, 4, "2-5", "5-7", "MD"),
        (213, "Mongolia", 0, 0, 8, 0, "3-6", "5-7", "MN"),
        (214, "Monserrat", 0, 0, 8, 0, "3-5", "5-7", "MS"),
        (215, "Mozambik", 0, 0, 8, 0, "2-5", "5-7", "MZ"),
        (216, "Namibia", 0, 0, 8, 0, "3-6", "5-7", "NA"),
        (217, "Nauru", 0, 0, 8, 0, "5-7", "5-7", "NR"),
        (218, "Nepal", 0, 0, 8, 0, "2-5", "5-7", "NP"),
        (219, "Nevis", 0, 0, 8, 0, "2-4", "5-7", "KN"),
        (220, "Niger", 0, 0, 8, 0, "2-4", "5-7", "NE"),
        (221, "Nigeria", 0, 0, 8, 0, "2-5", "5-7", "NG"),
        (222, "Nikaragua", 0, 0, 8, 0, "2-4", "5-7", "NI"),
        (223, "Niue", 0, 0, 8, 0, "5-7", "5-7", "NU"),
        (224, "Norwegia", 1, 0, 4, 4, "1-4", "5-7", "NO"),
        (225, "Nowa Kaledonia", 0, 0, 8, 0, "5-7", "5-7", "NC"),
        (226, "Nowa Zelandia", 0, 0, 8, 0, "3-6", "5-7", "NZ"),
        (227, "Oman", 0, 0, 7, 0, "2-5", "5-7", "OM"),
        (228, "Pakistan", 0, 0, 8, 0, "2-5", "5-7", "PK"),
        (229, "Panama", 0, 0, 8, 0, "2-5", "5-7", "PA"),
        (230, "Papua nowa gwinea", 0, 0, 8, 0, "4-6", "5-7", "PG"),
        (231, "Paragwaj", 0, 0, 8, 0, "2-4", "5-7", "PY"),
        (232, "Peru", 0, 0, 8, 0, "2-6", "5-7", "PE"),
        (233, "Portoryko", 0, 0, 8, 0, "2-6", "5-7", "PR"),
        (234, "Rep. Południowej Afryki", 0, 0, 8, 0, "2-5", "5-7", "ZA"),
        (235, "Rep. Środkowej Afryki", 0, 0, 8, 0, "3-5", "5-7", "ZA"),
        (236, "Rep. Zielonego Przylądka", 0, 0, 8, 0, "2-5", "5-7", "ZA"),
        (237, "Reunion", 0, 1, 8, 0, "2-5", "5-7", "RE"),
        (239, "Ruanda", 0, 0, 8, 0, "3-5", "5-7", "RW"),
        (240, "Saint Barthelemy", 0, 0, 8, 0, "3-5", "5-7", "BL"),
        (241, "Saint Eustatius", 0, 0, 8, 0, "3-5", "5-7", "BQ"),
        (242, "Saint Kitts", 0, 0, 8, 0, "3-5", "5-7", "KN"),
        (243, "Saint Lucia", 0, 0, 8, 0, "2-4", "5-7", "LC"),
        (244, "Saint Martin", 0, 0, 8, 0, "2-4", "5-7", "MF"),
        (245, "Saint Vinncent", 0, 0, 8, 0, "3-5", "5-7", "VC"),
        (246, "Saipan (Mariany)", 0, 0, 8, 0, "3-5", "5-7", "MP"),
        (247, "Salwador", 0, 0, 8, 0, "2-5", "5-7", "SV"),
        (248, "Samoa", 0, 0, 8, 0, "4-6", "5-7", "AS"),
        (249, "Senegal", 0, 0, 8, 0, "2-4", "5-7", "SN"),
        (250, "Serbia", 1, 0, 5, 4, "2-5", "5-7", "RS"),
        (251, "Seszele", 0, 0, 8, 0, "2-4", "5-7", "SC"),
        (252, "Sierra Leone", 0, 0, 8, 0, "2-5", "5-7", "SL"),
        (253, "Singapur", 0, 0, 7, 0, "2-4", "5-7", "SG"),
        (254, "Somalia", 0, 0, 8, 0, "2-4", "5-7", "SO"),
        (255, "Somaliland, Republika Północnej Somali", 0, 0, 8, 0, "2-4", "5-7", "SO"),
        (256, "Sri Lanka", 0, 0, 8, 0, "2-5", "5-7", "LK"),
        (257, "Suazi", 0, 0, 8, 0, "2-4", "5-7", "SZ"),
        (258, "Sudan", 0, 0, 8, 0, "3-6", "5-7", "SD"),
        (259, "Surinam", 0, 0, 8, 0, "3-6", "5-7", "SR"),
        (260, "Syria", 0, 0, 8, 0, "3-6", "5-7", "SY"),
        (261, "Szwajcaria", 1, 0, 4, 4, "1-2", "4-7", "CH"),
        (262, "Tadżykistan", 0, 0, 7, 0, "2-6", "4-7", "TJ"),
        (264, "Tajlandia", 0, 0, 7, 0, "2-5", "4-7", "TH"),
        (265, "Tajwan", 0, 0, 7, 0, "2-4", "4-7", "TW"),
        (266, "Tanzania", 0, 0, 8, 0, "2-5", "4-7", "TZ"),
        (267, "Timor Wschodni", 0, 0, 8, 0, "4-6", "4-7", "TL"),
        (268, "Togo", 0, 0, 8, 0, "2-5", "4-7", "TG"),
        (269, "Tonga", 0, 0, 8, 0, "5-7", "4-7", "TO"),
        (270, "Trynidad i Tobago", 0, 0, 8, 0, "3-6", "4-7", "TT"),
        (271, "Tunezja", 0, 0, 8, 0, "2-5", "4-7", "TN"),
        (272, "Turcja", 1, 0, 5, 4, "2-4", "6-10", "TR"),
        (273, "Turks i Caicos", 0, 0, 8, 0, "3-5", "6-10", "TC"),
        (274, "Tuvalu", 0, 0, 8, 0, "6-8", "6-10", "TV"),
        (275, "Uganda", 0, 0, 8, 0, "2-4", "6-10", "UG"),
        (276, "Ukraina", 0, 0, 5, 0, "2-5", "6-10", "UA"),
        (277, "Urugwaj", 0, 0, 8, 0, "2-5", "6-10", "UY"),
        (278, "Uzbekistan", 0, 0, 7, 0, "2-6", "6-10", "UZ"),
        (279, "Vanuatu", 0, 0, 8, 0, "5-7", "6-10", "VU"),
        (280, "Wenezuela", 0, 0, 8, 0, "2-6", "6-10", "VE"),
        (281, "Wietnam", 0, 0, 8, 0, "3-5", "6-10", "VN"),
        (282, "Wyb. Kości Słoniowej", 0, 0, 8, 0, "2-6", "6-10", "CI"),
        (283, "Wyspy Cooka", 0, 0, 8, 0, "4-6", "6-10", "CK"),
        (284, "Wyspy Dziewicze (Wielka Brytania)", 0, 0, 8, 0, "2-5", "6-10", "GB"),
        (285, "Wyspy Dziewicze Stanów Zjednoczonych", 0, 0, 8, 0, "2-5", "6-10", "VI"),
        (287, "Wyspy Marshalla", 0, 0, 8, 0, "3-5", "6-10", "MH"),
        (288, "Wyspy Owcze", 0, 0, 6, 0, "2-4", "6-10", "FO"),
        (289, "Wyspy Salomona", 0, 0, 8, 0, "5-7", "6-10", "SB"),
        (290, "Wyspy Św.Tomasza i Książęca", 0, 0, 8, 0, "5-7", "6-10", "ST"),
        (291, "Zambia", 0, 0, 8, 0, "2-5", "6-10", "ZM"),
        (292, "Zimbabwe", 0, 0, 8, 0, "2-5", "6-10", "ZW"),
        (293, "Zjednoczone Emiraty Arabskie", 0, 0, 7, 0, "2-4", "6-10", "AE"),
        (294, "Czarnogóra", 1, 1, 8, 0, "3-5", "6-10", "ME"),
        (295, "Rosja", 1, 0, 5, 4, "3-5", "4-7", "RU");';
