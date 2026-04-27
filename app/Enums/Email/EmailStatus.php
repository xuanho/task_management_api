<?php

namespace App\Enums\Email;

enum EmailStatus: string
{
    case PENDING = 'pending';
    case SENT = 'sent';
    case FALIED = 'failed';

}
