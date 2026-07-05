<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity;
use App\Traits\Tenantable;

class ActivityLog extends Activity
{
    use Tenantable;
}
