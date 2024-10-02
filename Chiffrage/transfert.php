<?php
$directory = __DIR__ . '/dataBase/';
$directory2 = __DIR__ . '/Script/';

include 'env.php';
$remoteServer;
$remoteDir;
$port;

function transfererFichier($file, $remoteServer, $remoteDir, $port) {

    $commande = "scp -P $port $file $remoteServer:$remoteDir";
    exec($commande, $output, $return_var);

    if ($return_var === 0) {
        echo "Transfert réussi : $file vers $remoteServer:$remoteDir\n";
    } else {
        echo "Erreur de transfert : $file\n";
    }
}

$files = array_merge(glob($directory . '*.enc'), glob($directory2 . '*.bin'));

if (empty($files)) {
    echo "Aucun fichier chiffré trouvé dans le répertoire $directory.\n";
} else {
    foreach ($files as $file) {
        transfererFichier($file, $remoteServer, $remoteDir, $port);
    }
}
?>
