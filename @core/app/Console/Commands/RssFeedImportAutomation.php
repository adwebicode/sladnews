<?php

namespace App\Console\Commands;

use App\RssFeedInfo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RssFeedImportAutomation extends Command
{

    protected $signature = 'feed:automation';

    protected $description = 'Command description';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $all_rss_feed_info = RssFeedInfo::where('status','on')->get();

        foreach ($all_rss_feed_info as $rss_item){

            $last_update = $rss_item->last_import_time;
            $old_carbon = \Carbon\Carbon::parse($last_update);
            $automation_type = $rss_item->automation_type;
            $last_carbon = Carbon::parse($old_carbon);

            switch ($automation_type){
                case 'every_two_hours' && $last_carbon->diffInHours() > 2:
                case 'every_six_hours' && $last_carbon->diffInHours() > 6:
                case 'daily' && $last_carbon->diffInDays() > 1:
                case 'weekly' && $last_carbon->diffInDays() > 7:
                case 'every_minutes' && $last_carbon->diffInMinutes() > 0 :
                    set_rss_import_automation($rss_item->id);
                break;
            }

        }

        return 0;
    }
}
