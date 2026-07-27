<?php
// Scaffold script to generate missing UI views
$views = [
    'admin/dashboard',
    'admin/organizers/index',
    'admin/spk/index',
    'admin/events/index',
    'admin/reports/index',

    'organizer/dashboard',
    'organizer/tickets/index',
    'organizer/bookings/index',
    'organizer/reports/index',

    'user/dashboard',
    'user/bookings/index',
];

$layout = '<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __(\'%s\') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-textPrimary">
                    <h3 class="text-lg font-medium mb-4">%s</h3>
                    <x-alert type="info">Halaman %s masih dalam tahap pengembangan (Sprint 7 UI Scaffold).</x-alert>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>';

foreach ($views as $view) {
    $title = ucwords(str_replace(['/', '-', 'index'], [' ', ' ', ''], $view));
    $content = sprintf($layout, $title, $title, $title);
    $path = __DIR__ . '/resources/views/' . $view . '.blade.php';

    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    file_put_contents($path, $content);
    echo "Created view: $view\n";
}
