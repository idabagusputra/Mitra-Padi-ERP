<?php

namespace Database\Seeders;

use App\Models\Petani;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PetaniSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create(); // Initialize Faker to generate random data

        // Daftar alamat yang akan digunakan
        $alamatList = [
            'Penebel',
            'Palesari',
            'Sangeh Sari',
            'Gigit Sari',
            'Wanaprasta',
            'Sibang',
            'Sausu',
            'Bali Indah',
            'Candra Buana'
        ];

        // Daftar nama petani Palesari
        $palesariPetani = [
            'Meman Indah',
            'Men Evan',
            'Men Yadnya',
            'Bli Komang Ano',
            'Jikman Babahan',
            'Ibun Wid',
            'Men Pera',
            'Kadek Kariasa',
            'Pan Gede Ari',
            'Pan Komang Adi',
            'Ajin Intan',
            'Pak Wo Rato',
            'Pan Agus Sandipa',
            'Nini',
            'Ajik Widana'
        ];

        // Daftar nama petani Gigit Sari
        $gigitSariPetani = [
            'Ajik Yuna Gigit',
            'Pan Yena',
            'Pan Yulia',
            'Pan Nadia',
            'Pan Dewi',
            'Pan Sinta'
        ];

        // Create Palesari Petani records
        foreach ($palesariPetani as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Palesari',
                'no_telepon' => $faker->phoneNumber,
            ]);
        }

        // Create Gigit Sari Petani records
        foreach ($gigitSariPetani as $nama) {
            Petani::create([
                'nama' => $nama,
                'alamat' => 'Gigit Sari',
                'no_telepon' => $faker->phoneNumber,
            ]);
        }

        // Calculate remaining records to create
        $remainingRecords = 50 - count($palesariPetani) - count($gigitSariPetani);

        // Create remaining Petani records for other locations
        for ($i = 0; $i < $remainingRecords; $i++) {
            $alamat = $faker->randomElement(array_diff($alamatList, ['Palesari', 'Gigit Sari']));
            Petani::create([
                'nama' => $faker->name,
                'alamat' => $alamat,
                'no_telepon' => $faker->phoneNumber,
            ]);
        }
    }
}
