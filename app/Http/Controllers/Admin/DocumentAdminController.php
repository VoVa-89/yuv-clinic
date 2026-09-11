<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDocumentRequest;
use App\Http\Requests\Admin\UpdateDocumentRequest;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class DocumentAdminController extends Controller
{
    public function index(): View
    {
        $documents = Document::query()->orderBy('sort_order')->paginate(30);

        return view('admin.documents.index', compact('documents'));
    }

    public function create(): View
    {
        return view('admin.documents.create');
    }

    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        Document::query()->create([
            'title' => $data['title'],
            'file_path' => $path,
            'mime' => $file->getMimeType(),
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.documents.index')->with('status', 'Документ загружен.');
    }

    public function edit(Document $document): View
    {
        return view('admin.documents.edit', compact('document'));
    }

    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($document->file_path);
            $file = $request->file('file');
            $document->file_path = $file->store('documents', 'public');
            $document->mime = $file->getMimeType();
        }

        $document->title = $data['title'];
        $document->sort_order = $data['sort_order'] ?? 0;
        $document->is_active = $request->boolean('is_active', true);
        $document->save();

        return redirect()->route('admin.documents.index')->with('status', 'Документ обновлён.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('admin.documents.index')->with('status', 'Документ удалён.');
    }
}
