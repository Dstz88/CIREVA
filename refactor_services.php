<?php

$services = glob(__DIR__ . '/app/Services/*Service.php');
foreach ($services as $file) {
    $content = file_get_contents($file);
    
    // Replace findById with findOrFail and remove the null check block
    $pattern = '/\$([a-zA-Z0-9_]+)\s*=\s*\$this->([a-zA-Z0-9_]+)->findById\(([^)]+)\);\s*if\s*\(!\$[a-zA-Z0-9_]+\)\s*\{\s*throw\s+new\s+Exception\([^)]+\);\s*\}/s';
    $replacement = '\$$1 = $this->$2->findOrFail($3);';
    
    $content = preg_replace($pattern, $replacement, $content);
    
    file_put_contents($file, $content);
}

echo "Refactored services.";
