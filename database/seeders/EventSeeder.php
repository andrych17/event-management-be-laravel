<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Config;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all locations and their floors by value
        $mainHall = Config::where('group_code', 'Location')->where('value', 'Main Hall')->first();
        $mainHall1F = Config::where('group_code', 'Floor')->where('parent_id', $mainHall->id)->where('value', '1st Floor')->first();
        $mainHall2M = Config::where('group_code', 'Floor')->where('parent_id', $mainHall->id)->where('value', '2nd Floor Mezzanine')->first();
        $mainHall3F = Config::where('group_code', 'Floor')->where('parent_id', $mainHall->id)->where('value', '3rd Floor')->first();

        $sanctuary = Config::where('group_code', 'Location')->where('value', 'Sanctuary')->first();
        $sanctuary1F = Config::where('group_code', 'Floor')->where('parent_id', $sanctuary->id)->where('value', '1st Floor')->first();
        $sanctuary2F = Config::where('group_code', 'Floor')->where('parent_id', $sanctuary->id)->where('value', '2nd Floor')->first();

        $chapel = Config::where('group_code', 'Location')->where('value', 'Chapel')->first();
        $chapel1F = Config::where('group_code', 'Floor')->where('parent_id', $chapel->id)->where('value', '1st Floor')->first();

        $eagleKidz = Config::where('group_code', 'Location')->where('value', 'EagleKidz 1')->first();
        $eagleKidz1F = Config::where('group_code', 'Floor')->where('parent_id', $eagleKidz->id)->where('value', '1st Floor')->first();

        // Get the start date (today)
        $startDate = Carbon::now()->startOfDay();

        // Generate events for the next 7 days
        $events = [
            // Day 1 (Today - Minggu)
            [
                'title' => 'Ibadah Umum Minggu Pagi',
                'location_id' => $sanctuary->id,
                'floor_id' => $sanctuary1F->id,
                'event_start_datetime' => $startDate->copy()->setTime(8, 0),
                'event_end_datetime' => $startDate->copy()->setTime(10, 0),
                'description' => 'Ibadah umum Minggu pagi dengan khotbah dan pujian',
            ],
            [
                'title' => 'Ibadah Umum Minggu Sore',
                'location_id' => $sanctuary->id,
                'floor_id' => $sanctuary1F->id,
                'event_start_datetime' => $startDate->copy()->setTime(17, 0),
                'event_end_datetime' => $startDate->copy()->setTime(19, 0),
                'description' => 'Ibadah umum Minggu sore dengan pelayanan firman',
            ],
            [
                'title' => 'Sekolah Minggu',
                'location_id' => $eagleKidz->id,
                'floor_id' => $eagleKidz1F->id,
                'event_start_datetime' => $startDate->copy()->setTime(8, 0),
                'event_end_datetime' => $startDate->copy()->setTime(10, 0),
                'description' => 'Sekolah Minggu untuk anak usia 3-12 tahun',
            ],
            [
                'title' => 'Pemuda Remaja',
                'location_id' => $mainHall->id,
                'floor_id' => $mainHall2M->id,
                'event_start_datetime' => $startDate->copy()->setTime(15, 0),
                'event_end_datetime' => $startDate->copy()->setTime(17, 0),
                'description' => 'Persekutuan pemuda dan remaja',
            ],

            // Day 2 (Senin)
            [
                'title' => 'Doa Pagi',
                'location_id' => $chapel->id,
                'floor_id' => $chapel1F->id,
                'event_start_datetime' => $startDate->copy()->addDay()->setTime(6, 0),
                'event_end_datetime' => $startDate->copy()->addDay()->setTime(7, 0),
                'description' => 'Doa pagi bersama jemaat',
            ],
            [
                'title' => 'Persekutuan Wanita',
                'location_id' => $sanctuary->id,
                'floor_id' => $sanctuary2F->id,
                'event_start_datetime' => $startDate->copy()->addDay()->setTime(10, 0),
                'event_end_datetime' => $startDate->copy()->addDay()->setTime(12, 0),
                'description' => 'Persekutuan dan pendalaman Alkitab untuk wanita',
            ],
            [
                'title' => 'Latihan Paduan Suara',
                'location_id' => $sanctuary->id,
                'floor_id' => $sanctuary1F->id,
                'event_start_datetime' => $startDate->copy()->addDay()->setTime(19, 0),
                'event_end_datetime' => $startDate->copy()->addDay()->setTime(21, 0),
                'description' => 'Latihan rutin paduan suara untuk ibadah Minggu',
            ],

            // Day 3 (Selasa)
            [
                'title' => 'Persekutuan Pria',
                'location_id' => $mainHall->id,
                'floor_id' => $mainHall1F->id,
                'event_start_datetime' => $startDate->copy()->addDays(2)->setTime(19, 0),
                'event_end_datetime' => $startDate->copy()->addDays(2)->setTime(21, 0),
                'description' => 'Persekutuan dan pembinaan untuk kaum pria',
            ],
            [
                'title' => 'Kelas Kreativitas Anak',
                'location_id' => $eagleKidz->id,
                'floor_id' => $eagleKidz1F->id,
                'event_start_datetime' => $startDate->copy()->addDays(2)->setTime(15, 0),
                'event_end_datetime' => $startDate->copy()->addDays(2)->setTime(17, 0),
                'description' => 'Kelas seni dan kreativitas untuk anak-anak',
            ],
            [
                'title' => 'Rapat Komisi Penginjilan',
                'location_id' => $chapel->id,
                'floor_id' => $chapel1F->id,
                'event_start_datetime' => $startDate->copy()->addDays(2)->setTime(19, 0),
                'event_end_datetime' => $startDate->copy()->addDays(2)->setTime(21, 0),
                'description' => 'Rapat koordinasi kegiatan penginjilan',
            ],

            // Day 4 (Rabu)
            [
                'title' => 'Ibadah Tengah Minggu',
                'location_id' => $sanctuary->id,
                'floor_id' => $sanctuary1F->id,
                'event_start_datetime' => $startDate->copy()->addDays(3)->setTime(19, 0),
                'event_end_datetime' => $startDate->copy()->addDays(3)->setTime(21, 0),
                'description' => 'Ibadah Rabu malam dengan pelayanan firman',
            ],
            [
                'title' => 'Latihan Band Pemuda',
                'location_id' => $mainHall->id,
                'floor_id' => $mainHall3F->id,
                'event_start_datetime' => $startDate->copy()->addDays(3)->setTime(16, 0),
                'event_end_datetime' => $startDate->copy()->addDays(3)->setTime(18, 0),
                'description' => 'Latihan musik untuk tim worship pemuda',
            ],

            // Day 5 (Kamis)
            [
                'title' => 'Rapat Majelis Gereja',
                'location_id' => $chapel->id,
                'floor_id' => $chapel1F->id,
                'event_start_datetime' => $startDate->copy()->addDays(4)->setTime(18, 0),
                'event_end_datetime' => $startDate->copy()->addDays(4)->setTime(20, 0),
                'description' => 'Rapat bulanan majelis gereja',
            ],
            [
                'title' => 'Kelas Pernikahan',
                'location_id' => $sanctuary->id,
                'floor_id' => $sanctuary2F->id,
                'event_start_datetime' => $startDate->copy()->addDays(4)->setTime(19, 0),
                'event_end_datetime' => $startDate->copy()->addDays(4)->setTime(21, 0),
                'description' => 'Kelas pembinaan pernikahan untuk pasangan',
            ],

            // Day 6 (Jumat)
            [
                'title' => 'Doa Puasa Jumat',
                'location_id' => $chapel->id,
                'floor_id' => $chapel1F->id,
                'event_start_datetime' => $startDate->copy()->addDays(5)->setTime(18, 0),
                'event_end_datetime' => $startDate->copy()->addDays(5)->setTime(20, 0),
                'description' => 'Doa dan puasa Jumat malam',
            ],
            [
                'title' => 'Fellowship Pemuda Jumat',
                'location_id' => $mainHall->id,
                'floor_id' => $mainHall1F->id,
                'event_start_datetime' => $startDate->copy()->addDays(5)->setTime(19, 0),
                'event_end_datetime' => $startDate->copy()->addDays(5)->setTime(21, 30),
                'description' => 'Persekutuan dan kegiatan pemuda Jumat malam',
            ],
            [
                'title' => 'Nonton Bareng Anak-anak',
                'location_id' => $eagleKidz->id,
                'floor_id' => $eagleKidz1F->id,
                'event_start_datetime' => $startDate->copy()->addDays(5)->setTime(18, 0),
                'event_end_datetime' => $startDate->copy()->addDays(5)->setTime(20, 30),
                'description' => 'Nonton film rohani untuk anak-anak',
            ],

            // Day 7 (Sabtu)
            [
                'title' => 'Doa Pagi Sabtu',
                'location_id' => $chapel->id,
                'floor_id' => $chapel1F->id,
                'event_start_datetime' => $startDate->copy()->addDays(6)->setTime(6, 0),
                'event_end_datetime' => $startDate->copy()->addDays(6)->setTime(7, 30),
                'description' => 'Doa pagi Sabtu untuk jemaat',
            ],
            [
                'title' => 'Kelas Baptisan',
                'location_id' => $sanctuary->id,
                'floor_id' => $sanctuary2F->id,
                'event_start_datetime' => $startDate->copy()->addDays(6)->setTime(9, 0),
                'event_end_datetime' => $startDate->copy()->addDays(6)->setTime(12, 0),
                'description' => 'Kelas persiapan baptisan untuk calon baptisan',
            ],
            [
                'title' => 'Latihan Umum Natal',
                'location_id' => $mainHall->id,
                'floor_id' => $mainHall1F->id,
                'event_start_datetime' => $startDate->copy()->addDays(6)->setTime(14, 0),
                'event_end_datetime' => $startDate->copy()->addDays(6)->setTime(18, 0),
                'description' => 'Latihan umum persiapan perayaan Natal',
            ],
            [
                'title' => 'Bakti Sosial',
                'location_id' => $mainHall->id,
                'floor_id' => $mainHall3F->id,
                'event_start_datetime' => $startDate->copy()->addDays(6)->setTime(9, 0),
                'event_end_datetime' => $startDate->copy()->addDays(6)->setTime(12, 0),
                'description' => 'Persiapan kegiatan bakti sosial ke masyarakat',
            ],
        ];

        // Create all events
        foreach ($events as $eventData) {
            // Extract date and time from start datetime for backward compatibility
            $startDatetime = $eventData['event_start_datetime'];
            $eventData['event_date'] = $startDatetime->format('Y-m-d');
            $eventData['event_time'] = $startDatetime->format('H:i:s');

            // Convert Carbon to string for database
            $eventData['event_start_datetime'] = $startDatetime->format('Y-m-d H:i:s');
            $eventData['event_end_datetime'] = $eventData['event_end_datetime']->format('Y-m-d H:i:s');

            Event::create($eventData);
        }
    }
}
