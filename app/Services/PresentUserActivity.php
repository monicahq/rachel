<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\UserActivityLog;

final readonly class PresentUserActivity
{
    /**
     * Transform a log row into the timeline payload expected by activity partial.
     *
     * @return array{action: string, icon: string, description: string, actor: string, created_at: \Illuminate\Support\Carbon, color: string, status?: string}
     */
    public function execute(UserActivityLog $log): array
    {
        $metadata = is_array($log->metadata) ? $log->metadata : [];
        $actor = $log->actor?->name ?? __('System');

        $entry = match ($log->action) {
            UserActivityLog::ACTION_PROFILE_NAME_UPDATED => [
                'action' => __('Profile information updated'),
                'icon' => 'pencil',
                'description' => __('Updated profile name'),
                'color' => 'blue',
            ],
            UserActivityLog::ACTION_PROFILE_EMAIL_UPDATED => [
                'action' => __('Profile information updated'),
                'icon' => 'pencil',
                'description' => __('Updated email address'),
                'color' => 'blue',
            ],
            UserActivityLog::ACTION_PROFILE_NAME_AND_EMAIL_UPDATED => [
                'action' => __('Profile information updated'),
                'icon' => 'pencil',
                'description' => __('Updated name and email address'),
                'color' => 'blue',
            ],
            UserActivityLog::ACTION_SECURITY_PASSWORD_UPDATED => [
                'action' => __('Password updated'),
                'icon' => 'key',
                'description' => __('Updated account password'),
                'color' => 'purple',
                'status' => __('Security'),
            ],
            UserActivityLog::ACTION_SECURITY_TWO_FACTOR_ENABLED => [
                'action' => __('Two-factor authentication enabled'),
                'icon' => 'shield-check',
                'description' => __('Enabled two-factor authentication'),
                'color' => 'purple',
                'status' => __('Security'),
            ],
            UserActivityLog::ACTION_SECURITY_TWO_FACTOR_CONFIRMED => [
                'action' => __('Two-factor authentication confirmed'),
                'icon' => 'shield-check',
                'description' => __('Confirmed two-factor authentication setup'),
                'color' => 'purple',
                'status' => __('Security'),
            ],
            UserActivityLog::ACTION_SECURITY_TWO_FACTOR_DISABLED => [
                'action' => __('Two-factor authentication disabled'),
                'icon' => 'shield-exclamation',
                'description' => __('Disabled two-factor authentication'),
                'color' => 'orange',
                'status' => __('Security'),
            ],
            UserActivityLog::ACTION_SECURITY_TWO_FACTOR_RECOVERY_CODES_REGENERATED => [
                'action' => __('Recovery codes regenerated'),
                'icon' => 'arrow-path',
                'description' => __('Generated a new set of two-factor recovery codes'),
                'color' => 'purple',
                'status' => __('Security'),
            ],
            UserActivityLog::ACTION_SECURITY_WEBAUTHN_KEY_REGISTERED => [
                'action' => __('Security key registered'),
                'icon' => 'fingerprint',
                'description' => __('Registered a new :kind key', ['kind' => $metadata['key_kind'] ?? __('security')]),
                'color' => 'purple',
                'status' => __('Security'),
            ],
            UserActivityLog::ACTION_SECURITY_WEBAUTHN_KEY_UPDATED => [
                'action' => __('Security key updated'),
                'icon' => 'pencil',
                'description' => __('Updated a security key name'),
                'color' => 'blue',
                'status' => __('Security'),
            ],
            UserActivityLog::ACTION_SECURITY_WEBAUTHN_KEY_DELETED => [
                'action' => __('Security key deleted'),
                'icon' => 'trash',
                'description' => __('Deleted a registered security key'),
                'color' => 'orange',
                'status' => __('Security'),
            ],
            UserActivityLog::ACTION_SECURITY_WEBAUTHN_KEY_UPGRADED => [
                'action' => __('Security key upgraded'),
                'icon' => 'arrow-up',
                'description' => __('Upgraded a security key to passkey'),
                'color' => 'green',
                'status' => __('Security'),
            ],
            UserActivityLog::ACTION_ACCOUNT_DELETED => [
                'action' => __('Account deleted'),
                'icon' => 'trash',
                'description' => __('Deleted this account'),
                'color' => 'orange',
                'status' => __('Account'),
            ],
            UserActivityLog::ACTION_VAULT_CREATED => [
                'action' => __('Vault created'),
                'icon' => 'folder-plus',
                'description' => __('Created vault :vault', ['vault' => $metadata['vault_name'] ?? __('Unknown')]),
                'link' => $log->loggable !== null ? route('vaults.show', [$log->loggable]) : null,
                'color' => 'green',
            ],
            UserActivityLog::ACTION_CONTACT_CREATED => [
                'action' => __('Contact created'),
                'icon' => 'user-plus',
                'description' => __('Created contact :contact', ['contact' => $metadata['contact_name'] ?? __('Unknown')]),
                'link' => $log->loggable !== null ? route('contacts.show', [$log->loggable->vault, $log->loggable]) : null,
                'color' => 'green',
            ],
            UserActivityLog::ACTION_CONTACT_PARAMETER_CREATED => [
                'action' => __('Contact parameter created'),
                'icon' => 'plus-circle',
                'description' => __('Added parameter :parameter', ['parameter' => $metadata['parameter_key'] ?? __('Unknown')]),
                'link' => $log->loggable !== null ? route('contacts.show', [$log->loggable->vault, $log->loggable]) : null,
                'color' => 'blue',
            ],
            UserActivityLog::ACTION_CONTACT_EMAIL_ADDED => [
                'action' => __('Contact email added'),
                'icon' => 'envelope',
                'description' => __('Added an email address to a contact'),
                'link' => $log->loggable !== null ? route('contacts.show', [$log->loggable->vault, $log->loggable]) : null,
                'color' => 'blue',
            ],
            UserActivityLog::ACTION_CONTACT_EMAIL_UPDATED => [
                'action' => __('Contact email updated'),
                'icon' => 'pencil',
                'description' => __('Updated an email address on a contact'),
                'link' => $log->loggable !== null ? route('contacts.show', [$log->loggable->vault, $log->loggable]) : null,
                'color' => 'blue',
            ],
            UserActivityLog::ACTION_CONTACT_EMAIL_DELETED => [
                'action' => __('Contact email deleted'),
                'icon' => 'trash',
                'description' => __('Deleted an email address from a contact'),
                'link' => $log->loggable !== null ? route('contacts.show', [$log->loggable->vault, $log->loggable]) : null,
                'color' => 'orange',
            ],
            default => [
                'action' => __('Activity'),
                'icon' => 'clock-counter-clockwise',
                'description' => __('Activity recorded'),
                'color' => 'gray',
            ],
        };

        return [
            ...$entry,
            'actor' => $actor,
            'created_at' => $log->created_at,
        ];
    }

    public function actorLabel(User $viewer, UserActivityLog $log): string
    {
        if ($log->actor_user_id === $viewer->id) {
            return (string) __('You');
        }

        return $log->actor?->name ?? (string) __('System');
    }
}
