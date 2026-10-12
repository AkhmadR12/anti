<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateWeddingSettingRequest;
use App\Models\Wedding;
use App\Services\ImageStorageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class WeddingSettingController extends Controller
{
    use AuthorizesRequests;

    /**
     * Update the visual and feature settings for the wedding.
     */
    public function update(UpdateWeddingSettingRequest $request, Wedding $wedding, ImageStorageService $storageService): RedirectResponse
    {
        $this->authorize('manage', $wedding);

        $settings = $wedding->settings;
        $data = $request->validated();

        // Convert checkbox boolean values
        $data['show_gift'] = $request->boolean('show_gift');
        $data['show_rsvp'] = $request->boolean('show_rsvp');
        $data['show_guestbook'] = $request->boolean('show_guestbook');

        // Handle background hero image upload
        if ($request->hasFile('background_image')) {
            if ($settings->background_image) {
                $storageService->deleteImage($settings->background_image, $wedding->id);
            }
            $data['background_image'] = $storageService->storeImage($request->file('background_image'), $wedding->id, 'background');
        }

        $settings->update($data);

        return back()->with('status', 'Pengaturan tema, musik, dan fitur berhasil disimpan.');
    }
}
