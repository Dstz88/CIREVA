<?php

$controllers = glob(__DIR__ . '/app/Http/Controllers/*.php');

foreach ($controllers as $file) {
    if (basename($file) === 'Controller.php' || basename($file) === 'ProfileController.php' || basename($file) === 'eventController.php') continue;

    $content = file_get_contents($file);

    // Replace JsonResponse with View|JsonResponse or just remove return type hint
    $content = preg_replace('/public function (index|create|show|edit)\((.*?)\):\s*JsonResponse/', 'public function $1($2)', $content);
    $content = preg_replace('/public function (store|update|destroy|approve|reject|verify|submit|publish|sign)\((.*?)\):\s*JsonResponse/', 'public function $1($2)', $content);

    // Simple replacement for index
    // $content = preg_replace('/return response\(\)->json\(\[\'message\' => \'(.*?) endpoint\.\'\]\);/', 'return view("stub", ["message" => "$1"]);', $content);

    file_put_contents($file, $content);
}

echo "Type hints removed.\n";
