<?php

namespace Database\Seeders;

use App\Models\Files;
use Illuminate\Database\Seeder;

class FileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $files = [
            [
                'file_name' => 'Test-file-3UzBpOomOz8WI6np5vlc8r27152sNQWPKiPsoEgE.png',
                'mime_type' => 'image/png',
                'size' => 50713,
                'entity_type' => 'document',
                'author_id' => 1,
                'storage_path' => '/1/document/',
                'storage_name' => '3UzBpOomOz8WI6np5vlc8r27152sNQWPKiPsoEgE.png',
                'link' => 'https://dev.storage.docudots.com/2/document/3UzBpOomOz8WI6np5vlc8r27152sNQWPKiPsoEgE.png',
            ],
            [
                'file_name' => 'Test-file-cdvdvdvdvdxvcxvxcvcxv.png',
                'mime_type' => 'image/png',
                'size' => 50713,
                'entity_type' => 'document',
                'author_id' => 1,
                'storage_path' => '/1/document/',
                'storage_name' => 'cdvdvdvdvdxvcxvxcvcxv.png',
                'link' => 'https://dev.storage.docudots.com/1/document/cdvdvdvdvdxvcxvxcvcxv.png',
            ],
        ];

        foreach ($files as $file) {
            Files::firstOrCreate(
                ['file_name' => $file['file_name']],
                $file,
            );
        }
    }
}
