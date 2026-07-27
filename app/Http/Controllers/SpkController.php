<?php

namespace App\Http\Controllers;

use App\Services\CooperationAgreementService;
use App\Models\CooperationAgreement;
use App\Http\Requests\SignSpkRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Exception;

class SpkController extends Controller
{
    use AuthorizesRequests;

    protected CooperationAgreementService $spkService;

    public function __construct(CooperationAgreementService $spkService)
    {
        $this->spkService = $spkService;
    }

    /**
     * Display a listing of SPKs.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', CooperationAgreement::class);
        return response()->json([
            'message' => 'SPK list endpoint.'
        ]);
    }

    /**
     * Sign the SPK by Organizer.
     */
    public function sign(SignSpkRequest $request)
    {
        try {
            $agreement = $this->spkService->signSpk(auth()->user()->organizerProfile->id, $request->validated());
            return response()->json([
                'message' => 'SPK signed successfully.',
                'data' => $agreement
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Approve the SPK (Admin).
     */
    public function approve(Request $request, CooperationAgreement $agreement)
    {
        $this->authorize('approve', $agreement);

        try {
            $this->spkService->approveSpk($agreement->id);
            return response()->json(['message' => 'SPK approved successfully.']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Reject the SPK (Admin).
     */
    public function reject(Request $request, CooperationAgreement $agreement)
    {
        $this->authorize('reject', $agreement);

        try {
            $this->spkService->rejectSpk($agreement->id, $request->input('reason', ''));
            return response()->json(['message' => 'SPK rejected successfully.']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
