/*riceve i dati di una operazione ossia tipologia, un giorno e un orario e ritorna true se è disponibile
(cioè se ci sono medici non licenziati disponibili in quella data ora specializzati in quella tipologia), false altrimenti */
DELIMITER |
CREATE FUNCTION is_operazione_disponibile(tipologia VARCHAR(64), giorno DATE, orario TIME)
RETURNS BOOLEAN
BEGIN
DECLARE cont INT(4);
 
SELECT COUNT(*) INTO cont
FROM Specializzazione as s
WHERE s.tipo_visita = tipologia AND s.medico NOT IN (
    SELECT e.medico
    FROM Effettuazione as e 
		JOIN Visita as v ON (e.id_visita = v.id_visita)
    WHERE v.data = giorno AND v.ora = orario
    UNION
    SELECT m.cod_fiscale
    FROM Medico as m
    WHERE m.data_licenziamento IS NOT NULL
);
 
IF cont > 0 THEN
    RETURN TRUE;
ELSE
    RETURN FALSE;
END IF;
 
END
|
DELIMITER ;
