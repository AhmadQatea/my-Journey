<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageRequest;
use App\Http\Requests\FeedbackRequest;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\ContactMessage;
use App\Models\Feedback;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view('website.pages.contactandfeedbacl', [
            'user' => $user,
        ]);
    }

    public function storeContact(ContactMessageRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $message = ContactMessage::create([
            'user_id' => $user?->id,
            'name' => $request->input('name', $user?->full_name),
            'email' => $request->input('email', $user?->email),
            'subject' => $request->input('subject'),
            'topic' => $request->input('topic'),
            'message' => $request->input('message'),
        ]);

        // إشعار جميع المسؤولين بوجود رسالة جديدة
        $admins = Admin::query()->where('is_active', true)->get();

        foreach ($admins as $admin) {
            AdminNotification::create([
                'admin_id' => $admin->id,
                'type' => 'contact_message',
                'title' => 'رسالة تواصل جديدة',
                'message' => 'قام '.($message->name ?: 'مستخدم').' بإرسال رسالة جديدة عبر نموذج الاتصال.',
                'icon' => 'fas fa-envelope-open-text',
                'color' => 'info',
                'link' => route('admin.contact-messages.show', $message),
                'data' => [
                    'contact_message_id' => $message->id,
                    'name' => $message->name,
                    'email' => $message->email,
                ],
                'is_read' => false,
            ]);
        }

        return back()->with('status', 'تم إرسال رسالتك بنجاح، سنقوم بالرد عليك في أقرب وقت ممكن.');
    }

    public function storeFeedback(FeedbackRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $feedback = Feedback::create([
            'user_id' => $user?->id,
            'name' => $request->input('name', $user?->full_name),
            'rating' => $request->integer('rating'),
            'comments' => $request->input('comments'),
            'likes' => $request->input('likes', []),
        ]);

        // إشعار جميع المسؤولين بتقييم/ملاحظة جديدة
        $admins = Admin::query()->where('is_active', true)->get();

        foreach ($admins as $admin) {
            AdminNotification::create([
                'admin_id' => $admin->id,
                'type' => 'feedback',
                'title' => 'تقييم / ملاحظة جديدة',
                'message' => 'قام '.($feedback->name ?: 'مستخدم').' بإرسال تقييم جديد بقيمة '.$feedback->rating.'/5.',
                'icon' => 'fas fa-comment-dots',
                'color' => 'success',
                'link' => route('admin.feedback.show', $feedback),
                'data' => [
                    'feedback_id' => $feedback->id,
                    'name' => $feedback->name,
                    'rating' => $feedback->rating,
                ],
                'is_read' => false,
            ]);
        }

        return back()->with('status', 'شكراً لك على تقييمك وملاحظاتك!');
    }
}
