/*FUNCTION dato in input un codice fiscale, trovare la somma totale di quanto hanno speso nelle visite */
DELIMITER |
CREATE FUNCTION totali(cf VARCHAR(64))
RETURNS NUMERIC
BEGIN
DECLARE tot NUMERIC;

SELECT SUM(tv.prezzo) INTO tot
FROM Paziente as p
	JOIN Visita as v ON v.paziente=p.cod_fiscale
	JOIN SezioneVisita as sv ON sv.id_visita=v.id_visita
	JOIN TipoVisita as tv ON tv.descrizione = sv.tipo_visita
WHERE p.cod_fiscale = cf;

RETURN tot;
END
|
DELIMITER ;
