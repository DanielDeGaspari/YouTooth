/*La funzione restituisce il numero di visite prenotate con il medico medico, in un periodo successivo
  alla data di licenziamento di medico*/
DELIMITER |
CREATE FUNCTION prenotazioni_future (medico CHAR(16), giorno_licenziamento DATE)
RETURNS INT
BEGIN
DECLARE conteggio INT;
SET conteggio = 0;
    SELECT count(*) INTO conteggio
    FROM Medico as m JOIN Effettuazione as e ON m.cod_fiscale=e.medico
                    JOIN SezioneVisita as sv ON (e.tipo_visita=sv.tipo_visita AND e.id_visita=sv.id_visita)
                    JOIN Visita as v ON v.id_visita=sv.id_visita
    WHERE v.data > giorno_licenziamento AND m.cod_fiscale=medico;
RETURN conteggio;
END
|
DELIMITER ;
