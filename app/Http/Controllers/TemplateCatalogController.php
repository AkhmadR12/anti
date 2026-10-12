<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Wedding;
use App\Services\TemplateRendererService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateCatalogController extends Controller
{
    /**
     * Display catalog of available wedding invitation templates.
     */
    public function index(Request $request): View
    {
        $templates = Template::where('is_active', true)->get();
        
        $userWeddings = collect();
        if ($request->user()) {
            $userWeddings = $request->user()->weddings()->get(['id', 'title', 'template_id']);
        }

        return view('templates.index', compact('templates', 'userWeddings'));
    }

    /**
     * Apply selected template to a user's wedding.
     */
    public function select(Request $request, Template $template): RedirectResponse
    {
        $request->validate([
            'wedding_id' => ['required', 'exists:weddings,id'],
        ]);

        // Secure tenancy scoping: ensure the target wedding belongs to the authenticated user
        $wedding = $request->user()->weddings()->findOrFail($request->wedding_id);
        $wedding->update(['template_id' => $template->id]);

        return redirect()->route('weddings.edit', $wedding)
            ->with('status', "Template berhasil diubah menjadi: {$template->name}");
    }

    /**
     * Live demo preview of a template with sample data.
     */
    public function demo(string $slug, TemplateRendererService $renderer): View
    {
        $template = Template::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // Load the sample seeded wedding or fallback to any published wedding
        $sampleWedding = Wedding::with(['couple', 'events', 'galleries', 'settings', 'gifts', 'rsvps'])
            ->where('slug', 'budi-siti')
            ->first() ?? Wedding::first();

        abort_if(!$sampleWedding, 404, 'Data contoh tidak ditemukan.');

        // Temporarily override template for this demo view instance
        $sampleWedding->setRelation('template', $template);

        return $renderer->render($sampleWedding, null, true);
    }
}
