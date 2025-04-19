<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        $tasks = [
            [
                'judul' => 'Membuat ERD Project',
                'deskripsi' => 'Mendesain Entity Relationship Diagram untuk project baru',
                'deadline' => now()->addDays(3),
                'status' => 'tertunda',
                'id_user' => $user->id,
            ],
            [
                'judul' => 'Meeting dengan Client',
                'deskripsi' => 'Diskusi requirement project dengan client',
                'deadline' => now()->addDays(1),
                'status' => "tertunda",
                'id_user' => $user->id,
            ],
            [
                'judul' => 'Belajar Laravel',
                'deskripsi' => 'Mempelajari fitur-fitur baru Laravel 10',
                'deadline' => now()->addWeek(),
                'status' => "tertunda",
                'id_user' => $user->id,
            ],
            [
                'judul' => 'Code Review',
                'deskripsi' => 'Review kode dari tim development',
                'deadline' => now()->addDays(2),
                'status' => "tertunda",
                'id_user' => $user->id,
            ],
            [
                'judul' => 'Backup Database',
                'deskripsi' => 'Melakukan backup database production',
                'deadline' => now()->addDay(),
                'status' => "selesai",
                'id_user' => $user->id,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
