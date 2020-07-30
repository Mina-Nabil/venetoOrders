<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        // DB::table("dash_types")->insert([
        //     "DHTP_NAME" => "admin"
        // ]);

        // DB::table('dash_users')->insert([
        //     "DASH_USNM" => "mina",
        //     "DASH_FLNM" => "Mina Nabil",
        //     "DASH_PASS" => bcrypt('mina@whale'),           
        //     "DASH_TYPE_ID" => 1,
        // ]);

        // DB::table("categories")->insert([
        //     "CATG_NAME" => "Men",
        //     "CATG_ARBC_NAME" => "رجالي",
        // ]);

        // DB::table("categories")->insert([
        //     "CATG_NAME" => "Women",
        //     "CATG_ARBC_NAME" => "حريمي",
        // ]);

        // DB::table('genders')->insert([
        //     "GNDR_NAME" => "Male",
        //     "GNDR_ARBC_NAME" => "ذكر"
        // ]);

        // DB::table('genders')->insert([
        //     "GNDR_NAME" => "Female",
        //     "GNDR_ARBC_NAME" => "انثي"
        // ]);

        // DB::table('genders')->insert([
        //     "GNDR_NAME" => "Prefer not to say",
        //     "GNDR_ARBC_NAME" => "لا افضل الاختيار"
        // ]);

        // DB::table('order_status')->insert([
        //     "STTS_NAME" => "New"
        // ]);
        // DB::table('order_status')->insert([
        //     "STTS_NAME" => "Ready"
        // ]);
        // DB::table('order_status')->insert([
        //     "STTS_NAME" => "In Delivery"
        // ]);
        // DB::table('order_status')->insert([
        //     "STTS_NAME" => "Delivered"
        // ]);
        // DB::table('order_status')->insert([
        //     "STTS_NAME" => "Cancelled"
        // ]);

        // DB::table("payment_options")->insert([
        //     "PYOP_NAME" => "Cash On Delivery",
        //     "PYOP_ARBC_NAME" => "كاش"
        // ]);
    

        // DB::table("payment_options")->insert([
        //     "PYOP_NAME" => "Credit Card",
        //     "PYOP_ARBC_NAME" => "بطاقه ائتمان"
        // ]);

        // DB::table("payment_options")->insert([
        //     "PYOP_NAME" => "Credit Card On Delivery",
        //     "PYOP_ARBC_NAME" => "بطاقه ائتمان عند التوصيل"
        // ]);
    }
}
