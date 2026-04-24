<?php
namespace App\Services\Queue;
use App\Interfaces\TaskQueueInterface;
use App\Jobs\Task\Email\SendTaskCreatedEmailJob;
use App\Jobs\Task\Email\SendTaskUpdatedEmailJob;


class TaskQueue implements TaskQueueInterface{

    public function sendTaskCreatedEmail(int $taskId):void{
        SendTaskCreatedEmailJob::dispatch($taskId);

    }
    public function sendTaskUpdatedEmail(int $taskId) : void {
        SendTaskUpdatedEmailJob::dispatch($taskId);


        
    }
    


}