<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('products')
            ->latest()
            ->paginate(20);

        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_he' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:255',
            'position' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['name_en']);
        $validated['color'] = $validated['color'] ?? '#3b82f6';

        Tag::create($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', __('messages.tag_created_successfully'));
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_he' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:255',
            'position' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['name_en'], $tag->id);
        $validated['color'] = $validated['color'] ?? $tag->color;

        $tag->update($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', __('messages.tag_updated_successfully'));
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', __('messages.tag_deleted_successfully'));
    }

    /**
     * Generate a unique slug for the tag
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Tag::withTrashed()->where('slug', $slug);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
