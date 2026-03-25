<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$nom       = htmlspecialchars(trim($_POST['nom'] ?? ''));
$entreprise = htmlspecialchars(trim($_POST['entreprise'] ?? ''));
$email     = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$message   = htmlspecialchars(trim($_POST['message'] ?? ''));

if (!$nom || !$email || !$message) {
    echo json_encode(['success' => false, 'message' => 'Champs requis manquants.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Adresse email invalide.']);
    exit;
}

$destinataire = 'jeremie.levesque@seanetik.com, maxime.leblanc@seanetik.com, info@seanetik.com';
$sujet        = "Nouveau message — seanetik.ca ($nom)";

$corps = "Nouveau message reçu via le site seanetik.ca\n\n";
$corps .= "Nom        : $nom\n";
$corps .= "Entreprise : $entreprise\n";
$corps .= "Email      : $email\n";
$corps .= "Message    :\n$message\n";

$entetes  = "From: noreply@seanetik.ca\r\n";
$entetes .= "Reply-To: $email\r\n";
$entetes .= "X-Mailer: PHP/" . phpversion();

$envoye = mail($destinataire, $sujet, $corps, $entetes);

if ($envoye) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'envoi.']);
}
