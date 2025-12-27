<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\FeedbackReplyMail;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedback = Feedback::query()
            ->latest()
            ->paginate(20);

        return view('admin.feedback.index', compact('feedback'));
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
    public function show(Feedback $feedback)
    {
        return view('admin.feedback.show', [
            'feedback' => $feedback,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feedback $feedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()
            ->route('admin.feedback.index')
            ->with('status', 'تم حذف التقييم بنجاح.');
    }

    public function reply(Request $request, Feedback $feedback)
    {
        $request->validate([
            'reply' => ['required', 'string', 'min:5'],
        ]);

        // يمكننا الرد فقط إذا كان هناك مستخدم مرتبط بالتقييم
        if (! $feedback->user || ! $feedback->user->email) {
            return back()->with('status', 'لا يمكن إرسال رد بالبريد لعدم توفر بريد إلكتروني للمستخدم.');
        }

        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();

        Mail::to($feedback->user->email)
            ->send(new FeedbackReplyMail(
                feedback: $feedback,
                admin: $admin,
                replyBody: $request->input('reply'),
            ));

        return back()->with('status', 'تم إرسال الرد على تقييم المستخدم عبر البريد الإلكتروني.');
    }
}
