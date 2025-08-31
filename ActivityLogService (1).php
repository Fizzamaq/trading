<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public function logActivity($data)
    {
        return ActivityLog::create([
            'user_id' => $data['user_id'] ?? Auth::id(),
            'action' => $data['action'],
            'model_type' => $data['model_type'] ?? null,
            'model_id' => $data['model_id'] ?? null,
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? null,
            'ip_address' => $data['ip_address'] ?? Request::ip(),
            'user_agent' => $data['user_agent'] ?? Request::userAgent(),
        ]);
    }

    public function logModelCreated($model)
    {
        return $this->logActivity([
            'action' => 'created',
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'new_values' => $model->toArray(),
        ]);
    }

    public function logModelUpdated($model, $oldValues)
    {
        return $this->logActivity([
            'action' => 'updated',
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $model->getChanges(),
        ]);
    }

    public function logModelDeleted($model)
    {
        return $this->logActivity([
            'action' => 'deleted',
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $model->toArray(),
        ]);
    }

    public function logUserLogin($user)
    {
        return $this->logActivity([
            'user_id' => $user->id,
            'action' => 'login',
            'model_type' => get_class($user),
            'model_id' => $user->id,
        ]);
    }

    public function logUserLogout($user)
    {
        return $this->logActivity([
            'user_id' => $user->id,
            'action' => 'logout',
            'model_type' => get_class($user),
            'model_id' => $user->id,
        ]);
    }
}
