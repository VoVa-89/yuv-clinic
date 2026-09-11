<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Service;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceAdminController extends Controller
{
    public function index(): View
    {
        $services = Service::query()->orderBy('sort_order')->paginate(20);

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        $doctors = Doctor::query()->orderBy('name')->get();

        return view('admin.services.create', compact('doctors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, null);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['body'] = HtmlSanitizer::clean($data['body'] ?? '');
        $data['is_published'] = $request->boolean('is_published');
        $doctorIds = $request->input('doctor_ids', []);

        $service = Service::query()->create($data);
        $service->doctors()->sync($doctorIds);

        return redirect()->route('admin.services.index')->with('status', 'Услуга создана.');
    }

    public function edit(Service $service): View
    {
        $doctors = Doctor::query()->orderBy('name')->get();
        $service->load('doctors');

        return view('admin.services.edit', compact('service', 'doctors'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $this->validated($request, $service);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['body'] = HtmlSanitizer::clean($data['body'] ?? '');
        $data['is_published'] = $request->boolean('is_published');
        $service->update($data);
        $service->doctors()->sync($request->input('doctor_ids', []));

        return redirect()->route('admin.services.index')->with('status', 'Услуга обновлена.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('status', 'Услуга удалена.');
    }

    private function validated(Request $request, ?Service $service): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable', 'string', 'max:255', 'alpha_dash',
                Rule::unique('services', 'slug')->ignore($service?->id),
            ],
            'short_description' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'price_text' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_published' => ['nullable'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'doctor_ids' => ['array'],
            'doctor_ids.*' => ['exists:doctors,id'],
        ]);
    }
}
