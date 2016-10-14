/* QUERY 1 trovare le tipologie di visite che hanno avuto i due massimi numeri di richieste, mostrando anche il numero di volte in cui sono state richieste*/

DROP VIEW IF EXISTS query1_aux;
DROP VIEW IF EXISTS query1_aux2;

CREATE VIEW query1_aux AS
SELECT COUNT(*) AS cont, tv.descrizione
FROM TipoVisita as tv JOIN SezioneVisita as sv on tv.descrizione=sv.tipo_visita
GROUP BY tv.descrizione;

CREATE VIEW query1_aux2 AS
SELECT cont
FROM query1_aux
WHERE cont NOT IN 
(
	SELECT MAX(query1_aux.cont)
  	FROM query1_aux
);

CREATE VIEW query1 AS
SELECT tv.descrizione, count(*) as conteggio
FROM TipoVisita as tv JOIN SezioneVisita as sv on tv.descrizione=sv.tipo_visita
GROUP BY tv.descrizione
HAVING conteggio IN
(
	SELECT MAX(query1_aux.cont)
  	FROM query1_aux
) OR conteggio IN
(
	SELECT MAX(query1_aux2.cont) 
	FROM query1_aux2
);
