<?php
// Configuration
$to = "louca.cc26@gmail.com"; // Remplace par ton adresse
$subject = "Nouveau message du formulaire";

// Sécurisation des données
$name = htmlspecialchars(trim($_POST['name']));
$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
$message = htmlspecialchars(trim($_POST['message']));

// Vérification
if (!$name || !$email || !$message) {
    echo "Tous les champs sont requis et doivent être valides.";
    exit;
}

// Construction du message
$body = "Nom: $name\nEmail: $email\nMessage:\n$message";

// En-têtes
$headers = "From: $email\r\nReply-To: $email\r\n";

// Envoi
if (mail($to, $subject, $body, $headers)) {
    echo "Message envoyé avec succès.";
} else {
    echo "Erreur lors de l'envoi du message.";
}
?>