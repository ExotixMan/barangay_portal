<?php

namespace App\Http\Controllers;

use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecureFileController extends Controller
{
    public function __construct(private SupabaseStorageService $storage)
    {
    }

    public function residentId(Request $request, string $side)
    {
        abort_unless(in_array($side, ['front', 'back', 'selfie'], true), 404);
        $resident = Auth::user();
        abort_unless($resident, 403);

        $field = ['front' => 'valid_id', 'back' => 'valid_id_back', 'selfie' => 'valid_id_selfie'][$side];

        return $this->serve((string) ($resident->{$field} ?? ''));
    }

    public function residentProfilePhoto(Request $request)
    {
        $resident = Auth::user();
        abort_unless($resident, 403);

        return $this->serve((string) ($resident->profile_photo ?? ''));
    }

    public function admin(Request $request)
    {
        $path = $request->query('path', '');
        abort_unless(is_string($path), 404);
        $path = trim($path);
        abort_if($path === '' || str_contains($path, '..') || preg_match('/[\x00-\x1f]/', $path), 404);

        $allowedPrefixes = [
            'uploads/valid_id/',
            'uploads/valid_ids/',
            'uploads/evidences/',
            'uploads/proof_of_residency/',
            'uploads/profile_photos/',
            'private/valid_id/',
            'private/valid_ids/',
            'private/evidences/',
            'private/proof_of_residency/',
            'private/profile_photos/',
        ];

        abort_unless(collect($allowedPrefixes)->contains(fn ($prefix) => str_starts_with($path, $prefix)), 403);

        app(\App\Services\PrivateRecordAccess::class)->authorizeFile($path);

        return $this->serve($path);
    }

    private function serve(string $path, ?\Illuminate\Http\Client\Response $remote = null)
    {
        abort_if($path === '' || str_contains($path, '..'), 404);

        if (str_starts_with($path, 'private/')) {
            $remote ??= $this->storage->fetchPrivate($path);

            if ($remote) {
                $contentType = $remote->header('Content-Type') ?: 'application/octet-stream';

                return response($remote->body(), 200, [
                    'Content-Type' => $contentType,
                    'Content-Length' => (string) strlen($remote->body()),
                    'Cache-Control' => 'private, no-store, max-age=0',
                    'X-Content-Type-Options' => 'nosniff',
                    'Content-Disposition' => 'inline',
                ]);
            }

            $localPrivatePath = $this->storage->localPrivatePath($path);
            abort_unless($localPrivatePath, 404);

            return response()->file($localPrivatePath, [
                'Cache-Control' => 'private, no-store, max-age=0',
                'X-Content-Type-Options' => 'nosniff',
            ]);
        }

        // Restored legacy objects retain their exact suffix; never guess by basename.
        if (str_starts_with($path, 'uploads/')) {
            $privatePath = 'private/' . substr($path, strlen('uploads/'));
            $remote = $this->storage->fetchPrivate($privatePath);
            if ($remote || $this->storage->localPrivatePath($privatePath)) {
                return $this->serve($privatePath, $remote);
            }
        }

        $fullPath = public_path(ltrim($path, '/'));
        $real = realpath($fullPath);
        abort_unless($real && is_file($real), 404);

        $allowedRoots = [
            realpath(public_path('uploads/valid_id')),
            realpath(public_path('uploads/valid_ids')),
            realpath(public_path('uploads/evidences')),
            realpath(public_path('uploads/proof_of_residency')),
            realpath(public_path('uploads/profile_photos')),
        ];

        $allowed = false;
        foreach (array_filter($allowedRoots) as $root) {
            if (str_starts_with($real, $root . DIRECTORY_SEPARATOR) || $real === $root) {
                $allowed = true;
                break;
            }
        }

        abort_unless($allowed, 403);

        return response()->file($real, [
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
