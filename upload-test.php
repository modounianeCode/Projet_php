<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Upload</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 50px auto; padding: 20px; }
        .result { margin: 20px 0; padding: 15px; border: 1px solid #ccc; border-radius: 5px; }
        .success { background: #d4edda; border-color: #28a745; }
        .error { background: #f8d7da; border-color: #dc3545; }
        .info { background: #d1ecf1; border-color: #17a2b8; }
        code { display: block; background: #f4f4f4; padding: 10px; margin: 10px 0; overflow-x: auto; }
    </style>
</head>
<body>

<h1>Test d'Upload d'Image</h1>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*" required>
    <button type="submit">Tester Upload</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['image'])) {
    echo '<div class="result info">';
    echo '<h2>📊 Infos $_FILES</h2>';
    
    $file = $_FILES['image'];
    echo '<code>name: ' . htmlspecialchars($file['name']) . '</code>';
    echo '<code>type: ' . htmlspecialchars($file['type']) . '</code>';
    echo '<code>size: ' . $file['size'] . ' bytes (' . round($file['size'] / 1024 / 1024, 2) . ' MB)</code>';
    echo '<code>error: ' . $file['error'] . '</code>';
    echo '<code>tmp_name: ' . htmlspecialchars($file['tmp_name']) . '</code>';
    
    echo '<h2>🔍 Vérifications</h2>';
    
    // Check error
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo '<div class="result error">❌ Erreur upload: ' . $file['error'] . '</div>';
    } else {
        echo '<div class="result success">✅ Pas d\'erreur upload</div>';
    }
    
    // Check size
    $max_size = 2 * 1024 * 1024;
    if ($file['size'] > $max_size) {
        echo '<div class="result error">❌ Fichier trop volumineux (' . round($file['size'] / 1024 / 1024, 2) . ' MB > 2 MB)</div>';
    } else {
        echo '<div class="result success">✅ Taille OK (' . round($file['size'] / 1024 / 1024, 2) . ' MB)</div>';
    }
    
    // Check if file exists and is readable
    if (!is_uploaded_file($file['tmp_name'])) {
        echo '<div class="result error">❌ Fichier temporaire n\'existe pas ou n\'est pas accessible</div>';
    } else {
        echo '<div class="result success">✅ Fichier temporaire accessible</div>';
    }
    
    // Check getimagesize
    if (is_uploaded_file($file['tmp_name'])) {
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            echo '<div class="result error">❌ getimagesize() a échoué - ce n\'est pas une image valide</div>';
        } else {
            echo '<div class="result success">✅ getimagesize() succès</div>';
            echo '<code>width: ' . $imageInfo[0] . '</code>';
            echo '<code>height: ' . $imageInfo[1] . '</code>';
            echo '<code>type: ' . $imageInfo[2] . '</code>';
            echo '<code>mime: ' . htmlspecialchars($imageInfo['mime']) . '</code>';
            
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (in_array($imageInfo['mime'], $allowed)) {
                echo '<div class="result success">✅ MIME type accepté: ' . $imageInfo['mime'] . '</div>';
            } else {
                echo '<div class="result error">❌ MIME type refusé: ' . $imageInfo['mime'] . '</div>';
            }
        }
    }
    
    // Check upload directory
    $uploadDir = dirname(__FILE__) . '/public/assets/images/produits/';
    if (is_dir($uploadDir)) {
        echo '<div class="result success">✅ Dossier upload existe: ' . htmlspecialchars($uploadDir) . '</div>';
        if (is_writable($uploadDir)) {
            echo '<div class="result success">✅ Dossier upload est writable</div>';
        } else {
            echo '<div class="result error">❌ Dossier upload n\'est pas writable</div>';
        }
    } else {
        echo '<div class="result error">❌ Dossier upload n\'existe pas: ' . htmlspecialchars($uploadDir) . '</div>';
    }
    
    // Test actual move_uploaded_file
    if (is_uploaded_file($file['tmp_name']) && is_writable($uploadDir)) {
        $dest = $uploadDir . 'test_' . uniqid() . '.jpg';
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            echo '<div class="result success">✅ Fichier uploadé avec succès: ' . htmlspecialchars(basename($dest)) . '</div>';
            unlink($dest); // Clean up
        } else {
            echo '<div class="result error">❌ move_uploaded_file() a échoué</div>';
        }
    }
    
    echo '</div>';
}

// Show PHP info
echo '<div class="result info">';
echo '<h2>⚙️ Config PHP</h2>';
echo '<code>post_max_size: ' . ini_get('post_max_size') . '</code>';
echo '<code>upload_max_filesize: ' . ini_get('upload_max_filesize') . '</code>';
echo '<code>max_file_uploads: ' . ini_get('max_file_uploads') . '</code>';
echo '</div>';
?>

</body>
</html>
