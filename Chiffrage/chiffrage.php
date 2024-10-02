<?php
$directory = __DIR__ . '/dataBase/';
$fichierCle = 'cle_stock.bin';

function chiffrerFichier($file) {
    $algorithme = "aes-256-cbc"; // Algorithme pour méthode de chiffrement par bloc
    $cle = openssl_random_pseudo_bytes(32); // Générer une clé aléatoire de 32 octets (256bits)
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($algorithme)); // Génération du vecteur d'initialisation

    $texteChiffre = openssl_encrypt(file_get_contents($file), $algorithme, $cle, OPENSSL_RAW_DATA, $iv);
    if ($texteChiffre === false) return "Erreur de chiffrement : $file\n";

    $sortie = "$file.enc";
    file_put_contents($sortie, base64_encode($iv . $texteChiffre));
    file_put_contents($GLOBALS['fichierCle'], base64_encode($cle) . ':' . base64_encode($iv));

    return "Chiffrement réussi : $sortie\n";
}
$files = glob($directory . '*.sql');
echo empty($files) ? "Aucun fichier SQL trouvé dans le répertoire $directory.\n" :
    array_reduce($files, fn($carry, $file) => $carry . chiffrerFichier($file), '');
?>
