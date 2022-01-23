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
        IP::create(['id' => 3, 'IP' => ip2long('192.168.2.9'), 'location' => 'Latvia, Riga', 'address' => 'Valdemāra iela 5']);
        IP::create(['id' => 4, 'IP' => ip2long('192.164.2.5'), 'location' => 'Latvia, Ventspils', 'address' => 'Lemberga iela 18']);
        IP::create(['id' => 5, 'IP' => ip2long('174.107.1.1'), 'location' => 'Germany, Stuttgart', 'address' => 'Wilhelmstrasse 5-B']);
    }
}
