-- Adminer 3.4.0 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `cell`;
CREATE TABLE `cell` (
  `id_cell` int(11) NOT NULL AUTO_INCREMENT,
  `id_layout` int(11) NOT NULL,
  `id_geometry` int(11) NOT NULL,
  `row` int(11) NOT NULL,
  `col` int(11) NOT NULL,
  `static` tinyint(1) NOT NULL,
  `rowspan` int(11) NOT NULL,
  `colspan` int(11) NOT NULL,
  PRIMARY KEY (`id_cell`),
  KEY `Index_1` (`id_cell`),
  KEY `FK_CellHasGeometry` (`id_geometry`),
  KEY `FK_CellOnLayout` (`id_layout`),
  CONSTRAINT `FK_CellHasGeometry` FOREIGN KEY (`id_geometry`) REFERENCES `geometry` (`id_geometry`),
  CONSTRAINT `FK_CellOnLayout` FOREIGN KEY (`id_layout`) REFERENCES `layout` (`id_layout`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `content`;
CREATE TABLE `content` (
  `id_content` int(11) NOT NULL AUTO_INCREMENT,
  `id_module` int(11) DEFAULT NULL,
  `id_phrase` int(11) NOT NULL,
  PRIMARY KEY (`id_content`),
  KEY `Index_1` (`id_content`),
  KEY `FK_ModuleOfContent` (`id_module`),
  CONSTRAINT `FK_ModuleOfContent` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `contentinslot`;
CREATE TABLE `contentinslot` (
  `id_content` int(11) NOT NULL,
  `id_slot` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id_content`,`id_slot`),
  KEY `Index_1` (`id_content`,`id_slot`),
  KEY `FK_ContentInSlot` (`id_slot`),
  CONSTRAINT `FK_ContentInSlot` FOREIGN KEY (`id_slot`) REFERENCES `slot` (`id_slot`),
  CONSTRAINT `FK_ContentInSlot2` FOREIGN KEY (`id_content`) REFERENCES `content` (`id_content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `geometry`;
CREATE TABLE `geometry` (
  `id_geometry` int(11) NOT NULL AUTO_INCREMENT,
  `width` float DEFAULT NULL,
  `width_unit` varchar(5) COLLATE utf8_czech_ci DEFAULT NULL,
  `height` float DEFAULT NULL,
  `height_unit` varchar(5) COLLATE utf8_czech_ci DEFAULT NULL,
  `x` float DEFAULT NULL,
  `x_unit` varchar(5) COLLATE utf8_czech_ci DEFAULT NULL,
  `y` float DEFAULT NULL,
  `y_unit` varchar(5) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id_geometry`),
  KEY `Index_1` (`id_geometry`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
  `id_language` int(11) NOT NULL AUTO_INCREMENT,
  `shortcut` varchar(2) COLLATE utf8_czech_ci NOT NULL,
  `location` varchar(2) COLLATE utf8_czech_ci DEFAULT NULL,
  `title` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id_language`),
  KEY `Index_1` (`id_language`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `layout`;
CREATE TABLE `layout` (
  `id_layout` int(11) NOT NULL AUTO_INCREMENT,
  `id_phrase` int(11) NOT NULL,
  PRIMARY KEY (`id_layout`),
  KEY `Index_1` (`id_layout`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `module`;
CREATE TABLE `module` (
  `id_module` int(11) NOT NULL AUTO_INCREMENT,
  `id_module_parent` int(11) DEFAULT NULL,
  `label` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `id_phrase` int(11) NOT NULL,
  PRIMARY KEY (`id_module`),
  KEY `Index_1` (`id_module`),
  KEY `FK_ParentModule` (`id_module_parent`),
  KEY `phrase` (`id_phrase`),
  CONSTRAINT `FK_ParentModule` FOREIGN KEY (`id_module_parent`) REFERENCES `module` (`id_module`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `module_setting`;
CREATE TABLE `module_setting` (
  `id_moduleSetting` int(11) NOT NULL AUTO_INCREMENT,
  `id_module` int(11) NOT NULL,
  `objectModuleType` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `id_objectModule` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `value` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`id_moduleSetting`),
  KEY `Index_1` (`id_moduleSetting`),
  KEY `FK_SettingOfModule` (`id_module`),
  CONSTRAINT `FK_SettingOfModule` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `modulehtml_section`;
CREATE TABLE `modulehtml_section` (
  `id_section` int(11) NOT NULL AUTO_INCREMENT,
  `id_content` int(11) NOT NULL,
  `id_style` int(11) NOT NULL,
  `id_phrase` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id_section`),
  KEY `Index_1` (`id_section`),
  KEY `FK_ModuleHtml_SectionHasModuleHtml_Style` (`id_style`),
  KEY `FK_ModuleHtml_SectionInContent` (`id_content`),
  CONSTRAINT `FK_ModuleHtml_SectionHasModuleHtml_Style` FOREIGN KEY (`id_style`) REFERENCES `style` (`id_style`),
  CONSTRAINT `FK_ModuleHtml_SectionInContent` FOREIGN KEY (`id_content`) REFERENCES `content` (`id_content`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `modulemenu_item`;
CREATE TABLE `modulemenu_item` (
  `id_item` int(11) NOT NULL AUTO_INCREMENT,
  `id_content` int(11) NOT NULL,
  `id_phrase` int(11) NOT NULL,
  `id_item_parent` int(11) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `id_page_reference` int(11) DEFAULT NULL,
  `id_slot_reference` int(11) DEFAULT NULL,
  `id_cell_reference` int(11) DEFAULT NULL,
  `referenceType` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `referenceUrl` varchar(256) COLLATE utf8_czech_ci DEFAULT NULL,
  `subMenuType` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `visible` tinyint(4) NOT NULL,
  `id_geometry` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_item`),
  KEY `Index_1` (`id_item`),
  KEY `FK_ReferenceToCell` (`id_cell_reference`),
  KEY `FK_ReferenceToPage` (`id_page_reference`),
  KEY `FK_ReferenceToSlot` (`id_slot_reference`),
  KEY `id_content` (`id_content`),
  KEY `id_item_parent` (`id_item_parent`),
  KEY `id_geometry` (`id_geometry`),
  CONSTRAINT `FK_ReferenceToCell` FOREIGN KEY (`id_cell_reference`) REFERENCES `cell` (`id_cell`),
  CONSTRAINT `FK_ReferenceToPage` FOREIGN KEY (`id_page_reference`) REFERENCES `page` (`id_page`),
  CONSTRAINT `FK_ReferenceToSlot` FOREIGN KEY (`id_slot_reference`) REFERENCES `slot` (`id_slot`),
  CONSTRAINT `modulemenu_item_ibfk_1` FOREIGN KEY (`id_content`) REFERENCES `content` (`id_content`),
  CONSTRAINT `modulemenu_item_ibfk_2` FOREIGN KEY (`id_item_parent`) REFERENCES `modulemenu_item` (`id_item`),
  CONSTRAINT `modulemenu_item_ibfk_3` FOREIGN KEY (`id_geometry`) REFERENCES `geometry` (`id_geometry`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `id_page` int(11) NOT NULL AUTO_INCREMENT,
  `id_layout` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `id_phrase` int(11) NOT NULL,
  PRIMARY KEY (`id_page`),
  KEY `Index_1` (`id_page`),
  KEY `FK_LayoutOfPage` (`id_layout`),
  CONSTRAINT `FK_LayoutOfPage` FOREIGN KEY (`id_layout`) REFERENCES `layout` (`id_layout`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `id_permission` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `operation` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `id_phrase` int(11) NOT NULL,
  PRIMARY KEY (`id_permission`),
  KEY `Index_1` (`id_permission`)
) ENGINE=InnoDB AUTO_INCREMENT=2999 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `phrase`;
CREATE TABLE `phrase` (
  `id_phrase` int(11) NOT NULL,
  `id_language` int(11) NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `link` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id_phrase`,`id_language`),
  KEY `Index_1` (`id_phrase`,`id_language`),
  KEY `FK_LanguageOfPhrase` (`id_language`),
  CONSTRAINT `FK_LanguageOfPhrase` FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `value` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`name`),
  KEY `Index_1` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `slot`;
CREATE TABLE `slot` (
  `id_slot` int(11) NOT NULL AUTO_INCREMENT,
  `id_phrase` int(11) NOT NULL,
  PRIMARY KEY (`id_slot`),
  KEY `Index_1` (`id_slot`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `slotonpageincell`;
CREATE TABLE `slotonpageincell` (
  `id_page` int(11) NOT NULL,
  `id_cell` int(11) NOT NULL,
  `id_slot` int(11) NOT NULL,
  PRIMARY KEY (`id_page`,`id_cell`,`id_slot`),
  KEY `Index_1` (`id_page`,`id_cell`,`id_slot`),
  KEY `FK_SlotInCell` (`id_cell`),
  KEY `FK_SlotOnPageInCell` (`id_slot`),
  CONSTRAINT `FK_SlotInCell` FOREIGN KEY (`id_cell`) REFERENCES `cell` (`id_cell`),
  CONSTRAINT `FK_SlotOnPage` FOREIGN KEY (`id_page`) REFERENCES `page` (`id_page`),
  CONSTRAINT `FK_SlotOnPageInCell` FOREIGN KEY (`id_slot`) REFERENCES `slot` (`id_slot`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `style`;
CREATE TABLE `style` (
  `id_style` int(11) NOT NULL AUTO_INCREMENT,
  `id_phrase` int(11) NOT NULL,
  `css` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id_style`),
  KEY `Index_1` (`id_style`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `id_userGroup` int(11) DEFAULT NULL,
  `username` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'SHA512',
  `firstName` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `lastName` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `eMail` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `hashCode` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  `lastAccessDate` datetime NOT NULL,
  `ip` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `userAgent` text COLLATE utf8_czech_ci NOT NULL,
  `countLoads` int(11) NOT NULL,
  `noCookie` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `hashCode` (`hashCode`),
  KEY `Index_1` (`id_user`),
  KEY `FK_UserInUserGroup` (`id_userGroup`),
  CONSTRAINT `FK_UserInUserGroup` FOREIGN KEY (`id_userGroup`) REFERENCES `usergroup` (`id_userGroup`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `usergroup`;
CREATE TABLE `usergroup` (
  `id_userGroup` int(11) NOT NULL AUTO_INCREMENT,
  `id_phrase` int(11) NOT NULL,
  `id_userGroup_parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_userGroup`),
  KEY `Index_1` (`id_userGroup`),
  KEY `FK_ParentUserGroup` (`id_userGroup_parent`),
  CONSTRAINT `FK_ParentUserGroup` FOREIGN KEY (`id_userGroup_parent`) REFERENCES `usergroup` (`id_userGroup`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `usergrouphaspermission`;
CREATE TABLE `usergrouphaspermission` (
  `id_permission` int(11) NOT NULL,
  `id_userGroup` int(11) NOT NULL,
  PRIMARY KEY (`id_permission`,`id_userGroup`),
  KEY `Index_1` (`id_permission`),
  KEY `FK_UserGroupHasPermission` (`id_userGroup`),
  CONSTRAINT `FK_UserGroupHasPermission` FOREIGN KEY (`id_userGroup`) REFERENCES `usergroup` (`id_userGroup`),
  CONSTRAINT `FK_UserGroupHasPermission2` FOREIGN KEY (`id_permission`) REFERENCES `permission` (`id_permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


-- 2012-07-04 09:39:52
