<?php

namespace App\Http\Controllers;

use App\Services\OrganizerService;
use App\Models\OrganizerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Exception;

class OrganizerController extends Controller
{
    use AuthorizesRequests;

    protected OrganizerService $organizerService;

    public function __construct(OrganizerService $organizerService)
    {
        $this->organizerService = $organizerService;
    }

    public function index(Request $request)
    {
        // For Admin verifying organizers
        $this->authorize('viewAny', OrganizerProfile::class);

        $organizers = OrganizerProfile::with('user')->paginate(15);
        if ($request->wantsJson()) {
            return response()->json(['data' => $organizers]);
        }
        return view('admin.organizer-verifications.index', compact('organizers'));
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $profile = $user->organizerProfile;

        if (!$profile) {
            $profile = OrganizerProfile::create([
                'user_id' => $user->id,
                'organization_name' => $user->name,
                'owner_name' => $user->name,
                'phone' => '08123456789',
                'address' => 'Kota Cirebon',
                'status' => 'pending',
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['data' => $profile]);
        }
        return view('organizer.profile.show', compact('profile'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => 'required|string|max:150',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|string|max:255',
        ]);

        try {
            $profile = $this->organizerService->registerProfile(Auth::id(), $validated);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Organizer profile registered successfully.',
                    'data' => $profile
                ], 201);
            }
            return redirect()->route('organizer.profile.show')->with('success', 'Organizer profile registered successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request)
    {
        // The admin update path for verify/reject vs organizer update path
        if ($request->has('status') && Auth::user()->role->name === 'Admin') {
            // Admin approving/rejecting
            $profile = OrganizerProfile::findOrFail($request->route('organizer_verification'));
            $this->authorize('update', $profile);

            $profile->update(['status' => $request->input('status')]);
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Status updated']);
            }
            return back()->with('success', 'Status updated');
        }

        $profile = Auth::user()->organizerProfile;

        if (!$profile) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Organizer profile not found.'], 404);
            }
            return back()->withErrors(['error' => 'Organizer profile not found.']);
        }

        $this->authorize('update', $profile);

        $validated = $request->validate([
            'organization_name' => 'sometimes|string|max:150',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|string|max:255',
        ]);

        try {
            $this->organizerService->updateProfile($profile->id, $validated);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Organizer profile updated successfully.']);
            }
            return back()->with('success', 'Organizer profile updated successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function submitForReview(Request $request)
    {
        $profile = Auth::user()->organizerProfile;

        if (!$profile) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Organizer profile not found.'], 404);
            }
            return back()->withErrors(['error' => 'Organizer profile not found.']);
        }

        $this->authorize('update', $profile);

        try {
            $this->organizerService->submitForReview($profile->id);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Organizer profile submitted for review successfully.']);
            }
            return back()->with('success', 'Profile submitted for review.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function documents(Request $request)
    {
        $profile = Auth::user()->organizerProfile;
        if (!$profile) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Organizer profile not found.'], 404);
            }
            return redirect()->route('organizer.dashboard')->withErrors(['error' => 'Organizer profile not found.']);
        }

        $documents = $profile->documents ?? collect([]);
        if ($request->wantsJson()) {
            return response()->json(['data' => $documents]);
        }
        return view('organizer.documents.index', compact('documents'));
    }

    public function uploadDocument(Request $request)
    {
        $profile = Auth::user()->organizerProfile;
        if (!$profile) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Organizer profile not found.'], 404);
            }
            return back()->withErrors(['error' => 'Organizer profile not found.']);
        }

        $request->validate([
            'document_type' => 'required|string|max:100',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'file.required' => 'File dokumen wajib diunggah.',
            'file.mimes' => 'Format file harus berupa PDF, JPG, JPEG, atau PNG.',
            'file.max' => 'Ukuran file maksimal adalah 5MB.',
        ]);

        try {
            $filePath = $request->file('file')->store('organizer-documents', 'public');

            \App\Models\OrganizerDocument::create([
                'organizer_profile_id' => $profile->id,
                'document_type' => $request->document_type,
                'file_path' => $filePath,
                'verification_status' => 'pending',
            ]);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Dokumen berhasil diunggah.']);
            }
            return back()->with('success', 'Dokumen persyaratan ' . $request->document_type . ' berhasil diunggah!');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
