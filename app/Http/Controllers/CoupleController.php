<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCoupleRequest;
use App\Models\Wedding;
use App\Services\ImageStorageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class CoupleController extends Controller
{
    use AuthorizesRequests;

    /**
     * Update the couple data for the given wedding.
     */
    public function update(UpdateCoupleRequest $request, Wedding $wedding, ImageStorageService $storageService): RedirectResponse
    {
        $this->authorize('manage', $wedding);

        $couple = $wedding->couple;
        $data = $request->validated();

        // Handle groom photo replacement
        if ($request->hasFile('groom_photo')) {
            if ($couple->groom_photo) {
                $storageService->deleteImage($couple->groom_photo, $wedding->id);
            }
            $data['groom_photo'] = $storageService->storeImage($request->file('groom_photo'), $wedding->id, 'couple');
        }

        // Handle bride photo replacement
        if ($request->hasFile('bride_photo')) {
            if ($couple->bride_photo) {
                $storageService->deleteImage($couple->bride_photo, $wedding->id);
            }
            $data['bride_photo'] = $storageService->storeImage($request->file('bride_photo'), $wedding->id, 'couple');
        }

        $couple->update($data);

        return back()->with('status', 'Data mempelai pria & wanita berhasil diperbarui.');
    }
}
