<?php

use App\Models\User;
use App\Models\Notification;

foreach (User::all() as $user) {
    Notification::firstOrCreate([
        'user_id' => $user->id,
        'title' => 'Selamat datang di CIREVA!',
    ], [
        'message' => 'Jelajahi berbagai event budaya Cirebon yang menarik.',
        'is_read' => false,
    ]);

    Notification::firstOrCreate([
        'user_id' => $user->id,
        'title' => 'Pemberitahuan Akun',
    ], [
        'message' => 'Lengkapi profil Anda untuk pengalaman pemesanan tiket yang lebih lancar.',
        'is_read' => false,
    ]);
}

echo "Notifications seeded successfully!\n";
