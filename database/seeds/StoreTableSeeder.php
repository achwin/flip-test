<?php

use Illuminate\Database\Seeder;

class StoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Store::class)->create([
            'user_id' => factory(App\User::class)->create()
        ]);
    }
}
