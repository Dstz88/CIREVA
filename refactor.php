<?php

$interfaces = glob(__DIR__ . '/app/Repositories/Contracts/*RepositoryInterface.php');
foreach ($interfaces as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'findOrFail') === false) {
        $content = preg_replace(
            '/public function findById\(int \$id\): \?([a-zA-Z]+);/',
            "\\0\n\n    /**\n     * Find a model by ID or throw an exception.\n     *\n     * @param int \$id\n     * @return \\1\n     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException\n     */\n    public function findOrFail(int \$id): \\1;",
            $content
        );
        file_put_contents($file, $content);
    }
}

$concretes = glob(__DIR__ . '/app/Repositories/*Repository.php');
foreach ($concretes as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'findOrFail') === false) {
        $content = preg_replace(
            '/public function findById\(int \$id\): \?([a-zA-Z]+)\s*\{\s*return ([a-zA-Z]+)::find\(\$id\);\s*\}/',
            "\\0\n\n    /**\n     * Find a model by ID or throw an exception.\n     *\n     * @param int \$id\n     * @return \\1\n     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException\n     */\n    public function findOrFail(int \$id): \\1\n    {\n        return \\2::findOrFail(\$id);\n    }",
            $content
        );
        file_put_contents($file, $content);
    }
}

echo "Refactored repositories.";
