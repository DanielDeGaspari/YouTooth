/*QUERY 6 Mostra la spesa totale di ogni cliente,  mostrando cognome e nome del cliente, il codice
      fiscale e la sua spesa, ordinati per cognome e nome.*/
SELECT p.nome, p.cognome, p.cod_fiscale, totali(p.cod_fiscale)
FROM Paziente AS p
ORDER BY p.cognome, p.nome ASC;
