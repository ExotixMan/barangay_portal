<?php

namespace App\Http\Middleware;

use App\Models\ResidentProfileCorrectionConcern;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NoPendingProfileCorrection
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $pendingConcern = ResidentProfileCorrectionConcern::query()
            ->where('resident_id', Auth::id())
            ->whereIn('status', ['pending', 'reviewed'])
            ->latest()
            ->first();

        if (! $pendingConcern) {
            return $next($request);
        }

        $concernReference = $pendingConcern->formatted_id;

        $message = 'Your profile correction request is still pending review. Please wait until the barangay approves/corrects it before submitting another service request.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'pending_correction' => [
                    'id' => $pendingConcern->id,
                    'reference' => $concernReference,
                    'service' => $pendingConcern->service,
                    'field_name' => $pendingConcern->field_name,
                    'status' => $pendingConcern->status,
                    'submitted_at' => optional($pendingConcern->created_at)->format('M d, Y h:i A'),
                ],
            ], 423);
        }

        return redirect()
            ->route('profile')
            ->with('warning', $message)
            ->with('pending_correction_id', $pendingConcern->id);
    }
}