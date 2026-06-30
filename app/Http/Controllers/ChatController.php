<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\User;

class ChatController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CUSTOMER
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $customerId = Auth::guard('customer')->id();

        Chat::where(
            'customer_id',
            $customerId
        )
        ->where(
            'sender',
            'admin'
        )
        ->update([

            'is_read' => true

        ]);

        $messages = Chat::with([
                'customer',
                'admin'
            ])
            ->where(
                'customer_id',
                $customerId
            )
            ->orderBy('created_at')
            ->get();

        return view(
            'customer.chat',
            compact('messages')
        );
    }


    public function send(Request $request)
    {
        $request->validate([

            'message' => 'nullable|string',

            'image' => 'nullable|image|max:2048'

        ]);


        if (

            !$request->filled('message')
            && !$request->hasFile('image')

        ) {

            return response()->json([

                'error' => 'Pesan atau gambar wajib diisi'

            ], 422);

        }


        $image = null;

        if ($request->hasFile('image')) {

            $image = $request
                ->file('image')
                ->store(
                    'chat',
                    'public'
                );

        }

        Chat::create([

            'customer_id' => Auth::guard('customer')->id(),

            'message' => $request->message,

            'image' => $image,

            'sender' => 'customer',

            'is_read' => false

        ]);

        return response()->json([
            'success' => true
        ]);
    }


    public function fetch()
    {
        $customerId =
            Auth::guard('customer')->id();

        Chat::where(
            'customer_id',
            $customerId
        )
        ->where(
            'sender',
            'admin'
        )
        ->update([

            'is_read' => true

        ]);

        $messages = Chat::with([
                'customer',
                'admin'
            ])
            ->where(
                'customer_id',
                $customerId
            )
            ->orderBy('created_at')
            ->get();

        return response()->json(
            $messages
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN & KASIR
    |--------------------------------------------------------------------------
    */

    public function adminIndex()
    {
        $customers = Chat::select('customer_id')
            ->distinct()
            ->with('customer')
            ->get();

        return view(
            'chat.index',
            compact('customers')
        );
    }


    public function adminShow($customerId)
    {
        $customer =
            User::findOrFail($customerId);

        Chat::where(
            'customer_id',
            $customerId
        )
        ->where(
            'sender',
            'customer'
        )
        ->update([

            'is_read' => true

        ]);

        $messages = Chat::where(
                'customer_id',
                $customerId
            )
            ->orderBy('created_at')
            ->get();

        return view(
            'chat.show',
            compact(
                'customer',
                'messages'
            )
        );
    }


    public function adminSend(
        Request $request,
        $customerId
    ) {

        $request->validate([

            'message' => 'nullable|string',

            'image' => 'nullable|image|max:2048'

        ]);


        if (

            !$request->filled('message')
            && !$request->hasFile('image')

        ) {

            return response()->json([

                'error' => 'Pesan atau gambar wajib diisi'

            ], 422);

        }


        $image = null;

        if ($request->hasFile('image')) {

            $image = $request
                ->file('image')
                ->store(
                    'chat',
                    'public'
                );

        }

        Chat::create([

            'customer_id' => $customerId,

            'admin_id' => Auth::id(),

            'message' => $request->message,

            'image' => $image,

            'sender' => 'admin',

            'is_read' => false

        ]);

        if ($request->expectsJson()) {

            return response()->json([
                'success' => true
            ]);

        }

        return back();
    }


    public function adminFetch($customerId)
    {
        Chat::where(
            'customer_id',
            $customerId
        )
        ->where(
            'sender',
            'customer'
        )
        ->update([

            'is_read' => true

        ]);

        $messages = Chat::where(
                'customer_id',
                $customerId
            )
            ->orderBy('created_at')
            ->get();

        return response()->json(
            $messages
        );
    }
}