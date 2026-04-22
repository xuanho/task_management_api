<?php
namespace App\Services\Queue;
use App\Interfaces\TaskQueueInterface;
use App\Jobs\SendTaskCreatedEmailJob;


class TaskQueue implements TaskQueueInterface{

    public function sendTaskCreatedEmail(int $taskId):void{
        SendTaskCreatedEmailJob::dispatch($taskId);

    }
    


}