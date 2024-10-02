<?php
// Configuration des chemins, de la clé de chiffrement et de la base de données
$directory = __DIR__ . '/dataBaseSave/';
$key = 'mysecretkey12345';  // Utilisez une clé plus robuste en production
$iv = '1234567891011121';   // IV de 16 octets pour AES-128

// Informations de connexion à la base de données
include 'env.php';

// Création du nom de fichier pour la sauvegarde avec timestamp
$backupFile = $directory . 'backup_' . date('Y-m-d_H-i-s') . '.sql';

// Commande mysqldump pour exporter la base de données
$command = "mysqldump --user=$dbUser --password=$dbPass --host=$dbHost $dbName > $backupFile";

// Exécution de la commande de sauvegarde
exec($command, $output, $returnVar);
if ($returnVar !== 0) {
    die("Erreur lors de la sauvegarde de la base de données.\n");
}

echo "Sauvegarde de la base de données réussie : $backupFile\n";

// Vérification si OpenSSL est installé
if (!extension_loaded('openssl')) {
    die("L'extension OpenSSL doit être activée dans PHP.\n");
}

// Fonction pour chiffrer un fichier
function encryptFile($filePath, $key, $iv) {
    $fileContent = file_get_contents($filePath);
    if ($fileContent === false) {
        echo "Impossible de lire le fichier $filePath.\n";
        return false;
    }

    // Chiffrement avec AES-128-CBC
    $encryptedContent = openssl_encrypt($fileContent, 'AES-128-CBC', $key, 0, $iv);
    if ($encryptedContent === false) {
        echo "Le chiffrement a échoué pour $filePath.\n";
        return false;
    }

    // Sauvegarde du fichier chiffré
    $encryptedFilePath = $filePath . '.enc';
    if (file_put_contents($encryptedFilePath, $encryptedContent) === false) {
        echo "Impossible d'écrire le fichier chiffré pour $filePath.\n";
        return false;
    }

    echo "Fichier chiffré : $encryptedFilePath\n";
    return true;
}

// Chiffrement de la sauvegarde
encryptFile($backupFile, $key, $iv);

// Supprimer le fichier SQL non chiffré après chiffrement
unlink($backupFile);
echo "Fichier non chiffré supprimé : $backupFile\n";
