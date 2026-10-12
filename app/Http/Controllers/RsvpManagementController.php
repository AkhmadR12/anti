<?php

namespace App\Http\Controllers;

use App\Models\Rsvp;
use App\Models\Wedding;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RsvpManagementController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of RSVP responses and guest book greetings for the wedding.
     */
    public function index(Request $request, Wedding $wedding): View
    {
        $this->authorize('manage', $wedding);

        $filter = $request->input('attendance');
        $search = $request->input('q');

        $query = $wedding->rsvps()->with('guest')->latest();

        if ($filter && in_array($filter, ['attending', 'not_attending', 'tentative'])) {
            $query->where('attendance', $filter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $rsvps = $query->paginate(20)->withQueryString();

        // Calculate summary metrics
        $metrics = [
            'total_responses' => $wedding->rsvps()->count(),
            'attending_count' => $wedding->rsvps()->where('attendance', 'attending')->count(),
            'total_pax' => $wedding->rsvps()->where('attendance', 'attending')->sum('guest_count'),
            'not_attending_count' => $wedding->rsvps()->where('attendance', 'not_attending')->count(),
            'tentative_count' => $wedding->rsvps()->where('attendance', 'tentative')->count(),
        ];

        return view('weddings.rsvps.index', compact('wedding', 'rsvps', 'metrics', 'filter', 'search'));
    }

    /**
     * Delete an RSVP entry or spam greeting.
     */
    public function destroy(Wedding $wedding, Rsvp $rsvp): RedirectResponse
    {
        $this->authorize('manage', $wedding);

        abort_if($rsvp->wedding_id !== $wedding->id, 403, 'Akses data RSVP tidak sah.');

        $name = $rsvp->name;
        $rsvp->delete();

        return back()->with('status', "Pesan / RSVP dari \"{$name}\" berhasil dihapus.");
    }

    /**
     * Export all RSVP entries to CSV.
     */
    public function export(Wedding $wedding): StreamedResponse
    {
        $this->authorize('manage', $wedding);

        $filename = "rsvp-" . $wedding->slug . "-" . date('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($wedding) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['No', 'Nama Tamu', 'Status Kehadiran', 'Jumlah Pax/Tamu', 'Pesan & Doa', 'Tanggal Kirim', 'Alamat IP']);

            $rsvps = $wedding->rsvps()->oldest()->get();
            $index = 1;

            foreach ($rsvps as $item) {
                $statusLabel = match($item->attendance) {
                    'attending' => 'Hadir',
                    'not_attending' => 'Tidak Hadir',
                    'tentative' => 'Masih Ragu',
                    default => $item->attendance,
                };

                fputcsv($file, [
                    $index++,
                    $item->name,
                    $statusLabel,
                    $item->guest_count,
                    $item->message ?? '-',
                    $item->created_at->format('Y-m-d H:i:s'),
                    $item->ip_address ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
