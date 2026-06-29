<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessageReply;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->get();
        return view('backend.admin.messages.index', compact('messages'));
    }

    public function reply(Request $request, ContactMessage $message)
    {
        $request->validate([
            'reply_message' => 'required|string',
        ]);

        try {
            $replySubject = 'Balasan: ' . $message->subject;
            Mail::to($message->email)->send(new ContactMessageReply(
                $replySubject,
                $request->reply_message,
                $message->name
            ));

            // Mark as read and replied when replied
            $message->update([
                'is_read' => true,
                'is_replied' => true
            ]);

            return back()->with('success', 'Balasan berhasil dikirim ke email ' . $message->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    public function markAsRead(ContactMessage $message)
    {
        $message->update(['is_read' => true]);
        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}
