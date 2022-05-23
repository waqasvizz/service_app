<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use DB;
use Exception;
use File;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = \Faker\Factory::create();
        // Service::factory(10)->create();
        try{
            if(DB::table('services')->count() == 0){
                File::copy(public_path('storage/service_image/seed-images/painting.jpg'), public_path('storage/service_image/painting.jpg'));
                File::copy(public_path('storage/service_image/seed-images/carpet-cleaning.jpg'), public_path('storage/service_image/carpet-cleaning.jpg'));
                File::copy(public_path('storage/service_image/seed-images/landscaping.jpg'), public_path('storage/service_image/landscaping.jpg'));
                File::copy(public_path('storage/service_image/seed-images/drywall-repair-scaled.jpeg'), public_path('storage/service_image/drywall-repair-scaled.jpeg'));
                File::copy(public_path('storage/service_image/seed-images/Cleaning-Company.jpeg'), public_path('storage/service_image/Cleaning-Company.jpeg'));
                
                DB::table('services')->insert([

                    [
                        'service_name' => 'Painting',
                        'service_description' => 'Painting',
                        // 'service_image' => 'storage/service_image/'.$faker->image('public/storage/service_image',640,480, null, false),
                        'service_image' => 'service_image/painting.jpg',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'service_name' => 'Carpet cleaning',
                        'service_description' => 'Carpet cleaning',
                        // 'service_image' => 'storage/service_image/'.$faker->image('public/storage/service_image',640,480, null, false),
                        'service_image' => 'service_image/carpet-cleaning.jpg',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'service_name' => 'Landscaping',
                        'service_description' => 'Landscaping',
                        // 'service_image' => 'storage/service_image/'.$faker->image('public/storage/service_image',640,480, null, false),
                        'service_image' => 'service_image/landscaping.jpg',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'service_name' => 'Drywall repair',
                        'service_description' => 'Drywall repair',
                        // 'service_image' => 'storage/service_image/'.$faker->image('public/storage/service_image',640,480, null, false),
                        'service_image' => 'service_image/drywall-repair-scaled.jpeg',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'service_name' => 'Cleaning',
                        'service_description' => 'Cleaning',
                        // 'service_image' => 'storage/service_image/'.$faker->image('public/storage/service_image',640,480, null, false),
                        'service_image' => 'service_image/Cleaning-Company.jpeg',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            } else { echo "<br>[Service Table is not empty] "; }

        }catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}