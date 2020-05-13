<?php

namespace App\Http\Controllers\Blog\User;

use App\Http\Requests\BlogPostStoreRequest;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\BlogPost;
use App\Models\User;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{

    /**
     * @var BlogPostRepository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $blogPostRepository;
    private $blogCategoryRepository;

    /**
     * Подключение репозиториев
     *
     * PostController constructor.
     */
    public function __construct()
    {
        $this->blogPostRepository = app(BlogPostRepository::class);
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }

    /**
     * Показать статьи пользователя
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $paginator = $this->blogPostRepository->getAllOfUserWithPaginate();
        return view('blog.user.posts.index', compact('paginator'));
    }

    /**
     * Показать форму создание статьи
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $item = new BlogPost();
        $categoryList = $this->blogCategoryRepository->getForComboBox();
        return view('blog.user.posts.edit', compact('item', 'categoryList'));
    }

    /**
     * Сохранить статью в базе данных
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(BlogPostStoreRequest $request)
    {
        $item = new BlogPost();
        $data = $request->all();
        $result = $item->fill($data)->save();

        if ($result){
            return redirect()
                ->route('blog.user.posts.edit', [$item->id])
                ->with(['success' => 'Успешно создано']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка создания'])
                ->withInput();
        }
    }

    /**
     * Показать статьи пользователя
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $paginator = $this->blogPostRepository->getAllOfUserWithPaginate($id);
        return view('blog.user.posts.index', compact('paginator'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) {
            return back()
                ->withErrors(['msg' => 'Запись не найдена']);
        }

        $categoryList = $this->blogCategoryRepository->getForComboBox();
        return view('blog.user.posts.edit', compact('item', 'categoryList'));
    }

    /**
     * Обновить статью
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BlogPostUpdateRequest $request, $id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запись id=[{$id}]не найдена"])
                ->withInput();
        }

        $data = $request->all();

        $result = $item->update($data);

        if ($result) {
            return redirect()
                ->route('blog.user.posts.edit', $item->id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
