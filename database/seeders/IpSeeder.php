<?php

namespace Database\Seeders;

use App\Models\IP;
use Illuminate\Database\Seeder;

class IpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IP::truncate();
        IP::create(['id' => 1, 'IP' => ip2long('192.168.1.1'), 'location' => 'Latvia, Riga', 'address' => 'Raiņa bulvāris 12']);
        IP::create(['id' => 2, 'IP' => ip2long('192.168.1.12'), 'location' => 'Latvia, Riga', 'address' => 'Raiņa bulvāris 3']);
    }
}
