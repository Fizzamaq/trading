<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ActivityLogService
{
    /**
     * Log a user login event.
     *
     * @param \App\Models\User $user
     */
    public function logUserLogin($user)
    {
        $this->logActivity([
            'user_id' => $user->id,
            'action' => 'login',
            'model_type' => get_class($user),
            'model_id' => $user->id,
        ]);
    }

    /**
     * Log a user logout event.
     *
     * @param \App\Models\User $user
     */
    public function logUserLogout($user)
    {
        $this->logActivity([
            'user_id' => $user->id,
            'action' => 'logout',
            'model_type' => get_class($user),
            'model_id' => $user->id,
        ]);
    }

    /**
     * Log a model creation event.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function logModelCreated($model)
    {
        $this->logActivity([
            'user_id' => Auth::id(),
            'action' => 'created_' . $model->getTable(),
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'new_values' => $model->toArray(),
        ]);
    }

    /**
     * Log a model update event.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $oldValues
     */
    public function logModelUpdated($model, array $oldValues)
    {
        $this->logActivity([
            'user_id' => Auth::id(),
            'action' => 'updated_' . $model->getTable(),
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $model->getChanges(),
        ]);
    }

    /**
     * Log a generic activity with provided data.
     *
     * @param array $data
     */
    public function logActivity(array $data)
    {
        $request = app(Request::class);
        $data = array_merge([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ], $data);

        ActivityLog::create($data);
    }
}
