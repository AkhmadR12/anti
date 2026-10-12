<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGalleryRequest;
use App\Models\Gallery;
use App\Models\Wedding;
use App\Services\ImageStorageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store multiple newly uploaded gallery images.
     */
    public function store(StoreGalleryRequest $request, Wedding $wedding, ImageStorageService $storageService): RedirectResponse
    {
        $this->authorize('manage', $wedding);

        $maxSort = $wedding->galleries()->max('sort_order') ?? 0;
        $caption = $request->input('caption');

        foreach ($request->file('images') as $file) {
            $maxSort++;
            $path = $storageService->storeImage($file, $wedding->id, 'gallery');

            $wedding->galleries()->create([
                'image_path' => $path,
                'caption' => $caption,
                'sort_order' => $maxSort,
            ]);
        }

        return back()->with('status', 'Foto galeri berhasil diunggah.');
    }

    /**
     * Delete a gallery image from storage and database.
     */
    public function destroy(Wedding $wedding, Gallery $gallery, ImageStorageService $storageService): RedirectResponse
    {
        $this->authorize('manage', $wedding);

        abort_if($gallery->wedding_id !== $wedding->id, 403, 'Akses foto galeri tidak sah.');

        $storageService->deleteImage($gallery->image_path, $wedding->id);
        $gallery->delete();

        return back()->with('status', 'Foto galeri berhasil dihapus.');
    }

    /**
     * Reorder gallery images.
     */
    public function reorder(Request $request, Wedding $wedding): JsonResponse|RedirectResponse
    {
        $this->authorize('manage', $wedding);

        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:galleries,id'],
        ]);

        foreach ($request->order as $index => $galleryId) {
            $wedding->galleries()->where('id', $galleryId)->update(['sort_order' => $index + 1]);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('status', 'Urutan foto galeri berhasil diperbarui.');
    }
}
