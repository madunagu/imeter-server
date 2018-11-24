<?php

use Illuminate\Database\Seeder;
use App\ConsumerDashboardStat;

class ConsumerDashboardStatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status1 = new ConsumerDashboardStat();
        $status1->name = 'unprocessed';
        $status1->display = 'Unprocessed';
        $status1->color = 'primary';
        $status1->value = 'testval';
        $status1->save();

        $status1 = new ConsumerDashboardStat();
        $status1->name = 'processing';
        $status1->display = 'Processing';
        $status1->color = 'warning';
        $status1->value = 'testval';
        $status1->save();

        $status1 = new ConsumerDashboardStat();
        $status1->name = 'delivered';
        $status1->display = 'Delivered';
        $status1->color = 'success';
        $status1->value = 'testval';
        $status1->save();

        $status1 = new ConsumerDashboardStat();
        $status1->name = 'returned';
        $status1->display = 'Returned';
        $status1->color = 'danger';
        $status1->value = 'testval';
        $status1->save();
    }
}
