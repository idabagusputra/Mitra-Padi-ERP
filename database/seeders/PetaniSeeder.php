<?php

namespace Database\Seeders;

use App\Models\Petani;
use Illuminate\Database\Seeder;

class PetaniSeeder extends Seeder
{
    public function run()
    {
        $namaPetani = [
            'Meyu Sugi',
            'Metu Wid',
            'Pan Lisa',
            'Pan Krisna',
            'Pan Dut',
            'Men Joni',
            'Pan Rika',
            'Pan Vidia',
            'Jika Min',
            'Dewa Aji Wnp',
            'Pan Ayu Sugendro',
            'Ajin Supra',
            'Eka Nova',
            'Gus Supra',
            'Pan Gede Sandut',
        ];

        foreach ($namaPetani as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Wanaprasta',
                'no_telepon' => '080000000000',
            ]);
        }
    }
}
