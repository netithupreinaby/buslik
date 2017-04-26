CREATE TABLE IF NOT EXISTS `yen_resizer2_settings` (
	`NAME` VARCHAR(100) NOT NULL,
	`VALUE` VARCHAR(600) NOT NULL,
	PRIMARY KEY (`NAME`));

CREATE TABLE IF NOT EXISTS `yen_resizer2_sets` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`NAME` VARCHAR(100) NOT NULL,
	`w` INT(10) NOT NULL,
	`h` INT(10) NOT NULL,
	`q` INT(10),
	`wm` VARCHAR(1),
	`priority` VARCHAR(30),
	`watermark_settings` VARCHAR(800),
	`conv` VARCHAR(30),
	PRIMARY KEY (`id`));

CREATE TABLE IF NOT EXISTS `yen_resizer2_set_file` (
	`KEY` CHAR(32) NOT NULL,
	`SET_DIR` VARCHAR(32) NOT NULL,
	`FILE_ID` INT(18) NOT NULL,
	PRIMARY KEY (`KEY`, `SET_DIR`));
