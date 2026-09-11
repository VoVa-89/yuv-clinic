<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\View\View;

class DocumentPageController extends Controller
{
    public function __invoke(): View
    {
        $documents = Document::query()->active()->orderBy('sort_order')->get();

        return view('public.documents', compact('documents'));
    }
}
