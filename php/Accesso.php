<?php
	require "Connect.php";
	$username=$_POST['username'];
	$password=SHA1($_POST['password']);

	if($username==TRUE && $password==TRUE)
		{
		$query="SELECT * 
				FROM Account a
			 	WHERE a.username='$username' AND a.pwd='$password' ";


		$risultato=mysqli_query($conn,$query) or die ("Query fallita" . mysqli_error($conn));
		if (mysqli_num_rows($risultato) !=1 )
				header('Location: Errore_login.php');
		else
			{
				$row=mysqli_fetch_row($risultato);
				$privilegi=$row[2];
				session_start();
				$_SESSION['username']=$username;
				$_SESSION['privilegi']=$privilegi;
				header('Location: ../index.php');
			}
		}
		else
			header('Location: Errore_login.php');
?>
