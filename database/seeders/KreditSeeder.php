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
        // Data petani
        $metuWid = \App\Models\Petani::firstOrCreate(['nama' => 'Metu Wid']);
        $menRumi = \App\Models\Petani::firstOrCreate(['nama' => 'Men Rumi']);
        $panDut = \App\Models\Petani::firstOrCreate(['nama' => 'Pan Dut']);
        $panVidia = \App\Models\Petani::firstOrCreate(['nama' => 'Pan Vidia']);
        $jikaMin = \App\Models\Petani::firstOrCreate(['nama' => 'Jika Min']);
        $dewaAji = \App\Models\Petani::firstOrCreate(['nama' => 'Dewa Aji Wnp']);
        $ajinSupra = \App\Models\Petani::firstOrCreate(['nama' => 'Ajin Supra']);
        $gusSupra = \App\Models\Petani::firstOrCreate(['nama' => 'Gus Supra']);

        // Metu Wid
        \App\Models\Kredit::create([
            'petani_id' => $metuWid->id,
            'tanggal' => '2024-10-31',
            'keterangan' => 'First Data',
            'jumlah' => 11253200,
            'status' => 0,
        ]);

        // Men Rumi
        $menRumiDates = ['2024-06-18', '2024-07-12', '2024-08-01', '2024-08-14', '2024-09-02', '2024-10-19'];
        $menRumiAmounts = [3000000, 2000000, 1000000, 2500000, 1000000, 1500000];

        foreach (array_combine($menRumiDates, $menRumiAmounts) as $date => $amount) {
            \App\Models\Kredit::create([
                'petani_id' => $menRumi->id,
                'tanggal' => $date,
                'keterangan' => 'First Data',
                'jumlah' => $amount,
                'status' => 0,
            ]);
        }

        // Pan Dut
        \App\Models\Kredit::create([
            'petani_id' => $panDut->id,
            'tanggal' => '2024-11-02',
            'keterangan' => 'First Data',
            'jumlah' => 3000000,
            'status' => 0,
        ]);

        // Pan Vidia
        \App\Models\Kredit::create([
            'petani_id' => $panVidia->id,
            'tanggal' => '2024-09-23',
            'keterangan' => 'First Data',
            'jumlah' => 10000000,
            'status' => 0,
        ]);

        // Jika Min
        $jikaMinDates = ['2024-07-31', '2024-11-17'];
        $jikaMinAmounts = [11261510, 2000000];

        foreach (array_combine($jikaMinDates, $jikaMinAmounts) as $date => $amount) {
            \App\Models\Kredit::create([
                'petani_id' => $jikaMin->id,
                'tanggal' => $date,
                'keterangan' => 'First Data',
                'jumlah' => $amount,
                'status' => 0,
            ]);
        }

        // Data Kredit untuk Dewa Aji Wnp
        \App\Models\Kredit::create([
            'petani_id' => $dewaAji->id,
            'tanggal' => '2024-08-15',
            'keterangan' => 'First Data',
            'jumlah' => 1225750,
            'status' => 0,
        ]);

        \App\Models\Kredit::create([
            'petani_id' => $dewaAji->id,
            'tanggal' => '2024-08-15',
            'keterangan' => 'First Data',
            'jumlah' => 15000000,
            'status' => 0,
        ]);

        \App\Models\Kredit::create([
            'petani_id' => $dewaAji->id,
            'tanggal' => '2024-09-17',
            'keterangan' => 'First Data',
            'jumlah' => 2000000,
            'status' => 0,
        ]);

        \App\Models\Kredit::create([
            'petani_id' => $dewaAji->id,
            'tanggal' => '2024-10-01',
            'keterangan' => 'First Data',
            'jumlah' => 3200000,
            'status' => 0,
        ]);

        \App\Models\Kredit::create([
            'petani_id' => $dewaAji->id,
            'tanggal' => '2024-10-10',
            'keterangan' => 'First Data',
            'jumlah' => 1000000,
            'status' => 0,
        ]);


        // Ajin Supra
        \App\Models\Kredit::create([
            'petani_id' => $ajinSupra->id,
            'tanggal' => '2024-10-17',
            'keterangan' => 'First Data',
            'jumlah' => 2500000,
            'status' => 0,
        ]);

        // Gus Supra
        $gusSupraDates = ['2024-10-23', '2024-11-05'];
        $gusSupraAmounts = [2000000, 2000000];

        foreach (array_combine($gusSupraDates, $gusSupraAmounts) as $date => $amount) {
            \App\Models\Kredit::create([
                'petani_id' => $gusSupra->id,
                'tanggal' => $date,
                'keterangan' => 'First Data',
                'jumlah' => $amount,
                'status' => 0,
            ]);
        }
    }
}
