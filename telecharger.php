<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['youtube_url'])) {
        echo json_encode(['error' => 'URL manquante']);
        exit;
    }

    $url = escapeshellarg($_POST['youtube_url']);
    $python_path = __DIR__ . '/python/arold/bin/python3';
    $script_path = __DIR__ . '/python/telecharger.py';

    $command = "$python_path $script_path $url 2>&1";
    $output = shell_exec($command);
    
    // Rechercher le fichier téléchargé
    function extraireTitre($output) {
        $pattern = '/(?<=\/downloads\/)(.*?)(?=\.mp4)/';
        if (preg_match($pattern, $output, $matches)) {
            return $matches[1]; // Retourne le titre sans extension
        }
        return null;
    }

    $titre_video = extraireTitre($output);
    $file = '/var/www/html/sebastian.cafe/public_html/downloads/' . $titre_video . '.mp4';

    if (file_exists($file)) {
        echo json_encode([
            'success' => true,
            'titre_video' => $titre_video,
            'file_url' => '/downloads/' . basename($file)
        ]);
    } else {
        echo json_encode(['error' => 'Fichier introuvable']);
    }
}
?>