<?php
// session_start();
session_start();



$db = new PDO('mysql:host=db:3306;dbname=formation_members;charset=utf8','root', 'password');


if(isset($_SESSION['connect'])){
	header('location: index.php');
	exit();
}

// require('src/connection.php');

// CONNEXION
if(!empty($_POST['email']) && !empty($_POST['password'])){

	// VARIABLES
	$email 		= $_POST['email'];
	$password 	= $_POST['password'];
	$error		= 1;

	// CRYPTER LE PASSWORD
	$password = "aq1".sha1($password."1254")."25";

	echo $password;

	$req = $db->prepare('SELECT * FROM users WHERE email = ?');
	$req->execute(array($email));

	while($user = $req->fetch()){

		if($password == $user['password']){
			$error = 0;
			$_SESSION['connect'] = 1;
			$_SESSION['pseudo']	 = $user['pseudo'];

			if(isset($_POST['connect'])) {
				setcookie('log', $user['secret'], time() + 365*24*3600, '/', null, false, true);
			}

			header('location: connection.php?success=1');
			exit();
		}

	}

	if($error == 1){
		header('location: connection.php?error=1');
		exit();
	}

}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Connexion</title>
	<link rel="stylesheet" type="text/css" href="design/default.css">
</head>
<style>'
html {
	font-family: arial;
	font-size: 18px;
	background-color: #ECECEC;
}

body {
	padding: 0;
	margin: 0;
}

button {
	background-color: #D64541;
	color: white;
	border: 0;
	cursor: pointer;
	padding: 5px;
}

input {
	border: 1px solid black;
	padding: 5px 10px;
	margin-left: 10px;
}

a {
	text-decoration: none;
	color: red;
}

header {
	text-align: center;
	background-color: #2C3E50;
	padding: 10px;
}

header h1 {
	color: #FDE3A7;
	margin: 0;
}

* {
	box-sizing: border-box;
}

form {
	text-align: left;
	display: inline-block;
}

#form {
	text-align: center;
}

#info {
	text-align: center;
}

#error {
	background-color: red;
	color: white;
	text-align: center;
	padding: 5px;
}

#success {
	background-color: green;
	color: white;
	text-align: center;
	padding: 5px;
}

#button {
	text-align: center;
	margin-top: 5px;
}

.container {
	width: 500px;
	margin: 0 auto;
	padding: 15px;
}'
</style>
<body>
	<header>
		<h1>Connexion</h1>
	</header>

	<div class="container">
		<p id="info">Bienvenue sur mon site,si vous n'êtes pas inscrit, <a href="index.php">inscrivez-vous.</a></p>
	 	
		<?php
			if(isset($_GET['error'])){
				echo'<p id="error">Nous ne pouvons pas vous authentifier.</p>';
			}
			else if(isset($_GET['success'])){
				echo'<p id="success">Vous êtes maintenant connecté.</p>';
			}
		?>

	 	<div id="form">
			<form method="POST" action="connection.php">
				<table>
					<tr>
						<td>Email</td>
						<td><input type="email" name="email" placeholder="Ex : example@google.com" required></td>
					</tr>
					<tr>
						<td>Mot de passe</td>
						<td><input type="password" name="password" placeholder="Ex : ********" required ></td>
					</tr>
				</table>
				<p><label><input type="checkbox" name="connect" checked>Connexion automatique</label></p>
				<div id="button">
					<button type='submit'>Connexion</button>
				</div>
			</form>
		</div>
	</div>
</body>
</html>