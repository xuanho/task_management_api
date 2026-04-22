<?php
namespace App\Interfaces;

interface TaskQueueInterface{
    public function sendTaskCreatedEmail(int $taskId):void;
}