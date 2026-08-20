<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the contact messages.
     */
    public function index(Request $request)
    {
        $messages = ContactMessage::query()
            ->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%");
        })
            ->when($request->status !== null, function ($query) use ($request) {
            $query->where('is_read', $request->status);
        })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.contact-messages.index', compact('messages'));
    }

    /**
     * Display the specified contact message.
     */
    public function show(ContactMessage $message)
    {
        $contactMessage = $message;
        // Mark as read when viewed
        if (!$contactMessage->is_read) {
            $contactMessage->update(['is_read' => true]);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    /**
     * Remove the specified contact message from storage.
     */
    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}
