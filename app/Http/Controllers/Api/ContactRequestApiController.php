<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ContactRequestApiController extends Controller
{
    /**
     * Display a listing of contact requests.
     */
    public function index()
    {
        Artisan::call('migrate');
        $contactRequests = ContactRequest::orderBy('created_at', 'desc')->get();
        return response()->json($contactRequests);
    }

    /**
     * Display the specified contact request.
     */
    public function show($id)
    {
        $contactRequest = ContactRequest::findOrFail($id);
        return response()->json($contactRequest);
    }

    /**
     * Store a newly created contact request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'question' => 'required|string|max:255',
            'comment' => 'nullable|string',
        ]);

        $contactRequest = ContactRequest::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Əlaqə forması uğurla göndərildi',
            'data' => $contactRequest
        ], 201);
    }

    /**
     * Update contact request status.
     */
    public function updateStatus($id)
    {
        $contactRequest = ContactRequest::findOrFail($id);
        $contactRequest->status = !$contactRequest->status;
        $contactRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Status uğurla yeniləndi',
            'data' => $contactRequest
        ]);
    }

    /**
     * Remove the specified contact request.
     */
    public function destroy($id)
    {
        $contactRequest = ContactRequest::findOrFail($id);
        $contactRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Əlaqə forması uğurla silindi'
        ]);
    }
}
