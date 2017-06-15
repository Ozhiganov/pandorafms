START TRANSACTION;

DROP PROCEDURE IF EXISTS addcol_oum706;

delimiter '//'
CREATE PROCEDURE addcol_oum706() BEGIN
	IF NOT EXISTS (
		SELECT * FROM information_schema.columns WHERE table_name='treport_content' AND column_name='historical_db'
	) THEN
		ALTER TABLE treport_content ADD COLUMN historical_db tinyint(1) UNSIGNED NOT NULL default 0;
	END IF;
END;
//

delimiter ';'
CALL addcol_oum706();
DROP PROCEDURE addcol_oum706;

COMMIT;
