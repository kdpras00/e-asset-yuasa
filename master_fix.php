<?php
$directory = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$iterator = new RecursiveIteratorIterator($directory);
$regex = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$cardReplacements = [
    'rounded-2xl shadow-sm border border-gray-100' => 'rounded-3xl',
    'rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100' => 'rounded-3xl',
    'rounded-2xl border border-gray-100 shadow-sm' => 'rounded-3xl',
];

foreach ($regex as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $original = $content;
    
    // Fonts
    $content = str_replace('family=Outfit:wght', 'family=Poppins:wght', $content);
    if (strpos($path, 'dashboard/index.blade.php') !== false) {
        $content = str_replace("'Outfit', sans-serif", "'Poppins', sans-serif", $content);
        // Dashboard grid fix
        $content = str_replace('<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">', '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">', $content);
        $content = str_replace('<!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">', '<!-- Charts -->
    <div class="w-full">', $content);
        $content = str_replace('<div class="bg-white p-6 rounded-3xl lg:col-span-2">', '<div class="bg-white p-6 rounded-3xl w-full">', $content);
        // Sometimes the card replacements haven't run on dashboard yet, so handle the old classes
        $content = str_replace('<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2">', '<div class="bg-white p-6 rounded-3xl w-full">', $content);
    }
    
    // Cards
    $content = str_replace(array_keys($cardReplacements), array_values($cardReplacements), $content);
    
    // Inputs: fix missing border, but use border-gray-200 so it's not too thick/dark
    $content = preg_replace('/(?<!\bborder\s)(?<!\bborder-2\s)\bborder-(gray|yellow|blue|red)-([0-9]{3})\b/', 'border border-$1-200', $content);
    
    // Colored spans removal
    $content = preg_replace('/<div class="absolute top-0 w-full h-2 bg-gradient-to-r[^"]+"><\/div>\s*/', '', $content);
    
    if ($content !== $original) {
        file_put_contents($path, $content);
    }
}
echo "Master fix completed.\n";
