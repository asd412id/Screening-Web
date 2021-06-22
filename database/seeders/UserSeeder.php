<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Str;

class UserSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    $user = new User;
    $user->uuid = (string) Str::uuid();
    $user->name = 'Administrator';
    $user->username = 'admin';
    $user->password = bcrypt('passwordAdmin');
    $user->save();
  }
}
