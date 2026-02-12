<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Locations (parent_id = null)
        $mainHall = Config::create([
            'group_code' => 'Location',
            'parent_id' => null,
            'value' => 'Main Hall',
            'descr' => 'Main hall for large events',
            'is_active' => true,
        ]);

        $sanctuary = Config::create([
            'group_code' => 'Location',
            'parent_id' => null,
            'value' => 'Sanctuary',
            'descr' => 'Main worship sanctuary',
            'is_active' => true,
        ]);

        $chapel = Config::create([
            'group_code' => 'Location',
            'parent_id' => null,
            'value' => 'Chapel',
            'descr' => 'Chapel for smaller gatherings',
            'is_active' => true,
        ]);

        $eagleKidz = Config::create([
            'group_code' => 'Location',
            'parent_id' => null,
            'value' => 'EagleKidz 1',
            'descr' => 'EagleKidz area 1',
            'is_active' => true,
        ]);

        // Create Floors under MAINHALL
        Config::create([
            'group_code' => 'Floor',
            'parent_id' => $mainHall->id,
            'value' => '1st Floor',
            'descr' => 'Floor 1 - Main Hall',
            'is_active' => true,
        ]);

        Config::create([
            'group_code' => 'Floor',
            'parent_id' => $mainHall->id,
            'value' => '2nd Floor Mezzanine',
            'descr' => 'Floor 2 Mezzanine - Main Hall',
            'is_active' => true,
        ]);

        Config::create([
            'group_code' => 'Floor',
            'parent_id' => $mainHall->id,
            'value' => '3rd Floor',
            'descr' => 'Floor 3 - Main Hall',
            'is_active' => true,
        ]);

        // Create Floors under SANCTUARY
        Config::create([
            'group_code' => 'Floor',
            'parent_id' => $sanctuary->id,
            'value' => '1st Floor',
            'descr' => 'Floor 1 - Sanctuary',
            'is_active' => true,
        ]);

        Config::create([
            'group_code' => 'Floor',
            'parent_id' => $sanctuary->id,
            'value' => '2nd Floor',
            'descr' => 'Floor 2 - Sanctuary',
            'is_active' => true,
        ]);

        // Create Floor under CHAPEL
        Config::create([
            'group_code' => 'Floor',
            'parent_id' => $chapel->id,
            'value' => '1st Floor',
            'descr' => 'Floor 1 - Chapel',
            'is_active' => true,
        ]);

        // Create Floor under EAGLEKIDZ 1
        Config::create([
            'group_code' => 'Floor',
            'parent_id' => $eagleKidz->id,
            'value' => '1st Floor',
            'descr' => 'Floor 1 - EagleKidz',
            'is_active' => true,
        ]);
    }
}
