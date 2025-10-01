<!--
Fichiers inclus dans ce document :
1) index.html         -> Formulaire côté client
2) send_email.php     -> Script PHP utilisant PHPMailer (recommandé)
3) send_email_mail.php-> Alternative utilisant mail() (moins fiable)

Instructions d'utilisation (lire avant d'exécuter) :
- PHPMailer : installer via Composer (composer require phpmailer/phpmailer) ou inclure la librairie.
- Remplir les paramètres SMTP (hôte, utilisateur, mot de passe, port, chiffrement).
- Placer les fichiers sur un serveur web avec PHP (ex: hébergement mutualisé, XAMPP, MAMP).
- Protéger les données côté serveur (validations, prévention d'injection d'entêtes).

Ne pas oublier de remplacer les valeurs placeholder (EXEMPLE) par vos vrais identifiants SMTP ou adresse destinataire.
-->

<!-- ========================= index.html ========================= -->
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Formulaire d'envoi par e-mail</title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;background:#f5f7fa;padding:30px}
    .card{max-width:600px;margin:0 auto;background:white;padding:20px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.08)}
    label{display:block;margin:12px 0 6px;font-weight:600}
    input[type=text],input[type=email],textarea{width:100%;padding:10px;border:1px solid #d4d8dd;border-radius:6px}
    button{margin-top:14px;padding:10px 16px;border:0;border-radius:6px;background:#2563eb;color:white;font-weight:700}
    .note{font-size:0.9rem;color:#666;margin-top:8px}
  </style>
</head>
<body>
  <div class="card">
    <h2>Envoyer un message par e‑mail</h2>
    <form method="post" action="send_email.php" id="contactForm">
      <label for="name">Nom</label>
      <input type="text" id="name" name="name" required maxlength="100">

      <label for="email">Votre e‑mail</label>
      <input type="email" id="email" name="email" required maxlength="200">

      <label for="subject">Sujet</label>
      <input type="text" id="subject" name="subject" required maxlength="150">

      <label for="message">Message</label>
      <textarea id="message" name="message" rows="6" required maxlength="2000"></textarea>

      <button type="submit">Envoyer</button>
      <p class="note">Le formulaire enverra les données au script PHP qui va vous expédier un e‑mail via SMTP. Ne mettez pas de mot de passe en clair dans un fichier public.</p>
    </form>
  </div>
</body>
</html>


<!-- ========================= send_email.php (PHPMailer) ========================= -->
<?php
// send_email.php
// Recommandé : installer PHPMailer via Composer
// composer require phpmailer/phpmailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php'; // adapter si besoin

// Récupérer et nettoyer les données
function clean($v){
    return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
}

$name = isset($_POST['name']) ? clean($_POST['name']) : '';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$subject = isset($_POST['subject']) ? clean($_POST['subject']) : '';
$message = isset($_POST['message']) ? clean($_POST['message']) : '';

// Vérifications basiques
$errors = [];
if(!$name) $errors[] = 'Nom manquant.';
if(!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E‑mail invalide.';
if(!$subject) $errors[] = 'Sujet manquant.';
if(!$message) $errors[] = 'Message manquant.';

if(!empty($errors)){
    http_response_code(400);
    echo implode("<br>", $errors);
    exit;
}

// --- Paramètres SMTP (à adapter !) ---
$smtpHost = 'smtp.example.com'; // ex: smtp.gmail.com
$smtpPort = 587;               // 587 pour TLS, 465 pour SSL
$smtpUser = 'votre_smtp_user@example.com';
$smtpPass = 'VOTRE_MOT_DE_PASSE_SMTP';
$smtpSecure = 'tls'; // 'ssl' ou 'tls'

$destinataire = 'destinataire@domaine.com'; // adresse qui recevra le message

try {
    $mail = new PHPMailer(true);
    // Paramètres SMTP
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->SMTPSecure = $smtpSecure;
    $mail->Port = $smtpPort;

    // Expéditeur et destinataire
    $mail->setFrom($smtpUser, 'Formulaire Web');
    $mail->addAddress($destinataire);
    $mail->addReplyTo($email, $name);

    // Contenu
    $mail->isHTML(true);
    $mail->Subject = '[Formulaire] ' . $subject;
    $body  = "<p><strong>Nom :</strong> {$name}</p>";
    $body .= "<p><strong>E‑mail :</strong> {$email}</p>";
    $body .= "<p><strong>Message :</strong><br>" . nl2br($message) . "</p>";
    $mail->Body = $body;

    $mail->send();
    echo 'Message envoyé avec succès.';
} catch (Exception $e) {
    http_response_code(500);
    echo "Envoi échoué. Erreur: " . $mail->ErrorInfo;
}

?>


<!-- ========================= send_email_mail.php (alternative: mail()) ========================= -->
<?php
// Alternative simple : envoi via la fonction native mail().
// ATTENTION : la fonction mail() peut être bloquée sur certains hébergements.

// Récupérer et nettoyer
function clean2($v){ return trim(strip_tags($v)); }
$name = isset($_POST['name']) ? clean2($_POST['name']) : '';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$subject = isset($_POST['subject']) ? clean2($_POST['subject']) : '';
$message = isset($_POST['message']) ? clean2($_POST['message']) : '';

if(!$name || !$email || !$subject || !$message){
    http_response_code(400);
    echo 'Champs manquants.'; exit;
}

// Empêcher l'injection d'entêtes
if(preg_match('/[\r\n]/', $email) || preg_match('/[\r\n]/', $name)){
    http_response_code(400);
    echo 'Données invalides.'; exit;
}

$to = 'destinataire@domaine.com';
$headers = "From: {$name} <{$email}>\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$body = "Nom: {$name}\nEmail: {$email}\n\nMessage:\n{$message}\n";

if(mail($to, '[Formulaire] ' . $subject, $body, $headers)){
    echo 'Message envoyé (via mail()).';
} else {
    http_response_code(500);
    echo 'Échec de l\'envoi.';
}

?>