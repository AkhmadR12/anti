<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportGuestRequest;
use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Models\Guest;
use App\Models\Wedding;
use App\Services\Contracts\WhatsAppServiceInterface;
use App\Services\WhatsApp\WhatsAppNormalizer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GuestController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of guests for the wedding.
     */
    public function index(Request $request, Wedding $wedding, WhatsAppServiceInterface $whatsAppService): View
    {
        $this->authorize('manage', $wedding);

        $statusFilter = $request->input('status');
        $search = $request->input('q');

        $query = $wedding->guests()->latest();

        if ($statusFilter && in_array($statusFilter, ['pending', 'sent', 'opened', 'confirmed'])) {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $guests = $query->paginate(20)->withQueryString();

        // Calculate summary counts
        $counts = [
            'total' => $wedding->guests()->count(),
            'pending' => $wedding->guests()->where('status', 'pending')->count(),
            'sent' => $wedding->guests()->where('status', 'sent')->count(),
            'opened' => $wedding->guests()->where('status', 'opened')->count(),
            'confirmed' => $wedding->guests()->where('status', 'confirmed')->count(),
        ];

        // Attach WhatsApp share URL to each guest in current page
        foreach ($guests as $guest) {
            $guest->share_url = $whatsAppService->generateShareUrl($guest, $wedding);
            $guest->invitation_url = route('invitation.guest', [
                'slug' => $wedding->slug,
                'guestSlug' => $guest->slug,
            ]);
        }

        return view('weddings.guests.index', compact('wedding', 'guests', 'counts', 'statusFilter', 'search'));
    }

    /**
     * Store a newly created guest in storage.
     */
    public function store(StoreGuestRequest $request, Wedding $wedding): RedirectResponse
    {
        $this->authorize('manage', $wedding);

        $name = trim($request->name);
        $slug = $this->generateUniqueGuestSlug($wedding, $name);
        $normalizedPhone = WhatsAppNormalizer::normalize($request->whatsapp_number);

        $wedding->guests()->create([
            'name' => $name,
            'whatsapp_number' => $normalizedPhone,
            'slug' => $slug,
            'status' => 'pending',
        ]);

        return back()->with('status', "Tamu \"{$name}\" berhasil ditambahkan.");
    }

    /**
     * Update the specified guest in storage.
     */
    public function update(UpdateGuestRequest $request, Wedding $wedding, Guest $guest): RedirectResponse
    {
        $this->authorize('manage', $wedding);

        abort_if($guest->wedding_id !== $wedding->id, 403, 'Akses tamu tidak sah.');

        $name = trim($request->name);
        $slug = $guest->slug;

        // If name changed, regenerate slug
        if ($name !== $guest->name) {
            $slug = $this->generateUniqueGuestSlug($wedding, $name, $guest->id);
        }

        $normalizedPhone = WhatsAppNormalizer::normalize($request->whatsapp_number);

        $guest->update([
            'name' => $name,
            'whatsapp_number' => $normalizedPhone,
            'slug' => $slug,
            'status' => $request->status,
        ]);

        return back()->with('status', "Data tamu \"{$name}\" berhasil diperbarui.");
    }

    /**
     * Remove the specified guest from storage.
     */
    public function destroy(Wedding $wedding, Guest $guest): RedirectResponse
    {
        $this->authorize('manage', $wedding);

        abort_if($guest->wedding_id !== $wedding->id, 403, 'Akses tamu tidak sah.');

        $name = $guest->name;
        $guest->delete();

        return back()->with('status', "Tamu \"{$name}\" berhasil dihapus.");
    }

    /**
     * Mark a guest's status as 'sent' after WhatsApp message opened.
     */
    public function markSent(Wedding $wedding, Guest $guest): RedirectResponse
    {
        $this->authorize('manage', $wedding);

        abort_if($guest->wedding_id !== $wedding->id, 403, 'Akses tamu tidak sah.');

        if ($guest->status === 'pending') {
            $guest->update(['status' => 'sent']);
        }

        return back()->with('status', "Status undangan untuk {$guest->name} diubah menjadi 'Terkirim'.");
    }

    /**
     * Import guests from a CSV file.
     */
    public function import(ImportGuestRequest $request, Wedding $wedding): RedirectResponse
    {
        $this->authorize('manage', $wedding);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        
        if ($handle === false) {
            return back()->withErrors(['file' => 'Gagal membaca berkas CSV.']);
        }

        $imported = 0;
        $rowNumber = 0;

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $rowNumber++;

            // Handle semicolon delimiter if needed
            if (count($row) === 1 && str_contains($row[0], ';')) {
                $row = explode(';', $row[0]);
            }

            // Skip header if first row has "nama" or "name"
            if ($rowNumber === 1 && isset($row[0])) {
                $headerCandidate = strtolower(trim($row[0]));
                if (in_array($headerCandidate, ['nama', 'name', 'nama tamu', 'guest name'])) {
                    continue;
                }
            }

            $name = isset($row[0]) ? trim($row[0]) : '';
            if (blank($name)) {
                continue;
            }

            $phone = isset($row[1]) ? trim($row[1]) : null;
            $normalizedPhone = WhatsAppNormalizer::normalize($phone);
            $slug = $this->generateUniqueGuestSlug($wedding, $name);

            $wedding->guests()->create([
                'name' => $name,
                'whatsapp_number' => $normalizedPhone,
                'slug' => $slug,
                'status' => 'pending',
            ]);

            $imported++;
        }

        fclose($handle);

        return back()->with('status', "Berhasil mengimpor {$imported} data tamu dari berkas CSV.");
    }

    /**
     * Helper to generate unique slug for a guest within a specific wedding.
     */
    protected function generateUniqueGuestSlug(Wedding $wedding, string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        if (blank($baseSlug)) {
            $baseSlug = 'tamu';
        }

        $slug = $baseSlug;
        $counter = 1;

        while ($this->guestSlugExists($wedding->id, $slug, $ignoreId)) {
            $counter++;
            $slug = "{$baseSlug}-{$counter}";
        }

        return $slug;
    }

    protected function guestSlugExists(int $weddingId, string $slug, ?int $ignoreId = null): bool
    {
        $query = Guest::where('wedding_id', $weddingId)->where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
