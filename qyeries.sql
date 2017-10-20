UPDATE `u2` SET `answers`=shows*level WHERE answers>=shows

UPDATE `u2` SET `shows`= answers/level WHERE answers=shows

SELECT * FROM `u2` WHERE answers>shows

SELECT * FROM `u2` WHERE id IN (4,16,24,31)

INSERT INTO `u2` (`id`,`shows`,`answers`) VALUES ('4',1,1),('24',1,0),('31',1,0),('16',1,0) on duplicate key update shows = shows + values(shows) and answers = answers + values(answers);

INSERT INTO `u2` (`id`,`shows`,`answers`) VALUES (4,1,1),(24,1,0),(31,1,0),(16,1,0) on duplicate key update shows = shows + values(shows), answers = answers + values(answers);

UPDATE `thesaurus` SET `foreign` = UPPER(`foreign`) Преобразует все записи столбца foreign в верхний регистр.http://www.sql.ru/forum/663047/perevod-v-verhniy-registr

SELECT * FROM `thesaurus` WHERE `foreign` LIKE '%’%' ORDER BY `id` DESC