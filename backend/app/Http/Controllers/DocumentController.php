<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{


    public function index(Request $request)
    {
        $documents = Document::where('user_id', $request->user()->id)
            ->latest()
            ->get();
    
        return response()->json($documents);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $path = $request->file('file')->store('documents', 'public');

        $document = Document::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'filename' => $path,
            'extracted_text' => null, // to be filled by AI later
            'tags' => [],
            'type' => null,
        ]);

        return response()->json($document, 201);
    }

}
