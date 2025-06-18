<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Repositories\BlogPostRepository;
use App\Models\BlogPost;
use App\Http\Requests\BlogPostCreateRequest;
use Illuminate\Http\Request;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostUpdateRequest;
use Illuminate\Support\Str;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;

class PostController extends BaseController
{
    /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository;
    private $blogPostRepository;

    public function __construct()
    {
        parent::__construct();
        $this->blogPostRepository = app(BlogPostRepository::class);
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        return view('blog.admin.posts.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $item = new BlogPost(); // Створюємо новий порожній об'єкт BlogPost
        $categoryList = $this->blogCategoryRepository->getForComboBox(); // Отримуємо список категорій для випадаючого списку

        return view('blog.admin.posts.edit', compact('item', 'categoryList')); // Використовуємо той самий шаблон edit.blade.php
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogPostCreateRequest $request) // Змінено тип $request
    {
        $data = $request->input();
        $item = (new BlogPost())->create($data);
        if ($item) {
            // Відправляємо Job в чергу після успішного створення посту
            $job = new BlogPostAfterCreateJob($item);
            $this->dispatch($job); // Або dispatch($job); без $this

            return redirect()
                ->route('blog.admin.posts.edit', [$item->id])
                ->with(['success' => 'Успішно збережено']);}
        else {
            return back()
                ->withErrors(['msg' => 'Помилка збереження'])
                ->withInput();
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            abort(404);
        }
        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.posts.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogPostUpdateRequest $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"])
                ->withInput();
        }

        $data = $request->validated();

        $result = $item->update($data);

        if ($result) {
            return redirect()
                ->route('blog.admin.posts.edit', $item->id)
                ->with(['success' => 'Успішно збережено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка збереження'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = BlogPost::destroy($id); // Використовуємо soft delete, запис лишається в БД, але позначається як видалений

        // $result = BlogPost::find($id)->forceDelete(); // Повне видалення з БД (для використання, якщо потрібне жорстке видалення)

        if ($result) {
            // Відправляємо Job в чергу після успішного видалення посту, з затримкою 20 секунд
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);

            return redirect()
                ->route('blog.admin.posts.index')
                ->with(['success' => "Запис id[$id] видалено"]);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка видалення']);
        }
    }
}
