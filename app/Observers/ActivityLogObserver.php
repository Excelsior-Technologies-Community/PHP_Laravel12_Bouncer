<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Services\ActivityLogService;

class ActivityLogObserver
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function created($model)
    {
        $this->activityLogService->log(get_class($model) . '_created', $model, null, $model->toArray());
    }

    public function updated($model)
    {
        $this->activityLogService->log(get_class($model) . '_updated', $model, $model->getOriginal(), $model->toArray());
    }

    public function deleted($model)
    {
        $this->activityLogService->log(get_class($model) . '_deleted', $model, $model->toArray(), null);
    }

    public function restored($model)
    {
        $this->activityLogService->log(get_class($model) . '_restored', $model, null, $model->toArray());
    }
}
