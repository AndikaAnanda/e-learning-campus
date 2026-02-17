<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10240', // max 10MB
        ]);

        $filePath = $request->file('file')->store('materials', 'public');

        $material = Material::create([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'file_path' => $filePath,
        ]);

        return response()->json([
            'message' => 'Materi berhasil diunggah',
            'material' => $material,
        ], 201);
    }

    public function download($id) {
        $material = Material::findOrFail($id);

        $path = Storage::disk('public')->path($material->file_path);

        if (!Storage::disk('public')->exists($material->file_path)) {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }

        return response()->download($path, basename($material->file_path));
    }
}
