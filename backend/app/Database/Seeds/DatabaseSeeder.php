<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('App\Database\Seeds\RolesAndUsersSeeder');
        $this->call('App\Database\Seeds\SiteSettingsSeeder');
        $this->call('App\Database\Seeds\PagesSeeder');
        $this->call('App\Database\Seeds\ContentSeeder');
    }
}
