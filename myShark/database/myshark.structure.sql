-- Adminer 3.4.0 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET @adminer_alter = '';

CREATE TABLE IF NOT EXISTS `cell` (
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

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_cell` int(11) NOT NULL auto_increment FIRST, ADD `id_layout` int(11) NOT NULL AFTER `id_cell`, ADD `id_geometry` int(11) NOT NULL AFTER `id_layout`, ADD `row` int(11) NOT NULL AFTER `id_geometry`, ADD `col` int(11) NOT NULL AFTER `row`, ADD `static` tinyint(1) NOT NULL AFTER `col`, ADD `rowspan` int(11) NOT NULL AFTER `static`, ADD `colspan` int(11) NOT NULL AFTER `rowspan`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'cell' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_cell' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_cell` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_cell` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'id_layout' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_layout` int(11) NOT NULL AFTER `id_cell`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_cell'
					, '', ', MODIFY `id_layout` int(11) NOT NULL AFTER `id_cell`'));
				WHEN 'id_geometry' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_geometry` int(11) NOT NULL AFTER `id_layout`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_layout'
					, '', ', MODIFY `id_geometry` int(11) NOT NULL AFTER `id_layout`'));
				WHEN 'row' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `row` int(11) NOT NULL AFTER `id_geometry`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_geometry'
					, '', ', MODIFY `row` int(11) NOT NULL AFTER `id_geometry`'));
				WHEN 'col' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `col` int(11) NOT NULL AFTER `row`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'row'
					, '', ', MODIFY `col` int(11) NOT NULL AFTER `row`'));
				WHEN 'static' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `static` tinyint(1) NOT NULL AFTER `col`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'tinyint(1)' AND _extra = '' AND _column_comment = '' AND after = 'col'
					, '', ', MODIFY `static` tinyint(1) NOT NULL AFTER `col`'));
				WHEN 'rowspan' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `rowspan` int(11) NOT NULL AFTER `static`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'static'
					, '', ', MODIFY `rowspan` int(11) NOT NULL AFTER `static`'));
				WHEN 'colspan' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `colspan` int(11) NOT NULL AFTER `rowspan`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'rowspan'
					, '', ', MODIFY `colspan` int(11) NOT NULL AFTER `rowspan`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `cell`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `content` (
  `id_content` int(11) NOT NULL AUTO_INCREMENT,
  `id_module` int(11) DEFAULT NULL,
  `id_phrase` int(11) NOT NULL,
  PRIMARY KEY (`id_content`),
  KEY `Index_1` (`id_content`),
  KEY `FK_ModuleOfContent` (`id_module`),
  CONSTRAINT `FK_ModuleOfContent` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_content` int(11) NOT NULL auto_increment FIRST, ADD `id_module` int(11) AFTER `id_content`, ADD `id_phrase` int(11) NOT NULL AFTER `id_module`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'content' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_content' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_content` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_content` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'id_module' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_module` int(11) AFTER `id_content`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_content'
					, '', ', MODIFY `id_module` int(11) AFTER `id_content`'));
				WHEN 'id_phrase' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_phrase` int(11) NOT NULL AFTER `id_module`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_module'
					, '', ', MODIFY `id_phrase` int(11) NOT NULL AFTER `id_module`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `content`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `contentinslot` (
  `id_content` int(11) NOT NULL,
  `id_slot` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id_content`,`id_slot`),
  KEY `Index_1` (`id_content`,`id_slot`),
  KEY `FK_ContentInSlot` (`id_slot`),
  CONSTRAINT `FK_ContentInSlot` FOREIGN KEY (`id_slot`) REFERENCES `slot` (`id_slot`),
  CONSTRAINT `FK_ContentInSlot2` FOREIGN KEY (`id_content`) REFERENCES `content` (`id_content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_content` int(11) NOT NULL FIRST, ADD `id_slot` int(11) NOT NULL AFTER `id_content`, ADD `order` int(11) NOT NULL AFTER `id_slot`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'contentinslot' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_content' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_content` int(11) NOT NULL FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_content` int(11) NOT NULL FIRST'));
				WHEN 'id_slot' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_slot` int(11) NOT NULL AFTER `id_content`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_content'
					, '', ', MODIFY `id_slot` int(11) NOT NULL AFTER `id_content`'));
				WHEN 'order' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `order` int(11) NOT NULL AFTER `id_slot`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_slot'
					, '', ', MODIFY `order` int(11) NOT NULL AFTER `id_slot`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `contentinslot`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `geometry` (
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

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_geometry` int(11) NOT NULL auto_increment FIRST, ADD `width` float AFTER `id_geometry`, ADD `width_unit` varchar(5) COLLATE utf8_czech_ci AFTER `width`, ADD `height` float AFTER `width_unit`, ADD `height_unit` varchar(5) COLLATE utf8_czech_ci AFTER `height`, ADD `x` float AFTER `height_unit`, ADD `x_unit` varchar(5) COLLATE utf8_czech_ci AFTER `x`, ADD `y` float AFTER `x_unit`, ADD `y_unit` varchar(5) COLLATE utf8_czech_ci AFTER `y`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'geometry' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_geometry' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_geometry` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_geometry` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'width' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `width` float AFTER `id_geometry`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'float' AND _extra = '' AND _column_comment = '' AND after = 'id_geometry'
					, '', ', MODIFY `width` float AFTER `id_geometry`'));
				WHEN 'width_unit' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `width_unit` varchar(5) COLLATE utf8_czech_ci AFTER `width`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(5)' AND _extra = '' AND _column_comment = '' AND after = 'width'
					, '', ', MODIFY `width_unit` varchar(5) COLLATE utf8_czech_ci AFTER `width`'));
				WHEN 'height' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `height` float AFTER `width_unit`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'float' AND _extra = '' AND _column_comment = '' AND after = 'width_unit'
					, '', ', MODIFY `height` float AFTER `width_unit`'));
				WHEN 'height_unit' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `height_unit` varchar(5) COLLATE utf8_czech_ci AFTER `height`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(5)' AND _extra = '' AND _column_comment = '' AND after = 'height'
					, '', ', MODIFY `height_unit` varchar(5) COLLATE utf8_czech_ci AFTER `height`'));
				WHEN 'x' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `x` float AFTER `height_unit`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'float' AND _extra = '' AND _column_comment = '' AND after = 'height_unit'
					, '', ', MODIFY `x` float AFTER `height_unit`'));
				WHEN 'x_unit' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `x_unit` varchar(5) COLLATE utf8_czech_ci AFTER `x`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(5)' AND _extra = '' AND _column_comment = '' AND after = 'x'
					, '', ', MODIFY `x_unit` varchar(5) COLLATE utf8_czech_ci AFTER `x`'));
				WHEN 'y' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `y` float AFTER `x_unit`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'float' AND _extra = '' AND _column_comment = '' AND after = 'x_unit'
					, '', ', MODIFY `y` float AFTER `x_unit`'));
				WHEN 'y_unit' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `y_unit` varchar(5) COLLATE utf8_czech_ci AFTER `y`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(5)' AND _extra = '' AND _column_comment = '' AND after = 'y'
					, '', ', MODIFY `y_unit` varchar(5) COLLATE utf8_czech_ci AFTER `y`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `geometry`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `language` (
  `id_language` int(11) NOT NULL AUTO_INCREMENT,
  `shortcut` varchar(2) COLLATE utf8_czech_ci NOT NULL,
  `location` varchar(2) COLLATE utf8_czech_ci DEFAULT NULL,
  `title` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id_language`),
  KEY `Index_1` (`id_language`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_language` int(11) NOT NULL auto_increment FIRST, ADD `shortcut` varchar(2) COLLATE utf8_czech_ci NOT NULL AFTER `id_language`, ADD `location` varchar(2) COLLATE utf8_czech_ci AFTER `shortcut`, ADD `title` varchar(50) COLLATE utf8_czech_ci AFTER `location`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'language' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_language' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_language` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_language` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'shortcut' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `shortcut` varchar(2) COLLATE utf8_czech_ci NOT NULL AFTER `id_language`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(2)' AND _extra = '' AND _column_comment = '' AND after = 'id_language'
					, '', ', MODIFY `shortcut` varchar(2) COLLATE utf8_czech_ci NOT NULL AFTER `id_language`'));
				WHEN 'location' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `location` varchar(2) COLLATE utf8_czech_ci AFTER `shortcut`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(2)' AND _extra = '' AND _column_comment = '' AND after = 'shortcut'
					, '', ', MODIFY `location` varchar(2) COLLATE utf8_czech_ci AFTER `shortcut`'));
				WHEN 'title' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `title` varchar(50) COLLATE utf8_czech_ci AFTER `location`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(50)' AND _extra = '' AND _column_comment = '' AND after = 'location'
					, '', ', MODIFY `title` varchar(50) COLLATE utf8_czech_ci AFTER `location`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `language`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `layout` (
  `id_layout` int(11) NOT NULL AUTO_INCREMENT,
  `id_phrase` int(11) NOT NULL,
  PRIMARY KEY (`id_layout`),
  KEY `Index_1` (`id_layout`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_layout` int(11) NOT NULL auto_increment FIRST, ADD `id_phrase` int(11) NOT NULL AFTER `id_layout`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'layout' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_layout' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_layout` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_layout` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'id_phrase' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_phrase` int(11) NOT NULL AFTER `id_layout`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_layout'
					, '', ', MODIFY `id_phrase` int(11) NOT NULL AFTER `id_layout`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `layout`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `module` (
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

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_module` int(11) NOT NULL auto_increment FIRST, ADD `id_module_parent` int(11) AFTER `id_module`, ADD `label` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `id_module_parent`, ADD `id_phrase` int(11) NOT NULL AFTER `label`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'module' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_module' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_module` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_module` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'id_module_parent' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_module_parent` int(11) AFTER `id_module`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_module'
					, '', ', MODIFY `id_module_parent` int(11) AFTER `id_module`'));
				WHEN 'label' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `label` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `id_module_parent`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(50)' AND _extra = '' AND _column_comment = '' AND after = 'id_module_parent'
					, '', ', MODIFY `label` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `id_module_parent`'));
				WHEN 'id_phrase' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_phrase` int(11) NOT NULL AFTER `label`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'label'
					, '', ', MODIFY `id_phrase` int(11) NOT NULL AFTER `label`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `module`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `module_setting` (
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

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_moduleSetting` int(11) NOT NULL auto_increment FIRST, ADD `id_module` int(11) NOT NULL AFTER `id_moduleSetting`, ADD `objectModuleType` varchar(50) COLLATE utf8_czech_ci AFTER `id_module`, ADD `id_objectModule` int(11) AFTER `objectModuleType`, ADD `name` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `id_objectModule`, ADD `value` text COLLATE utf8_czech_ci AFTER `name`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'module_setting' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_moduleSetting' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_moduleSetting` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_moduleSetting` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'id_module' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_module` int(11) NOT NULL AFTER `id_moduleSetting`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_moduleSetting'
					, '', ', MODIFY `id_module` int(11) NOT NULL AFTER `id_moduleSetting`'));
				WHEN 'objectModuleType' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `objectModuleType` varchar(50) COLLATE utf8_czech_ci AFTER `id_module`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(50)' AND _extra = '' AND _column_comment = '' AND after = 'id_module'
					, '', ', MODIFY `objectModuleType` varchar(50) COLLATE utf8_czech_ci AFTER `id_module`'));
				WHEN 'id_objectModule' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_objectModule` int(11) AFTER `objectModuleType`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'objectModuleType'
					, '', ', MODIFY `id_objectModule` int(11) AFTER `objectModuleType`'));
				WHEN 'name' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `name` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `id_objectModule`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(50)' AND _extra = '' AND _column_comment = '' AND after = 'id_objectModule'
					, '', ', MODIFY `name` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `id_objectModule`'));
				WHEN 'value' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `value` text COLLATE utf8_czech_ci AFTER `name`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'text' AND _extra = '' AND _column_comment = '' AND after = 'name'
					, '', ', MODIFY `value` text COLLATE utf8_czech_ci AFTER `name`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `module_setting`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `modulehtml_section` (
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

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_section` int(11) NOT NULL auto_increment FIRST, ADD `id_content` int(11) NOT NULL AFTER `id_section`, ADD `id_style` int(11) NOT NULL AFTER `id_content`, ADD `id_phrase` int(11) NOT NULL AFTER `id_style`, ADD `order` int(11) NOT NULL AFTER `id_phrase`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'modulehtml_section' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_section' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_section` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_section` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'id_content' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_content` int(11) NOT NULL AFTER `id_section`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_section'
					, '', ', MODIFY `id_content` int(11) NOT NULL AFTER `id_section`'));
				WHEN 'id_style' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_style` int(11) NOT NULL AFTER `id_content`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_content'
					, '', ', MODIFY `id_style` int(11) NOT NULL AFTER `id_content`'));
				WHEN 'id_phrase' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_phrase` int(11) NOT NULL AFTER `id_style`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_style'
					, '', ', MODIFY `id_phrase` int(11) NOT NULL AFTER `id_style`'));
				WHEN 'order' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `order` int(11) NOT NULL AFTER `id_phrase`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_phrase'
					, '', ', MODIFY `order` int(11) NOT NULL AFTER `id_phrase`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `modulehtml_section`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `modulemenu_item` (
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

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_item` int(11) NOT NULL auto_increment FIRST, ADD `id_content` int(11) NOT NULL AFTER `id_item`, ADD `id_phrase` int(11) NOT NULL AFTER `id_content`, ADD `id_item_parent` int(11) AFTER `id_phrase`, ADD `order` int(11) NOT NULL AFTER `id_item_parent`, ADD `id_page_reference` int(11) AFTER `order`, ADD `id_slot_reference` int(11) AFTER `id_page_reference`, ADD `id_cell_reference` int(11) AFTER `id_slot_reference`, ADD `referenceType` varchar(20) COLLATE utf8_czech_ci NOT NULL AFTER `id_cell_reference`, ADD `referenceUrl` varchar(256) COLLATE utf8_czech_ci AFTER `referenceType`, ADD `subMenuType` varchar(20) COLLATE utf8_czech_ci NOT NULL AFTER `referenceUrl`, ADD `active` tinyint(4) NOT NULL AFTER `subMenuType`, ADD `visible` tinyint(4) NOT NULL AFTER `active`, ADD `id_geometry` int(11) AFTER `visible`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'modulemenu_item' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_item' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_item` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_item` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'id_content' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_content` int(11) NOT NULL AFTER `id_item`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_item'
					, '', ', MODIFY `id_content` int(11) NOT NULL AFTER `id_item`'));
				WHEN 'id_phrase' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_phrase` int(11) NOT NULL AFTER `id_content`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_content'
					, '', ', MODIFY `id_phrase` int(11) NOT NULL AFTER `id_content`'));
				WHEN 'id_item_parent' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_item_parent` int(11) AFTER `id_phrase`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_phrase'
					, '', ', MODIFY `id_item_parent` int(11) AFTER `id_phrase`'));
				WHEN 'order' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `order` int(11) NOT NULL AFTER `id_item_parent`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_item_parent'
					, '', ', MODIFY `order` int(11) NOT NULL AFTER `id_item_parent`'));
				WHEN 'id_page_reference' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_page_reference` int(11) AFTER `order`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'order'
					, '', ', MODIFY `id_page_reference` int(11) AFTER `order`'));
				WHEN 'id_slot_reference' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_slot_reference` int(11) AFTER `id_page_reference`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_page_reference'
					, '', ', MODIFY `id_slot_reference` int(11) AFTER `id_page_reference`'));
				WHEN 'id_cell_reference' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_cell_reference` int(11) AFTER `id_slot_reference`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_slot_reference'
					, '', ', MODIFY `id_cell_reference` int(11) AFTER `id_slot_reference`'));
				WHEN 'referenceType' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `referenceType` varchar(20) COLLATE utf8_czech_ci NOT NULL AFTER `id_cell_reference`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(20)' AND _extra = '' AND _column_comment = '' AND after = 'id_cell_reference'
					, '', ', MODIFY `referenceType` varchar(20) COLLATE utf8_czech_ci NOT NULL AFTER `id_cell_reference`'));
				WHEN 'referenceUrl' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `referenceUrl` varchar(256) COLLATE utf8_czech_ci AFTER `referenceType`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(256)' AND _extra = '' AND _column_comment = '' AND after = 'referenceType'
					, '', ', MODIFY `referenceUrl` varchar(256) COLLATE utf8_czech_ci AFTER `referenceType`'));
				WHEN 'subMenuType' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `subMenuType` varchar(20) COLLATE utf8_czech_ci NOT NULL AFTER `referenceUrl`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(20)' AND _extra = '' AND _column_comment = '' AND after = 'referenceUrl'
					, '', ', MODIFY `subMenuType` varchar(20) COLLATE utf8_czech_ci NOT NULL AFTER `referenceUrl`'));
				WHEN 'active' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `active` tinyint(4) NOT NULL AFTER `subMenuType`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'tinyint(4)' AND _extra = '' AND _column_comment = '' AND after = 'subMenuType'
					, '', ', MODIFY `active` tinyint(4) NOT NULL AFTER `subMenuType`'));
				WHEN 'visible' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `visible` tinyint(4) NOT NULL AFTER `active`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'tinyint(4)' AND _extra = '' AND _column_comment = '' AND after = 'active'
					, '', ', MODIFY `visible` tinyint(4) NOT NULL AFTER `active`'));
				WHEN 'id_geometry' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_geometry` int(11) AFTER `visible`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'visible'
					, '', ', MODIFY `id_geometry` int(11) AFTER `visible`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `modulemenu_item`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `page` (
  `id_page` int(11) NOT NULL AUTO_INCREMENT,
  `id_layout` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `id_phrase` int(11) NOT NULL,
  PRIMARY KEY (`id_page`),
  KEY `Index_1` (`id_page`),
  KEY `FK_LayoutOfPage` (`id_layout`),
  CONSTRAINT `FK_LayoutOfPage` FOREIGN KEY (`id_layout`) REFERENCES `layout` (`id_layout`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_page` int(11) NOT NULL auto_increment FIRST, ADD `id_layout` int(11) NOT NULL AFTER `id_page`, ADD `order` int(11) NOT NULL AFTER `id_layout`, ADD `id_phrase` int(11) NOT NULL AFTER `order`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'page' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_page' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_page` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_page` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'id_layout' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_layout` int(11) NOT NULL AFTER `id_page`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_page'
					, '', ', MODIFY `id_layout` int(11) NOT NULL AFTER `id_page`'));
				WHEN 'order' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `order` int(11) NOT NULL AFTER `id_layout`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_layout'
					, '', ', MODIFY `order` int(11) NOT NULL AFTER `id_layout`'));
				WHEN 'id_phrase' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_phrase` int(11) NOT NULL AFTER `order`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'order'
					, '', ', MODIFY `id_phrase` int(11) NOT NULL AFTER `order`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `page`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `permission` (
  `id_permission` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `operation` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `id_phrase` int(11) NOT NULL,
  PRIMARY KEY (`id_permission`),
  KEY `Index_1` (`id_permission`)
) ENGINE=InnoDB AUTO_INCREMENT=3000 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_permission` int(11) NOT NULL auto_increment FIRST, ADD `type` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `id_permission`, ADD `operation` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `type`, ADD `id_phrase` int(11) NOT NULL AFTER `operation`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'permission' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_permission' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_permission` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_permission` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'type' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `type` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `id_permission`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(50)' AND _extra = '' AND _column_comment = '' AND after = 'id_permission'
					, '', ', MODIFY `type` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `id_permission`'));
				WHEN 'operation' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `operation` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `type`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(50)' AND _extra = '' AND _column_comment = '' AND after = 'type'
					, '', ', MODIFY `operation` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `type`'));
				WHEN 'id_phrase' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_phrase` int(11) NOT NULL AFTER `operation`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'operation'
					, '', ', MODIFY `id_phrase` int(11) NOT NULL AFTER `operation`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `permission`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `phrase` (
  `id_phrase` int(11) NOT NULL,
  `id_language` int(11) NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `link` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id_phrase`,`id_language`),
  KEY `Index_1` (`id_phrase`,`id_language`),
  KEY `FK_LanguageOfPhrase` (`id_language`),
  CONSTRAINT `FK_LanguageOfPhrase` FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_phrase` int(11) NOT NULL FIRST, ADD `id_language` int(11) NOT NULL AFTER `id_phrase`, ADD `text` text COLLATE utf8_czech_ci NOT NULL AFTER `id_language`, ADD `link` varchar(100) COLLATE utf8_czech_ci AFTER `text`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'phrase' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_phrase' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_phrase` int(11) NOT NULL FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_phrase` int(11) NOT NULL FIRST'));
				WHEN 'id_language' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_language` int(11) NOT NULL AFTER `id_phrase`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_phrase'
					, '', ', MODIFY `id_language` int(11) NOT NULL AFTER `id_phrase`'));
				WHEN 'text' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `text` text COLLATE utf8_czech_ci NOT NULL AFTER `id_language`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'text' AND _extra = '' AND _column_comment = '' AND after = 'id_language'
					, '', ', MODIFY `text` text COLLATE utf8_czech_ci NOT NULL AFTER `id_language`'));
				WHEN 'link' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `link` varchar(100) COLLATE utf8_czech_ci AFTER `text`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(100)' AND _extra = '' AND _column_comment = '' AND after = 'text'
					, '', ', MODIFY `link` varchar(100) COLLATE utf8_czech_ci AFTER `text`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `phrase`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `setting` (
  `name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `value` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`name`),
  KEY `Index_1` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `name` varchar(50) COLLATE utf8_czech_ci NOT NULL FIRST, ADD `value` text COLLATE utf8_czech_ci AFTER `name`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'setting' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'name' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `name` varchar(50) COLLATE utf8_czech_ci NOT NULL FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(50)' AND _extra = '' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `name` varchar(50) COLLATE utf8_czech_ci NOT NULL FIRST'));
				WHEN 'value' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `value` text COLLATE utf8_czech_ci AFTER `name`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'text' AND _extra = '' AND _column_comment = '' AND after = 'name'
					, '', ', MODIFY `value` text COLLATE utf8_czech_ci AFTER `name`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `setting`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `slot` (
  `id_slot` int(11) NOT NULL AUTO_INCREMENT,
  `id_phrase` int(11) NOT NULL,
  PRIMARY KEY (`id_slot`),
  KEY `Index_1` (`id_slot`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_slot` int(11) NOT NULL auto_increment FIRST, ADD `id_phrase` int(11) NOT NULL AFTER `id_slot`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'slot' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_slot' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_slot` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_slot` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'id_phrase' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_phrase` int(11) NOT NULL AFTER `id_slot`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_slot'
					, '', ', MODIFY `id_phrase` int(11) NOT NULL AFTER `id_slot`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `slot`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `slotonpageincell` (
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

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_page` int(11) NOT NULL FIRST, ADD `id_cell` int(11) NOT NULL AFTER `id_page`, ADD `id_slot` int(11) NOT NULL AFTER `id_cell`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'slotonpageincell' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_page' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_page` int(11) NOT NULL FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_page` int(11) NOT NULL FIRST'));
				WHEN 'id_cell' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_cell` int(11) NOT NULL AFTER `id_page`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_page'
					, '', ', MODIFY `id_cell` int(11) NOT NULL AFTER `id_page`'));
				WHEN 'id_slot' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_slot` int(11) NOT NULL AFTER `id_cell`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_cell'
					, '', ', MODIFY `id_slot` int(11) NOT NULL AFTER `id_cell`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `slotonpageincell`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `style` (
  `id_style` int(11) NOT NULL AUTO_INCREMENT,
  `id_phrase` int(11) NOT NULL,
  `css` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id_style`),
  KEY `Index_1` (`id_style`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_style` int(11) NOT NULL auto_increment FIRST, ADD `id_phrase` int(11) NOT NULL AFTER `id_style`, ADD `css` text COLLATE utf8_czech_ci NOT NULL AFTER `id_phrase`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'style' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_style' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_style` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_style` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'id_phrase' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_phrase` int(11) NOT NULL AFTER `id_style`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_style'
					, '', ', MODIFY `id_phrase` int(11) NOT NULL AFTER `id_style`'));
				WHEN 'css' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `css` text COLLATE utf8_czech_ci NOT NULL AFTER `id_phrase`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'text' AND _extra = '' AND _column_comment = '' AND after = 'id_phrase'
					, '', ', MODIFY `css` text COLLATE utf8_czech_ci NOT NULL AFTER `id_phrase`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `style`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `user` (
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
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_user` int(11) NOT NULL auto_increment FIRST, ADD `id_userGroup` int(11) AFTER `id_user`, ADD `username` varchar(50) COLLATE utf8_czech_ci AFTER `id_userGroup`, ADD `password` varchar(128) COLLATE utf8_czech_ci COMMENT \'SHA512\' AFTER `username`, ADD `firstName` varchar(50) COLLATE utf8_czech_ci AFTER `password`, ADD `lastName` varchar(50) COLLATE utf8_czech_ci AFTER `firstName`, ADD `eMail` varchar(50) COLLATE utf8_czech_ci AFTER `lastName`, ADD `hashCode` varchar(128) COLLATE utf8_czech_ci NOT NULL AFTER `eMail`, ADD `lastAccessDate` datetime NOT NULL AFTER `hashCode`, ADD `ip` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `lastAccessDate`, ADD `userAgent` text COLLATE utf8_czech_ci NOT NULL AFTER `ip`, ADD `countLoads` int(11) NOT NULL AFTER `userAgent`, ADD `noCookie` tinyint(4) NOT NULL AFTER `countLoads`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'user' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_user' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_user` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_user` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'id_userGroup' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_userGroup` int(11) AFTER `id_user`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_user'
					, '', ', MODIFY `id_userGroup` int(11) AFTER `id_user`'));
				WHEN 'username' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `username` varchar(50) COLLATE utf8_czech_ci AFTER `id_userGroup`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(50)' AND _extra = '' AND _column_comment = '' AND after = 'id_userGroup'
					, '', ', MODIFY `username` varchar(50) COLLATE utf8_czech_ci AFTER `id_userGroup`'));
				WHEN 'password' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `password` varchar(128) COLLATE utf8_czech_ci COMMENT \'SHA512\' AFTER `username`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(128)' AND _extra = '' AND _column_comment = 'SHA512' AND after = 'username'
					, '', ', MODIFY `password` varchar(128) COLLATE utf8_czech_ci COMMENT \'SHA512\' AFTER `username`'));
				WHEN 'firstName' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `firstName` varchar(50) COLLATE utf8_czech_ci AFTER `password`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(50)' AND _extra = '' AND _column_comment = '' AND after = 'password'
					, '', ', MODIFY `firstName` varchar(50) COLLATE utf8_czech_ci AFTER `password`'));
				WHEN 'lastName' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `lastName` varchar(50) COLLATE utf8_czech_ci AFTER `firstName`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(50)' AND _extra = '' AND _column_comment = '' AND after = 'firstName'
					, '', ', MODIFY `lastName` varchar(50) COLLATE utf8_czech_ci AFTER `firstName`'));
				WHEN 'eMail' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `eMail` varchar(50) COLLATE utf8_czech_ci AFTER `lastName`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(50)' AND _extra = '' AND _column_comment = '' AND after = 'lastName'
					, '', ', MODIFY `eMail` varchar(50) COLLATE utf8_czech_ci AFTER `lastName`'));
				WHEN 'hashCode' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `hashCode` varchar(128) COLLATE utf8_czech_ci NOT NULL AFTER `eMail`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(128)' AND _extra = '' AND _column_comment = '' AND after = 'eMail'
					, '', ', MODIFY `hashCode` varchar(128) COLLATE utf8_czech_ci NOT NULL AFTER `eMail`'));
				WHEN 'lastAccessDate' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `lastAccessDate` datetime NOT NULL AFTER `hashCode`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'datetime' AND _extra = '' AND _column_comment = '' AND after = 'hashCode'
					, '', ', MODIFY `lastAccessDate` datetime NOT NULL AFTER `hashCode`'));
				WHEN 'ip' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `ip` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `lastAccessDate`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'varchar(50)' AND _extra = '' AND _column_comment = '' AND after = 'lastAccessDate'
					, '', ', MODIFY `ip` varchar(50) COLLATE utf8_czech_ci NOT NULL AFTER `lastAccessDate`'));
				WHEN 'userAgent' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `userAgent` text COLLATE utf8_czech_ci NOT NULL AFTER `ip`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> 'utf8_czech_ci' AND _column_type = 'text' AND _extra = '' AND _column_comment = '' AND after = 'ip'
					, '', ', MODIFY `userAgent` text COLLATE utf8_czech_ci NOT NULL AFTER `ip`'));
				WHEN 'countLoads' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `countLoads` int(11) NOT NULL AFTER `userAgent`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'userAgent'
					, '', ', MODIFY `countLoads` int(11) NOT NULL AFTER `userAgent`'));
				WHEN 'noCookie' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `noCookie` tinyint(4) NOT NULL AFTER `countLoads`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'tinyint(4)' AND _extra = '' AND _column_comment = '' AND after = 'countLoads'
					, '', ', MODIFY `noCookie` tinyint(4) NOT NULL AFTER `countLoads`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `user`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `usergroup` (
  `id_userGroup` int(11) NOT NULL AUTO_INCREMENT,
  `id_phrase` int(11) NOT NULL,
  `id_userGroup_parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_userGroup`),
  KEY `Index_1` (`id_userGroup`),
  KEY `FK_ParentUserGroup` (`id_userGroup_parent`),
  CONSTRAINT `FK_ParentUserGroup` FOREIGN KEY (`id_userGroup_parent`) REFERENCES `usergroup` (`id_userGroup`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_userGroup` int(11) NOT NULL auto_increment FIRST, ADD `id_phrase` int(11) NOT NULL AFTER `id_userGroup`, ADD `id_userGroup_parent` int(11) AFTER `id_phrase`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'usergroup' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_userGroup' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_userGroup` int(11) NOT NULL auto_increment FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = 'auto_increment' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_userGroup` int(11) NOT NULL auto_increment FIRST'));
				WHEN 'id_phrase' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_phrase` int(11) NOT NULL AFTER `id_userGroup`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_userGroup'
					, '', ', MODIFY `id_phrase` int(11) NOT NULL AFTER `id_userGroup`'));
				WHEN 'id_userGroup_parent' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_userGroup_parent` int(11) AFTER `id_phrase`', IF(
						_column_default <=> NULL AND _is_nullable = 'YES' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_phrase'
					, '', ', MODIFY `id_userGroup_parent` int(11) AFTER `id_phrase`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `usergroup`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


CREATE TABLE IF NOT EXISTS `usergrouphaspermission` (
  `id_permission` int(11) NOT NULL,
  `id_userGroup` int(11) NOT NULL,
  PRIMARY KEY (`id_permission`,`id_userGroup`),
  KEY `Index_1` (`id_permission`),
  KEY `FK_UserGroupHasPermission` (`id_userGroup`),
  CONSTRAINT `FK_UserGroupHasPermission` FOREIGN KEY (`id_userGroup`) REFERENCES `usergroup` (`id_userGroup`),
  CONSTRAINT `FK_UserGroupHasPermission2` FOREIGN KEY (`id_permission`) REFERENCES `permission` (`id_permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT ', ADD `id_permission` int(11) NOT NULL FIRST, ADD `id_userGroup` int(11) NOT NULL AFTER `id_permission`';
	DECLARE columns CURSOR FOR SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'usergrouphaspermission' ORDER BY ORDINAL_POSITION;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name
				WHEN 'id_permission' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_permission` int(11) NOT NULL FIRST', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = ''
					, '', ', MODIFY `id_permission` int(11) NOT NULL FIRST'));
				WHEN 'id_userGroup' THEN
					SET add_columns = REPLACE(add_columns, ', ADD `id_userGroup` int(11) NOT NULL AFTER `id_permission`', IF(
						_column_default <=> NULL AND _is_nullable = 'NO' AND _collation_name <=> NULL AND _column_type = 'int(11)' AND _extra = '' AND _column_comment = '' AND after = 'id_permission'
					, '', ', MODIFY `id_userGroup` int(11) NOT NULL AFTER `id_permission`'));
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE `usergrouphaspermission`', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;


SELECT @adminer_alter;
-- 2012-07-13 13:25:26
