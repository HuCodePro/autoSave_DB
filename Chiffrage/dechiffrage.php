<?php
$directory = __DIR__ . '/dataBase/';
$fichierCle = 'cle_stock.bin';

function dechiffrerFichier($fichierChiffre, $fichierCle) {
    $algorithme = "aes-256-cbc";
    list($cle, $iv) = array_map('base64_decode', explode(':', file_get_contents($fichierCle)));

    $texteChiffre = file_get_contents($fichierChiffre);
    if ($texteChiffre === false) {
        echo "Erreur lors de la lecture du fichier chiffré : $fichierChiffre\n";
        return;
    }

    $texteChiffre = base64_decode($texteChiffre);
    if ($texteChiffre === false) {
        echo "Erreur lors de la décodification du texte chiffré : $fichierChiffre\n";
        return;
    }

    // Extraire l'IV et le texte chiffré
    $ivLength = openssl_cipher_iv_length($algorithme);
    $iv = substr($texteChiffre, 0, $ivLength);
    $texteChiffre = substr($texteChiffre, $ivLength);

    $fichierDechiffre = $directory . basename($fichierChiffre, '.sql.enc') . '_dechiffre.sql';

    // Déchiffrement avec OPENSSL_RAW_DATA
    $texteDechiffre = openssl_decrypt($texteChiffre, $algorithme, $cle, OPENSSL_RAW_DATA, $iv);
    
    if ($texteDechiffre !== false) {
        file_put_contents($fichierDechiffre, $texteDechiffre);
        echo "Déchiffrement réussi : $fichierDechiffre\n";
    } else {
        echo "Erreur de déchiffrement : $fichierChiffre\n";
    }
}
foreach (glob($directory . '*.sql.enc') as $file) {
    dechiffrerFichier($file, $fichierCle);
}
?>
