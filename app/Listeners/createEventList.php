<?php

namespace App\Listeners;

use App\Events\saveModel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\EventList;
use App\Events\CheckupDataUpdated;

class createEventList
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  saveModel  $event
     * @return void
     */
    public function handle(saveModel $event)
    {
        //レコード更新のイベントログを保存
        EventList::create([
            'name'=> $event->param[0],
            'type'=> $event->param[1],
            'level'=> $event->param[2],
            'notes'=> $event->param[3],
        ]);

        $type = $event->param[1];
        if(\in_array($type,['update_reserve','create_reserve','delete_reserve','upload_reception_list'])){        
            broadcast(new CheckupDataUpdated('reserve'))->toOthers();
        }else if(\in_array($type,['update_result','checkup_complete'])){        
            broadcast(new CheckupDataUpdated('result'))->toOthers();
        }else{
            broadcast(new CheckupDataUpdated('event_list'))->toOthers();            
        }

    }
}
