<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();
        // Register the user seeder
        // $this->call(\UsersTableSeeder::class);
        // Model::reguard();
        \DB::table('customer')->truncate();
        \DB::table('posts')->truncate();
        factory(App\User::class, 100)->create();
        factory(App\Post::class, 100)->create();
    }
}
