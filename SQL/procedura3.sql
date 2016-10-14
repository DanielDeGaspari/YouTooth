/*La procedura disponibile invoca la funzione is_operazione_disponibile(tipologia, giorno, orario) e restituisce in disp l'output di tale funzione*/
DELIMITER |
CREATE PROCEDURE disponibile(IN tipologia VARCHAR(64), giorno DATE, orario TIME, OUT disp BOOL)
BEGIN
	SET disp = is_operazione_disponibile(tipologia, giorno, orario);
END
|
DELIMITER ;

