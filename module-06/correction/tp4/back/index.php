<?php

function getDockerSecret(string $name): string
{
	$path = '/run/secrets/' . $name;

	if (! is_readable($path)) {
		throw new RuntimeException("Secret not readable: {$name}");
	}

	$value = file_get_contents($path);

	if ($value === false) {
		throw new RuntimeException("Failed to read secret: {$name}");
	}

	return trim($value);
}


$APP_ENV  = getenv('APP_ENV');
$front_port = getenv('FRONT_PORT');
$allowed_origin = "http://localhost:{$front_port}";


//Gestion des origines, merci de passer par l'application cliente.
if ($APP_ENV && $APP_ENV === 'production') {
	// Récupère l'origine de la requête
	$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

	// Si l'origine est présente mais n'est pas la bonne, on bloque
	if (!empty($origin) && $origin !== $allowed_origin) {
		header('HTTP/1.1 403 Forbidden');
		die("Accès interdit : origine non autorisée.");
	}

	// Optionnel : Bloquer aussi les accès directs (barre d'adresse)
	if (empty($origin)) {
		// Si vous voulez interdire l'accès direct via le navigateur
		header('HTTP/1.1 403 Forbidden');
		die("Accès direct interdit.");
	}
}

// Moduler la SOP avec la CORS policy, plus flexible en developement pour tester directement le backend au besoin.
header("Access-Control-Allow-Origin: $allowed_origin");
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	http_response_code(204); // Répond avec un code 204 sans contenu
	exit;
}

header("Content-Type: application/json");

// Configuration accès à la base de donnée (a deplacer dans env)
$dbname   = getenv('DB_NAME');
$dbhost   = getenv('DB_HOST');
$dbport   = getenv('DB_PORT');
$dbuser   = getenv('DB_USER');
$dbpasswd = getDockerSecret('db-password');

try {

	// Ping la base de données
	$pdo = new PDO(
		"mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4",
		$dbuser,
		$dbpasswd,
		array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		)
	);

	// Requête du jeu de données en base
	$stmt = $pdo->prepare('SELECT name FROM Student');
	$stmt->execute();
	$students = $stmt->fetchAll(PDO::FETCH_COLUMN);


	if ($APP_ENV && $APP_ENV == 'developement') {
		// Envoi d'un email (via serveur mailhog vers le mailcatcher)
		mail(
			'foo.bar@app.com',
			'test',
			'Données en base: ' . implode(', ', $students),
			'To: foo.bar@app.com'
		);
		echo json_encode(array('La connexion à la base de données a réussi ! Allez <a href="http://localhost:8025">checker vos emails</a>'));
	} else {
		// Envoi de mail configuré pour la prod.
		echo json_encode(array('La liste des étudiant·es a été envoyé par email.'));
	}
} catch (PDOException $e) {

	echo json_encode(array('La connexion a la base de données a échoué :('));
}

exit;
