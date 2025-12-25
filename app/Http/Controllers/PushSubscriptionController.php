<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PushSubscription;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint' => 'required',
            'keys.p256dh' => 'required',
            'keys.auth' => 'required',
        ]);

        PushSubscription::updateOrCreate(
            ['endpoint' => $request->endpoint],
            [
                'user_id' => Auth::id(),
                'p256dh' => $request->input('keys.p256dh'),
                'auth' => $request->input('keys.auth'),
            ]
        );

        return response()->json(['success' => true]);
    }

    public function unsubscribe(Request $request)
    {
        PushSubscription::where('endpoint', $request->endpoint)->delete();
        return response()->json(['success' => true]);
    }
}
