<?php

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::create([
            'name' => 'Croatian kuna',
            'symbol' => 'kn',
            'code' => 'HRK',
        ]);

        Currency::create([
            'name' => 'Euro',
            'symbol' => '€',
            'code' => 'EUR',
        ]);
    }
}
