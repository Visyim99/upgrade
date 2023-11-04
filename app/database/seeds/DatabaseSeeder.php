<?php

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Eloquent::unguard();

        // $this->call('UserTableSeeder');
    }
}
