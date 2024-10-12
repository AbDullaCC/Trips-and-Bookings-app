<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BaseService
{
    protected function logAction($action, $details){
        Log::channel('critical_actions')->info($action, $details);
    }
}
