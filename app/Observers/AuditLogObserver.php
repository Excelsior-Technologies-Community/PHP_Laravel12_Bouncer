<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Services\AuditLogService;

class AuditLogObserver
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    public function created($model)
    {
        $this->auditLogService->log('created', $model, null, $model->toArray());
    }

    public function updated($model)
    {
        $this->auditLogService->log('updated', $model, $model->getOriginal(), $model->toArray());
    }

    public function deleted($model)
    {
        $this->auditLogService->log('deleted', $model, $model->toArray(), null);
    }

    public function restored($model)
    {
        $this->auditLogService->log('restored', $model, null, $model->toArray());
    }
}
