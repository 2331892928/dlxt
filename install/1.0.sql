SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
DROP TABLE IF EXISTS `ymdl_config`;
CREATE TABLE `ymdl_config` (
  `id` int(8) NOT NULL,
  `code_key` varchar(12) NOT NULL,
  `code` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `ymdl_config` (`id`, `code_key`, `code`) VALUES
(1, 'title', '湮灭短链'),
(2, 'title1', '湮灭短链'),
(3, 'title2', '湮灭短链'),
(4, 'cookie', ''),
(5, 'time', '3'),
(6, 'template', 'default');
DROP TABLE IF EXISTS `ymdl_data`;
CREATE TABLE `ymdl_data` (
  `id` int(8) NOT NULL,
  `code` varchar(6) NOT NULL,
  `cl` text NOT NULL,
  `title` text,
  `title1` text,
  `title2` text,
  `data` varchar(32) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `ymdl_config`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ymdl_data`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ymdl_config`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `ymdl_data`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
COMMIT;