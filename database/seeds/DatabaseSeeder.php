<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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
        Model::unguard();

        \App\Artvenue\Models\User::create([
            'username'     => 'adminUser',
            'password'     => bcrypt('admin123'),
            'fullname'     => 'Abhimanyu',
            'email'        => 'admin@gmail.com',
            'confirmed_at' => Carbon::now(),
            'avatar'       => 'user',
            'permission'   => 'admin',
        ]);

        DB::table('sitesettings')
            ->insert([
                ['option' => 'siteName', 'value' => 'ArtVenue'],
                ['option' => 'description', 'value' => 'Some Description'],
                ['option' => 'favIcon', 'value' => 'favicon.ico'],
                ['option' => 'tos', 'value' => 'add tos here'],
                ['option' => 'privacy', 'value' => 'add privacy policy here'],
                ['option' => 'faq', 'value' => 'add faq here'],
                ['option' => 'about', 'value' => 'add about us here'],
                ['option' => 'autoApprove', 'value' => '1'],
                ['option' => 'numberOfImagesInGallery', 'value' => '21'],
                ['option' => 'limitPerDay', 'value' => '20'],
                ['option' => 'tagsLimit', 'value' => '5'],
                ['option' => 'allowDownloadOriginal', 'value' => '1'],
                ['option' => 'maxImageSize', 'value' => '10'],
            ]);

        DB::table('categories')
            ->insert([
                ['name' => 'Uncategorized', 'slug' => 'uncategorized'],
            ]);

        Model::reguard();
    }
}
