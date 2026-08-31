<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Throwable;

class EmailDeliveryService
{
    public function __construct(private AdminSettingsService $settings)
    {
    }

    public function sendNotification(string $to, string $name, string $message, string $subject = 'Barangay Request Update'): void
    {
        $html = view('email.notification', [
            'name' => $name,
            'messageText' => $message,
        ])->render();

        $this->sendHtml($to, $subject, $html);
    }

    public function sendView(string $to, string $subject, string $view, array $data = []): void
    {
        $this->sendHtml($to, $subject, view($view, $data)->render());
    }

    public function sendText(string $to, string $subject, string $text): void
    {
        $html = '<div style="font-family:Arial,sans-serif;white-space:pre-wrap;line-height:1.6">' . e($text) . '</div>';
        $this->sendHtml($to, $subject, $html, $text);
    }

    public function sendHtml(string $to, string $subject, string $html, ?string $text = null): void
    {
        $email = $this->settings->section('email');
        $provider = strtolower(trim((string) ($email['mailer'] ?? config('mail.default', 'smtp'))));
        $fromAddress = trim((string) ($email['from_address'] ?? config('mail.from.address')));
        $fromName = trim((string) ($email['from_name'] ?? config('mail.from.name', config('app.name'))));

        if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('The recipient email address is invalid.');
        }
        if (! filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('The configured sender email address is invalid or missing.');
        }

