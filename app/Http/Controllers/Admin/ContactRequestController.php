<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    /**
     * Display a listing of the contact requests.
     */
    public function index()
    {
        $contactRequests = ContactRequest::orderBy('created_at', 'desc')->get();
        return view('back.admin.contact-requests.index', compact('contactRequests'));
    }

    /**
     * Display the specified contact request.
     */
    public function show($id)
    {
        $contactRequest = ContactRequest::findOrFail($id);
        return view('back.admin.contact-requests.show', compact('contactRequest'));
    }

    /**
     * Remove the specified contact request.
     */
    public function destroy($id)
    {
        $contactRequest = ContactRequest::findOrFail($id);
        $contactRequest->delete();

        return redirect()->route('back.pages.contact-requests.index')
            ->with('success', 'Əlaqə forması uğurla silindi.');
    }

    /**
     * Toggle status of the specified contact request.
     */
    public function toggleStatus($id)
    {
        $contactRequest = ContactRequest::findOrFail($id);
        $contactRequest->status = !$contactRequest->status;
        $contactRequest->save();

        return redirect()->route('back.pages.contact-requests.index')
            ->with('success', 'Status uğurla dəyişdirildi.');
    }
}
