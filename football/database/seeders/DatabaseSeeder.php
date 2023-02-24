<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Generate a random integer between the start and stop values, inclusive.
     *
     * @param int $start The lowest possible value for the random number.
     * @param int $stop The highest possible value for the random number.
     *
     * @return int A random integer between the start and stop values.
     */ /**/
    private function randomNumber(int $start, int $stop): int
    {
        return rand($start, $stop);
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Team::factory()
            ->count($this->randomNumber(10, 15))
            ->hasPlayers($this->randomNumber(5, 15))
            ->create();
    }
}
