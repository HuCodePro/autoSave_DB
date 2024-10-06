<?php
$directory = __DIR__ . '/dataBase/';
$fichierCle = '/var/data/Script/cle_stock.bin';

function dechiffrerFichier($fichierChiffre, $directory, $fichierCle) {
    $algorithme = "aes-256-cbc";

    // Vérification de l'existence du fichier clé et extraction de la clé et de l'IV
    if (!file_exists($fichierCle)) return printf("Fichier clé introuvable : %s\n", $fichierCle);
    [$cle, $iv] = array_map('base64_decode', explode(':', file_get_contents($fichierCle)));

    // Décodage du texte chiffré et extraction de l'IV
    if (!$texteChiffre = base64_decode(file_get_contents($fichierChiffre)))
        return printf("Erreur lors de la décodification : %s\n", $fichierChiffre);
    $ivExtract = substr($texteChiffre, 0, $ivLength = openssl_cipher_iv_length($algorithme));
    $texteChiffre = substr($texteChiffre, $ivLength);

    // Comparaison des IV et tentative de déchiffrement
    if ($iv !== $ivExtract) return printf("Erreur IV : %s\n", $fichierChiffre);
    $texteDechiffre = openssl_decrypt($texteChiffre, $algorithme, $cle, OPENSSL_RAW_DATA, $iv);

    // Écriture du fichier déchiffré ou gestion des erreurs
    $fichierDechiffre = $directory . basename($fichierChiffre, '.enc') . '_dechiffre.sql';
    $texteDechiffre ? file_put_contents($fichierDechiffre, $texteDechiffre) 
                    : printf("Erreur de déchiffrement : %s\n", $fichierChiffre);
    printf("Déchiffrement %s : %s\n", $texteDechiffre ? "réussi" : "échoué", $fichierDechiffre);
}

// Déchiffrer tous les fichiers .sql.enc dans le dossier
array_map(fn($file) => dechiffrerFichier($file, $directory, $fichierCle), glob($directory . '*.sql.enc'));

?>
