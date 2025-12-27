<?php

namespace Database\Seeders;

use App\Models\StorageConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StorageConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StorageConfig::updateOrCreate(
            ['driver' => 'local'],
            [
                'name' => 'Local Default',
                'is_active' => true,
                'credentials' => null
            ]
        );
    }
}
