<?php

require __DIR__ . '/vendor/autoload.php';

$reposDir = __DIR__ . '/app/Repositories';
$contractsDir = $reposDir . '/Contracts';

if (!is_dir($contractsDir)) {
    mkdir($contractsDir, 0755, true);
}

$files = glob($reposDir . '/*Repository.php');

$bindings = [];

foreach ($files as $file) {
    $className = basename($file, '.php');
    $fullClassName = "\\App\\Repositories\\" . $className;
    
    if (!class_exists($fullClassName)) {
        echo "Class $fullClassName not found.\n";
        continue;
    }
    
    $interfaceName = $className . 'Interface';
    $interfacePath = $contractsDir . '/' . $interfaceName . '.php';
    
    // Extract methods using Reflection
    $reflection = new ReflectionClass($fullClassName);
    $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
    
    $interfaceMethods = [];
    $uses = [];
    
    foreach ($methods as $method) {
        if ($method->isConstructor() || $method->isDestructor() || $method->isStatic()) {
            continue;
        }
        
        // Get method signature
        $methodDoc = $method->getDocComment() ? $method->getDocComment() . "\n    " : "";
        
        $params = [];
        foreach ($method->getParameters() as $param) {
            $paramStr = '';
            if ($param->hasType()) {
                $type = $param->getType();
                if ($type instanceof ReflectionNamedType) {
                    $typeName = $type->getName();
                    if (!$type->isBuiltin()) {
                        // Extract class name
                        $parts = explode('\\', $typeName);
                        $shortName = end($parts);
                        $uses[] = $typeName;
                        $typeName = $shortName;
                    }
                    $paramStr .= ($type->allowsNull() ? '?' : '') . $typeName . ' ';
                } elseif ($type instanceof ReflectionUnionType) {
                    // complex, skip union types for simple generator
                    $paramStr .= $type . ' ';
                }
            }
            $paramStr .= '$' . $param->getName();
            
            if ($param->isDefaultValueAvailable()) {
                $default = var_export($param->getDefaultValue(), true);
                $paramStr .= ' = ' . $default;
            }
            
            $params[] = $paramStr;
        }
        
        $returnType = '';
        if ($method->hasReturnType()) {
            $type = $method->getReturnType();
            if ($type instanceof ReflectionNamedType) {
                $typeName = $type->getName();
                if (!$type->isBuiltin()) {
                    $parts = explode('\\', $typeName);
                    $shortName = end($parts);
                    $uses[] = $typeName;
                    $typeName = $shortName;
                }
                $returnType = ': ' . ($type->allowsNull() ? '?' : '') . $typeName;
            }
        }
        
        $interfaceMethods[] = $methodDoc . "public function " . $method->getName() . "(" . implode(', ', $params) . ")" . $returnType . ";";
    }
    
    $uses = array_unique($uses);
    $useStatements = '';
    foreach ($uses as $use) {
        if (strpos($use, 'App\\') === 0 || strpos($use, 'Illuminate\\') === 0) {
            $useStatements .= "use $use;\n";
        }
    }
    
    $interfaceContent = "<?php\n\nnamespace App\\Repositories\\Contracts;\n\n";
    if (!empty($useStatements)) {
        $interfaceContent .= $useStatements . "\n";
    }
    
    $interfaceContent .= "interface $interfaceName\n{\n    " . implode("\n\n    ", $interfaceMethods) . "\n}\n";
    
    file_put_contents($interfacePath, $interfaceContent);
    echo "Generated: $interfaceName\n";
    
    // Modify original repository to implement interface
    $repoContent = file_get_contents($file);
    if (strpos($repoContent, 'implements ' . $interfaceName) === false) {
        // Add use statement for interface
        $useInterface = "use App\\Repositories\\Contracts\\$interfaceName;";
        $repoContent = preg_replace('/namespace App\\\\Repositories;/', "namespace App\\Repositories;\n\n$useInterface", $repoContent);
        
        // Add implements
        $repoContent = preg_replace('/class ' . $className . '(\s*)/', "class $className implements $interfaceName\n{\n", $repoContent);
        
        // Fix double brace if any
        $repoContent = str_replace("{\n{", "{", $repoContent);

        file_put_contents($file, $repoContent);
        echo "Updated: $className\n";
    }
    
    $bindings[$interfaceName] = $className;
}

// Generate Service Provider
$providerContent = "<?php\n\nnamespace App\\Providers;\n\nuse Illuminate\\Support\\ServiceProvider;\n\nclass RepositoryServiceProvider extends ServiceProvider\n{\n    /**\n     * Register services.\n     */\n    public function register(): void\n    {\n";

foreach ($bindings as $interface => $implementation) {
    $providerContent .= "        \$this->app->bind(\n            \\App\\Repositories\\Contracts\\$interface::class,\n            \\App\\Repositories\\$implementation::class\n        );\n";
}

$providerContent .= "    }\n\n    /**\n     * Bootstrap services.\n     */\n    public function boot(): void\n    {\n        //\n    }\n}\n";

file_put_contents(__DIR__ . '/app/Providers/RepositoryServiceProvider.php', $providerContent);
echo "Generated: RepositoryServiceProvider\n";

// Append to bootstrap/providers.php
$providersFile = __DIR__ . '/bootstrap/providers.php';
$providersContent = file_get_contents($providersFile);
if (strpos($providersContent, 'App\\Providers\\RepositoryServiceProvider::class') === false) {
    $providersContent = preg_replace('/return \[/', "return [\n    App\\Providers\\RepositoryServiceProvider::class,", $providersContent);
    file_put_contents($providersFile, $providersContent);
    echo "Registered RepositoryServiceProvider in bootstrap/providers.php\n";
}

echo "Done!\n";
