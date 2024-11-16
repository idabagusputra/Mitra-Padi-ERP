<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kredit;
use App\Models\Petani;
use Carbon\Carbon;

class KreditSeeder extends Seeder
{
    public function run()
    {
        $petanis = Petani::all();
        $totalKredits = 100; // Total number of credits to create
        $kreditCount = 0; // Counter for the number of created credits
        $maxDate = Carbon::createFromFormat('d/m/Y', '10/10/2024'); // Set max date to December 31, 2024

        foreach ($petanis as $petani) {
            if ($kreditCount >= $totalKredits) {
                break;
            }

            $maxKreditsForThisPetani = min(rand(1, 5), $totalKredits - $kreditCount);

            for ($i = 0; $i < $maxKreditsForThisPetani; $i++) {
                Kredit::create([
                    'petani_id' => $petani->id,
                    'tanggal' => $maxDate->copy()->subDays(rand(1, 365))->format('Y-m-d'), // Format date as 'Y-m-d' for MySQL compatibility
                    'keterangan' => "Kredit " . ($kreditCount + 1),
                    'jumlah' => rand(1000000, 100000000),
                    'status' => rand(0, 1),
                ]);
                $kreditCount++;

                if ($kreditCount >= $totalKredits) {
                    break;
                }
            }
        }
    }
}
