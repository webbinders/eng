UPDATE `u2` SET `answers`=shows*level WHERE answers>=shows

UPDATE `u2` SET `shows`= answers/level WHERE answers=shows

SELECT * FROM `u2` WHERE answers>shows

SELECT * FROM `u2` WHERE id IN (4,16,24,31)

INSERT INTO `u2` (`id`,`shows`,`answers`) VALUES ('4',1,1),('24',1,0),('31',1,0),('16',1,0) on duplicate key update shows = shows + values(shows) and answers = answers + values(answers);

INSERT INTO `u2` (`id`,`shows`,`answers`) VALUES (4,1,1),(24,1,0),(31,1,0),(16,1,0) on duplicate key update shows = shows + values(shows), answers = answers + values(answers);