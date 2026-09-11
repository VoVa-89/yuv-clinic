<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Service;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class DoctorAdminController extends Controller
{
    public function index(): View
    {
        $doctors = Doctor::query()->orderBy('sort_order')->paginate(20);

        return view('admin.doctors.index', compact('doctors'));
    }

    public function create(): View
    {
        $services = Service::query()->orderBy('title')->get();

        return view('admin.doctors.create', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedFields($request);
        $serviceIds = array_map('intval', $validated['service_ids'] ?? []);

        unset($validated['service_ids'], $validated['photo_file']);

        $validated['bio'] = HtmlSanitizer::clean($validated['bio'] ?? '');
        $validated['is_published'] = $request->boolean('is_published');

        $manualPhoto = isset($validated['photo']) ? (string) $validated['photo'] : null;
        $validated['photo'] = $this->resolveDoctorPhotoPath($request, null, $manualPhoto);

        $validated['slug'] = $this->resolveUniqueDoctorSlug(
            $this->slugBaseFromValidated($validated),
            null
        );

        DB::transaction(function () use ($validated, $serviceIds): void {
            $doctor = Doctor::query()->create($validated);
            $doctor->services()->sync($serviceIds);
        });

        return redirect()->route('admin.doctors.index')->with('status', 'Врач добавлен.');
    }

    public function edit(Doctor $doctor): View
    {
        $services = Service::query()->orderBy('title')->get();
        $doctor->load('services');

        return view('admin.doctors.edit', compact('doctor', 'services'));
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $validated = $this->validatedFields($request);
        $serviceIds = array_map('intval', $validated['service_ids'] ?? []);

        unset($validated['service_ids'], $validated['photo_file']);

        $validated['bio'] = HtmlSanitizer::clean($validated['bio'] ?? '');
        $validated['is_published'] = $request->boolean('is_published');

        $manualPhoto = isset($validated['photo']) ? (string) $validated['photo'] : null;
        $validated['photo'] = $this->resolveDoctorPhotoPath($request, $doctor, $manualPhoto);

        $validated['slug'] = $this->resolveUniqueDoctorSlug(
            $this->slugBaseFromValidated($validated),
            $doctor->id
        );

        DB::transaction(function () use ($doctor, $validated, $serviceIds): void {
            $doctor->update($validated);
            $doctor->services()->sync($serviceIds);
        });

        return redirect()->route('admin.doctors.index')->with('status', 'Карточка врача обновлена.');
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        DB::transaction(function () use ($doctor): void {
            $doctor->reviews()->delete();
            $this->deleteStoredDoctorPhotoIfManaged($doctor->photo);
            $doctor->delete();
        });

        return redirect()->route('admin.doctors.index')->with('status', 'Врач удалён.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedFields(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'photo_file' => ['nullable', 'image', 'max:4096', 'mimes:jpeg,jpg,png,webp'],
            'photo' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_published' => ['nullable'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'service_ids' => ['sometimes', 'array'],
            'service_ids.*' => ['integer', 'exists:services,id'],
        ]);
    }

    private function slugBaseFromValidated(array $validated): string
    {
        $rawSlug = trim((string) ($validated['slug'] ?? ''));
        if ($rawSlug !== '') {
            $fromInput = Str::slug($rawSlug);
            if ($fromInput !== '') {
                return $fromInput;
            }
        }

        $fromName = Str::slug($validated['name']);

        return $fromName !== '' ? $fromName : 'doctor-'.Str::lower(Str::random(10));
    }

    private function resolveUniqueDoctorSlug(string $base, ?int $ignoreDoctorId): string
    {
        $candidate = $base;
        $suffix = 2;

        while ($this->isDoctorSlugTaken($candidate, $ignoreDoctorId)) {
            $candidate = $base.'-'.$suffix;
            $suffix++;
        }

        return $candidate;
    }

    private function isDoctorSlugTaken(string $slug, ?int $ignoreDoctorId): bool
    {
        $query = Doctor::query()->where('slug', $slug);

        if ($ignoreDoctorId !== null) {
            $query->where('id', '!=', $ignoreDoctorId);
        }

        return $query->exists();
    }

    /**
     * Загрузка файла (приоритет) или ручной путь/URL в поле photo.
     */
    private function resolveDoctorPhotoPath(Request $request, ?Doctor $existing, ?string $manualPhoto): ?string
    {
        if ($request->hasFile('photo_file')) {
            if ($existing !== null) {
                $this->deleteStoredDoctorPhotoIfManaged($existing->photo);
            }

            $stored = $request->file('photo_file')->store('doctors', 'public');

            return '/storage/'.$stored;
        }

        $normalized = $this->normalizeManualPhoto($manualPhoto);

        if ($existing !== null && $existing->photo !== null && $normalized !== $existing->photo) {
            $this->deleteStoredDoctorPhotoIfManaged($existing->photo);
        }

        return $normalized;
    }

    private function normalizeManualPhoto(?string $photo): ?string
    {
        if ($photo === null) {
            return null;
        }

        $trimmed = trim($photo);

        return $trimmed === '' ? null : $trimmed;
    }

    private function deleteStoredDoctorPhotoIfManaged(?string $photo): void
    {
        if ($photo === null || $photo === '') {
            return;
        }

        $trimmed = ltrim($photo);

        if (! Str::startsWith($trimmed, '/storage/')) {
            return;
        }

        $relative = Str::after($trimmed, '/storage/');
        Storage::disk('public')->delete($relative);
    }
}
