<?php

namespace Modules\User\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

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
                'first_name' => 'Default',
                'last_name'  => 'Admin',
                'mobile'     => '9121234567',
                'password'   => 'secret@exchange',
            ],
            [
                'mobile'   => '9393982868',
                'password' => 'secret@mahdi',
            ],
        ];

        array_map(fn($admin) => User::firstOrCreate([
            'mobile' => $admin['mobile'],
        ], $admin)->assignRole('super-admin'), $admins);
    }
}
