/* QUERY 3 mostra i clienti che hanno fatto almeno una visita, il cui prezzo è il secondo più costoso*/

SELECT DISTINCT p.cod_fiscale, p.cognome, p.nome
FROM Paziente as p
	JOIN Visita as v ON v.paziente=p.cod_fiscale
	JOIN SezioneVisita as sv ON sv.id_visita=v.id_visita
	JOIN TipoVisita as tv ON sv.tipo_visita=tv.descrizione
WHERE tv.descrizione IN
(
		SELECT tv.descrizione
		FROM TipoVisita as tv
		WHERE tv.prezzo IN
		(
			SELECT MAX(tv.prezzo)
			FROM TipoVisita as tv
			WHERE tv.descrizione NOT IN
			(
				SELECT tv.descrizione
				FROM TipoVisita as tv
				WHERE tv.prezzo IN
				(
					SELECT MAX(tv.prezzo)
					FROM TipoVisita as tv
				)
			)
		)
);
