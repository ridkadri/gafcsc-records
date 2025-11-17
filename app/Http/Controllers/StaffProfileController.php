<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class StaffProfileController extends Controller
{
    /**
     * Display the staff's own profile
     */
    public function show()
    {
        $user = auth()->user();
        
        // Check if user has associated staff record
        if (!$user->staff_id) {
            return redirect()->route('dashboard')
                ->with('error', 'No staff profile found for your account.');
        }
        
        $staff = Staff::with(['documents' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($user->staff_id);
        
        // Initialize all document types with empty collections
        $documentsByType = collect([
            StaffDocument::TYPE_CERTIFICATE => collect(),
            StaffDocument::TYPE_ID_CARD => collect(),
            StaffDocument::TYPE_MEDICAL => collect(),
            StaffDocument::TYPE_QUALIFICATION => collect(),
        ]);
        
        // Group existing documents by type and merge with initialized types
        $documentsByType = $documentsByType->merge(
            $staff->documents->groupBy('document_type')
        );
        
        return view('staff.profile.show', compact('staff', 'documentsByType'));
    }

    /**
     * Upload profile picture
     */
    public function uploadProfilePicture(Request $request)
    {
        \Log::info('=== UPLOAD STARTED ===');
        \Log::info('User ID: ' . auth()->id());
        \Log::info('User staff_id: ' . auth()->user()->staff_id);
        \Log::info('Has file: ' . ($request->hasFile('profile_picture') ? 'YES' : 'NO'));
        
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        $user = auth()->user();
        $staff = Staff::findOrFail($user->staff_id);
        
        \Log::info('Staff found', ['id' => $staff->id, 'name' => $staff->name]);
        \Log::info('Old picture: ' . ($staff->profile_picture ?? 'NULL'));

        // Delete old profile picture if exists
        if ($staff->profile_picture && Storage::disk('public')->exists($staff->profile_picture)) {
            Storage::disk('public')->delete($staff->profile_picture);
            \Log::info('Deleted old picture: ' . $staff->profile_picture);
        }

        // Store new profile picture
        $file = $request->file('profile_picture');
        $filename = 'profile_' . $staff->id . '_' . time() . '.' . $file->extension();
        $path = $file->storeAs('profile_pictures', $filename, 'public');
        
        \Log::info('File stored at: ' . $path);
        \Log::info('File exists: ' . (Storage::disk('public')->exists($path) ? 'YES' : 'NO'));

        // Update staff record
        \Log::info('Attempting update with path: ' . $path);
        $updateResult = $staff->update(['profile_picture' => $path]);
        \Log::info('Update result: ' . ($updateResult ? 'TRUE' : 'FALSE'));
        
        // Verify it saved
        $staff->refresh();
        \Log::info('After refresh - picture in DB: ' . ($staff->profile_picture ?? 'NULL'));
        
        \Log::info('=== UPLOAD COMPLETE ===');

        return redirect()->route('staff.profile.show')
            ->with('success', 'Profile picture updated successfully!')
            ->with('image_updated', time()); // Cache buster
    }

    /**
     * Upload document
     */
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:certificate,id_card,medical,qualification',
            'document_name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB max
            'description' => 'nullable|string|max:1000',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
        ]);

        $user = auth()->user();
        $staff = Staff::findOrFail($user->staff_id);

        // Store file
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filename = time() . '_' . $originalName;
        $path = $file->storeAs('staff_documents/' . $staff->id, $filename, 'public');

        // Create document record
        StaffDocument::create([
            'staff_id' => $staff->id,
            'uploaded_by' => $user->id,
            'document_type' => $request->document_type,
            'document_name' => $request->document_name,
            'file_path' => $path,
            'file_name' => $originalName,
            'file_type' => $file->extension(),
            'file_size' => $file->getSize(),
            'description' => $request->description,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('staff.profile.show')
            ->with('success', 'Document uploaded successfully!');
    }

    /**
     * Download document
     */
    public function downloadDocument(StaffDocument $document)
    {
        $user = auth()->user();
        
        // Check permissions: user can download their own documents or if admin
        if ($document->staff_id !== $user->staff_id && !$user->canViewAllStaff()) {
            abort(403, 'You do not have permission to download this document.');
        }

        if (!Storage::exists($document->file_path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return Storage::download($document->file_path, $document->file_name);
    }

    /**
     * Delete document
     */
    public function deleteDocument(StaffDocument $document)
    {
        $user = auth()->user();
        
        // Check permissions: user can delete their own documents or if super admin
        if ($document->staff_id !== $user->staff_id && !$user->isSuperAdmin()) {
            abort(403, 'You do not have permission to delete this document.');
        }

        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully!');
    }

    /**
     * Admin: Upload document for any staff
     */
    public function adminUploadDocument(Request $request, Staff $staff)
    {
        $user = auth()->user();
        
        // Only admins who can manage staff
        if (!$user->canManageStaff()) {
            abort(403, 'You do not have permission to upload documents.');
        }

        $request->validate([
            'document_type' => 'required|in:certificate,id_card,medical,qualification',
            'document_name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'description' => 'nullable|string|max:1000',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
        ]);

        // Store file
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filename = time() . '_' . $originalName;
        $path = $file->storeAs('staff_documents/' . $staff->id, $filename, 'public');

        // Create document record
        StaffDocument::create([
            'staff_id' => $staff->id,
            'uploaded_by' => $user->id,
            'document_type' => $request->document_type,
            'document_name' => $request->document_name,
            'file_path' => $path,
            'file_name' => $originalName,
            'file_type' => $file->extension(),
            'file_size' => $file->getSize(),
            'description' => $request->description,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('staff.show', $staff)
            ->with('success', 'Document uploaded successfully!');
    }

    /**
     * Admin: Upload profile picture for any staff
     */
    public function adminUploadProfilePicture(Request $request, Staff $staff)
    {
        $user = auth()->user();
        
        // Only admins who can manage staff
        if (!$user->canManageStaff()) {
            abort(403, 'You do not have permission to upload profile pictures.');
        }

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Delete old profile picture if exists
        if ($staff->profile_picture && Storage::exists($staff->profile_picture)) {
            Storage::delete($staff->profile_picture);
        }

        // Store new profile picture
        $file = $request->file('profile_picture');
        $filename = 'profile_' . $staff->id . '_' . time() . '.' . $file->extension();
        $path = $file->storeAs('profile_pictures', $filename, 'public');

        // Update staff record
        $staff->update(['profile_picture' => $path]);

        return redirect()->route('staff.show', $staff)
            ->with('success', 'Profile picture updated successfully!');
    }
}