<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageReplyMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = ContactMessage::query()
            ->latest()
            ->paginate(20);

        return view('admin.contacts.index', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactMessage $contactMessage)
    {
        return view('admin.contacts.show', [
            'message' => $contactMessage,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactMessage $contactMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactMessage $contactMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('status', 'تم حذف الرسالة بنجاح.');
    }

    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $request->validate([
            'reply' => ['required', 'string', 'min:5'],
        ]);

        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();

        Mail::to($contactMessage->email)
            ->send(new ContactMessageReplyMail(
                messageModel: $contactMessage,
                admin: $admin,
                replyBody: $request->input('reply'),
            ));

        $contactMessage->update(['status' => 'replied']);

        return back()->with('status', 'تم إرسال الرد إلى المستخدم عبر البريد الإلكتروني.');
    }
}
