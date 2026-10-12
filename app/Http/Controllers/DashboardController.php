<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard with their isolated weddings and stats.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Data isolation: strictly scope query to the authenticated user
        $weddings = $user->weddings()
            ->with(['couple', 'template', 'events', 'settings'])
            ->withCount(['guests', 'rsvps'])
            ->latest()
            ->get();

        $stats = [
            'total_weddings' => $weddings->count(),
            'published_weddings' => $weddings->where('status', 'published')->count(),
            'total_guests' => $weddings->sum('guests_count'),
            'total_rsvps' => $weddings->sum('rsvps_count'),
        ];

        return view('dashboard', compact('user', 'weddings', 'stats'));
    }
}
