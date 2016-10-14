/*QUERY 5 mostra i medici che hanno effettuato visite a pazienti che hanno fatto SOLO estrazioni di denti del giudizio*/

/*ausiliaria: persone che hanno SOLO tolto denti del giudizio*/
DROP VIEW IF EXISTS query5_aux_solo_giudiziosi;
CREATE VIEW query5_aux_solo_giudiziosi AS
SELECT p.cod_fiscale
FROM Paziente as p
WHERE p.cod_fiscale NOT IN (
	SELECT p.cod_fiscale
	FROM Paziente as p JOIN Visita as v ON v.paziente=p.cod_fiscale
						JOIN SezioneVisita as sv ON sv.id_visita=v.id_visita
	WHERE sv.tipo_visita <> 'Estrazione dente del giudizio'
);

CREATE VIEW query5 AS
SELECT DISTINCT m.cod_fiscale, m.cognome, m.nome
FROM Medico AS m
	JOIN Effettuazione as e on m.cod_fiscale=e.medico
	JOIN Visita as v on v.id_visita=e.id_visita
	JOIN Paziente as p on p.cod_fiscale=v.paziente
WHERE p.cod_fiscale IN 
(
	SELECT query5_aux_solo_giudiziosi.cod_fiscale
	FROM query5_aux_solo_giudiziosi
);
