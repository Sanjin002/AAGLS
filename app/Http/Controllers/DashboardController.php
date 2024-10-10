<?php

namespace App\Http\Controllers;

use App\Models\Parcel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recentParcels = Parcel::where('user_id', $user->id)
                               ->latest()
                               ->take(5)
                               ->get();

        $parcelStats = [
            'pending' => Parcel::where('status', 'pending')->count(),
            'sent' => Parcel::where('status', 'sent')->count(),
            'printed' => Parcel::where('status', 'printed')->count(),
        ];

        return view('dashboard', compact('recentParcels', 'parcelStats'));
    }
}