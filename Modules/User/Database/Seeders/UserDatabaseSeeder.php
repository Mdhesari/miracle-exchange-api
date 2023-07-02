<?php

namespace Modules\User\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $admins = [
            [
                'mobile'   => '9121234567',
                'password' => 'secret@exchange',
            ]
        ];

        array_map(fn($admin) => User::firstOrCreate([
            'mobile' => $admin['mobile'],
        ], $admin)->assignRole('super-admin'), $admins);
    }
}
