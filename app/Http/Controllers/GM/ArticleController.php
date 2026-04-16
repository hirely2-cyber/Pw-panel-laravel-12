<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Http\Controllers\GM;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = News::where('author_id', auth()->id())->latest('published_at')->paginate(20);
        return view('gm.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('gm.articles.form', ['article' => new News()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'body'         => ['required', 'string'],
            'category'     => ['required', 'string', 'max:50'],
            'thumbnail'    => ['nullable', 'image', 'max:2048'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['slug']         = Str::slug($data['title']) . '-' . now()->format('ymdHis');
        $data['author_id']    = auth()->id();
        $data['is_active']    = false; // Needs admin approval
        $data['published_at'] = $data['published_at'] ?? now();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = ImageOptimizer::storeAsWebp($request->file('thumbnail'), 'news');
        }

        News::create($data);

        return redirect()->route('gm.articles.index')
            ->with('success', 'Artikel berhasil dibuat dan menunggu persetujuan admin.');
    }

    public function edit(News $article): View
    {
        // GM can only edit their own articles
        if ($article->author_id !== auth()->id()) {
            abort(403);
        }

        return view('gm.articles.form', compact('article'));
    }

    public function update(Request $request, News $article): RedirectResponse
    {
        if ($article->author_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'body'         => ['required', 'string'],
            'category'     => ['required', 'string', 'max:50'],
            'thumbnail'    => ['nullable', 'image', 'max:2048'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = ImageOptimizer::storeAsWebp($request->file('thumbnail'), 'news');
        }

        $article->update($data);

        return redirect()->route('gm.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(News $article): RedirectResponse
    {
        if ($article->author_id !== auth()->id()) {
            abort(403);
        }

        $article->delete();
        return redirect()->route('gm.articles.index')->with('success', 'Artikel dihapus.');
    }
}
