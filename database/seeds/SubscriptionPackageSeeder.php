<?php

use Illuminate\Database\Seeder;

class SubscriptionPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<4;$i++)
        {
            $package = [[
             'amount' => ($i+1)*10,
                'MonthIncrease' => ($i+1)
            ]];
            App\SubscriptionPackage::insert($package);
        }

    }
}
