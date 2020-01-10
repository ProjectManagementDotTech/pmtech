<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        $settings = $user->settings;

        if($request->has('last_visited_view')) {
            if($settings->last_visited_view != $request->last_visited_view) {
                $settings->last_visited_view = $request->last_visited_view;
            }
        }

        $settings->save();

        return response('', 204);
    }
}
