<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\President;
use Inertia\Inertia;

class UserPresidentController extends Controller
{
    public function index()
    {
        $presidents = President::with('team')->orderBy('name')->get();

        return Inertia::render('user/President', [
            'presidents' => $presidents,
        ]);
    }
}
