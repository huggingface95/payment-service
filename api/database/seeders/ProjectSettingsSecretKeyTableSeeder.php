<?php

namespace Database\Seeders;

use App\Models\ProjectSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSettingsSecretKeyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        ProjectSettings::query()->get()->each(function (ProjectSettings $setting) {
            $setting->secret_key = Str::random();
            $setting->save();
        });

    }
}
