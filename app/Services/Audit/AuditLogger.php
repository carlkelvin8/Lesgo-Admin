<?php

namespace App\Services\Audit;

use App\Models\AuditLog;
use App\Models\SecurityEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public function log(
        string $event,
        Model $auditable,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?array $tags = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => get_class($auditable),
            'auditable_id' => $auditable->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'tags' => $tags,
        ]);
    }

    public function logCreated(Model $model, ?array $tags = null): AuditLog
    {
        return $this->log(
            event: class_basename($model) . '.created',
            auditable: $model,
            newValues: $model->getAttributes(),
            tags: $tags
        );
    }

    public function logUpdated(Model $model, array $changes, ?array $tags = null): AuditLog
    {
        $oldValues = [];
        $newValues = [];

        foreach ($changes as $key => $value) {
            if (is_array($value) && isset($value[0], $value[1])) {
                $oldValues[$key] = $value[0];
                $newValues[$key] = $value[1];
            }
        }

        return $this->log(
            event: class_basename($model) . '.updated',
            auditable: $model,
            oldValues: $oldValues,
            newValues: $newValues,
            tags: $tags
        );
    }

    public function logDeleted(Model $model, ?array $tags = null): AuditLog
    {
        return $this->log(
            event: class_basename($model) . '.deleted',
            auditable: $model,
            oldValues: $model->getAttributes(),
            tags: $tags
        );
    }

    public function logSecurityEvent(
        string $eventType,
        string $severity = 'info',
        ?array $metadata = null,
        ?int $userId = null
    ): SecurityEvent {
        return SecurityEvent::create([
            'user_id' => $userId ?? Auth::id(),
            'event_type' => $eventType,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'metadata' => $metadata,
            'severity' => $severity,
        ]);
    }

    public function logLogin(int $userId, bool $success = true): SecurityEvent
    {
        return $this->logSecurityEvent(
            eventType: $success ? 'login_success' : 'login_failed',
            severity: $success ? 'info' : 'warning',
            userId: $userId
        );
    }

    public function logLogout(int $userId): SecurityEvent
    {
        return $this->logSecurityEvent(
            eventType: 'logout',
            severity: 'info',
            userId: $userId
        );
    }

    public function logPasswordChanged(int $userId): SecurityEvent
    {
        return $this->logSecurityEvent(
            eventType: 'password_changed',
            severity: 'info',
            userId: $userId
        );
    }

    public function logSuspiciousActivity(string $description, ?array $metadata = null): SecurityEvent
    {
        return $this->logSecurityEvent(
            eventType: 'suspicious_activity',
            severity: 'critical',
            metadata: array_merge(['description' => $description], $metadata ?? [])
        );
    }
}
