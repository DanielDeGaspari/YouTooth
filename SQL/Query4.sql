/*QUERY 4 mostrare i medici e le segretarie che sono anche pazienti dello studio e che non si fanno visitare da più di 5 anni */


/*ausiliaria: clienti che non si fanno piu vedere da 5 anni numero*/
DROP VIEW IF EXISTS query4_aux_clienti_non_recenti;
CREATE VIEW query4_aux_clienti_non_recenti AS
SELECT p.nome, p.cognome, p.cod_fiscale, max(v.data) as UltimaVisita
FROM Paziente as p JOIN Visita as v ON v.paziente=p.cod_fiscale
GROUP BY p.nome, p.cognome, p.cod_fiscale
HAVING UltimaVisita < DATE_SUB(CURRENT_DATE(), INTERVAL 4 YEAR);

CREATE VIEW query4 AS
SELECT DISTINCT m.cod_fiscale
FROM Medico AS m
WHERE m.cod_fiscale
IN (
	SELECT p.cod_fiscale
	FROM Paziente AS p
)
AND m.cod_fiscale
IN (
	SELECT query4_aux_clienti_non_recenti.cod_fiscale
	FROM query4_aux_clienti_non_recenti
)
UNION
SELECT DISTINCT s.cod_fiscale
FROM Segretaria AS s
WHERE s.cod_fiscale
IN (
	SELECT p.cod_fiscale
	FROM Paziente AS p
)
AND s.cod_fiscale
IN (
	SELECT query4_aux_clienti_non_recenti.cod_fiscale
	FROM query4_aux_clienti_non_recenti
);
