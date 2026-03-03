<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        // For now, let's just get the groups the user belongs to
        $groups = auth()->user()->joinedGroups()->with('creator', 'users')->get();

        return view('chat.index', compact('users', 'groups'));
    }

    public function show(Group $group)
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $groups = auth()->user()->joinedGroups()->with('creator', 'users')->get();

        $messages = $group->messages()->with('sender', 'media')->oldest()->get();

        return view('chat.show', compact('users', 'groups', 'group', 'messages'));
    }

    public function sendMessage(Request $request, Group $group)
    {
        $request->validate([
            'content' => 'required_without:media',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp3,wav,mp4,mov|max:10240',
        ]);

        $message = $group->messages()->create([
            'sender_id' => auth()->id(),
            'content' => $request->content,
            'type' => $request->hasFile('media') ? 'media' : 'text',
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('chat_media', 'public');
                $message->media()->create([
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                ]);
            }
        }

        broadcast(new MessageSent($message))->toOthers();

        return back();
    }

    public function startChat(User $user)
    {
        // Check if a private chat already exists
        $group = Group::where('is_group', false)
            ->whereHas('users', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->first();

        if (!$group) {
            $group = Group::create([
                'user_id' => auth()->id(),
                'name' => null,
                'is_group' => false,
            ]);

            $group->groupUsers()->createMany([
                ['user_id' => auth()->id(), 'add_user_id' => auth()->id()],
                ['user_id' => $user->id, 'add_user_id' => auth()->id()],
            ]);
        }

        return redirect()->route('chat.show', $group);
    }
}