        try {
            $this->deliver($provider, $email, $to, $subject, $html, $fromAddress, $fromName, $text);
        } catch (Throwable $e) {
            $fallback = strtolower(trim((string) env('MAIL_FALLBACK_PROVIDER', '')));

            if ($fallback !== '' && $fallback !== $provider) {
                try {
                    Log::warning('Primary email provider failed; trying fallback.', [
                        'provider' => $provider,
                        'fallback' => $fallback,
                        'to' => $to,
                    ]);
                    $this->deliver($fallback, $email, $to, $subject, $html, $fromAddress, $fromName, $text);
                    return;
                } catch (Throwable $fallbackError) {
                    Log::error('Email fallback provider failed.', [
                        'provider' => $fallback,
                        'to' => $to,
                        'message' => $fallbackError->getMessage(),
                    ]);
                }
            }

            Log::error('Email delivery failed', [
                'provider' => $provider,
                'to' => $to,
                'message' => $e->getMessage(),
            ]);
            throw $e instanceof RuntimeException ? $e : new RuntimeException('Email could not be sent: ' . $e->getMessage(), 0, $e);
        }
    }

    private function deliver(string $provider, array $email, string $to, string $subject, string $html, string $fromAddress, string $fromName, ?string $text): void
    {
        match ($provider) {
            'resend' => $this->sendResend($email, $to, $subject, $html, $fromAddress, $fromName, $text),
            'postmark' => $this->sendPostmark($email, $to, $subject, $html, $fromAddress, $fromName, $text),
            'mailgun' => $this->sendMailgun($email, $to, $subject, $html, $fromAddress, $fromName, $text),
            'sendgrid' => $this->sendSendGrid($email, $to, $subject, $html, $fromAddress, $fromName, $text),
            'smtp', 'mail', 'failover' => $this->sendSmtp($email, $to, $subject, $html, $fromAddress, $fromName),
            'log' => Log::info('Email delivery (log provider)', compact('to', 'subject', 'fromAddress')),
            default => throw new RuntimeException("Unsupported email provider: {$provider}."),
        };
    }

    private function sendSmtp(array $email, string $to, string $subject, string $html, string $fromAddress, string $fromName): void
    {
        config([
            'mail.mailers.smtp.host' => $email['host'] ?? config('mail.mailers.smtp.host'),
            'mail.mailers.smtp.port' => (int) ($email['port'] ?? config('mail.mailers.smtp.port', 587)),
            'mail.mailers.smtp.username' => $email['username'] ?? config('mail.mailers.smtp.username'),
            'mail.mailers.smtp.password' => $email['password'] ?? config('mail.mailers.smtp.password'),
            'mail.mailers.smtp.encryption' => in_array(strtolower((string) ($email['encryption'] ?? '')), ['', 'none'], true) ? null : $email['encryption'],
            'mail.from.address' => $fromAddress,
            'mail.from.name' => $fromName,
        ]);

        Mail::mailer('smtp')->html($html, function ($message) use ($to, $subject, $fromAddress, $fromName) {
            $message->to($to)->subject($subject)->from($fromAddress, $fromName);
        });
    }

    private function sendResend(array $email, string $to, string $subject, string $html, string $fromAddress, string $fromName, ?string $text): void
    {
        $key = $this->requireApiKey($email, 'Resend');
        $response = Http::timeout(20)->withToken($key)->acceptJson()->post('https://api.resend.com/emails', array_filter([
            'from' => $this->formatFrom($fromAddress, $fromName),
            'to' => [$to],
            'subject' => $subject,
            'html' => $html,
            'text' => $text,
        ], fn ($v) => $v !== null && $v !== ''));
        $this->ensureSuccess($response->successful(), $response->status(), $response->body(), 'Resend');
    }

    private function sendPostmark(array $email, string $to, string $subject, string $html, string $fromAddress, string $fromName, ?string $text): void
    {
        $key = $this->requireApiKey($email, 'Postmark');
        $response = Http::timeout(20)->withHeaders(['X-Postmark-Server-Token' => $key])->acceptJson()->post('https://api.postmarkapp.com/email', array_filter([
            'From' => $this->formatFrom($fromAddress, $fromName),
            'To' => $to,
            'Subject' => $subject,
            'HtmlBody' => $html,
            'TextBody' => $text,
            'MessageStream' => $email['message_stream'] ?? 'outbound',
        ], fn ($v) => $v !== null && $v !== ''));
        $this->ensureSuccess($response->successful(), $response->status(), $response->body(), 'Postmark');
    }

    private function sendMailgun(array $email, string $to, string $subject, string $html, string $fromAddress, string $fromName, ?string $text): void
    {
        $key = $this->requireApiKey($email, 'Mailgun');
        $domain = trim((string) ($email['domain'] ?? env('MAILGUN_DOMAIN', '')));
        if ($domain === '') {
            throw new RuntimeException('Mailgun domain is required.');
        }
        $endpoint = trim((string) ($email['endpoint'] ?? env('MAILGUN_ENDPOINT', 'https://api.mailgun.net')));
        if (! str_starts_with($endpoint, 'http://') && ! str_starts_with($endpoint, 'https://')) {
            $endpoint = 'https://' . $endpoint;
        }
        $endpoint = rtrim($endpoint, '/');
        $response = Http::timeout(20)->withBasicAuth('api', $key)->asForm()->post("{$endpoint}/v3/{$domain}/messages", array_filter([
            'from' => $this->formatFrom($fromAddress, $fromName),
            'to' => $to,
            'subject' => $subject,
            'html' => $html,
            'text' => $text,
        ], fn ($v) => $v !== null && $v !== ''));
        $this->ensureSuccess($response->successful(), $response->status(), $response->body(), 'Mailgun');
    }

    private function sendSendGrid(array $email, string $to, string $subject, string $html, string $fromAddress, string $fromName, ?string $text): void
    {
        $key = $this->requireApiKey($email, 'SendGrid');
        $content = [['type' => 'text/html', 'value' => $html]];
        if ($text) {
            array_unshift($content, ['type' => 'text/plain', 'value' => $text]);
        }
        $response = Http::timeout(20)->withToken($key)->acceptJson()->post('https://api.sendgrid.com/v3/mail/send', [
            'personalizations' => [['to' => [['email' => $to]]]],
            'from' => array_filter(['email' => $fromAddress, 'name' => $fromName]),
            'subject' => $subject,
            'content' => $content,
        ]);
        $this->ensureSuccess($response->successful(), $response->status(), $response->body(), 'SendGrid');
    }

    private function requireApiKey(array $email, string $provider): string
    {
        $key = trim((string) ($email['api_key'] ?? ''));

        if ($key === '') {
            $key = match (strtolower($provider)) {
                'resend' => trim((string) env('RESEND_KEY', '')),
                'postmark' => trim((string) env('POSTMARK_TOKEN', '')),
                'mailgun' => trim((string) env('MAILGUN_SECRET', '')),
                'sendgrid' => trim((string) env('SENDGRID_API_KEY', '')),
                default => '',
            };
        }

        if ($key === '') {
            throw new RuntimeException("{$provider} API key is required.");
        }

        return $key;
    }

    private function ensureSuccess(bool $successful, int $status, string $body, string $provider): void
    {
        if ($successful) {
            return;
        }
        $body = trim(preg_replace('/\s+/', ' ', $body));
        throw new RuntimeException("{$provider} rejected the email (HTTP {$status})" . ($body !== '' ? ': ' . mb_substr($body, 0, 500) : '.'));
    }

    private function formatFrom(string $address, string $name): string
    {
        return $name !== '' ? sprintf('%s <%s>', $name, $address) : $address;
    }
}
