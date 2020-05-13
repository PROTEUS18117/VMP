<?php

namespace App\Http\Controllers\Blog;

use App\Models\Product;
use App\Repositories\BlogProductRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{

    private $blogProductRepository;

    public function __construct()
    {
        $this->blogProductRepository = app(BlogProductRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = $this->blogProductRepository->getAllWithPaginate();

        return view('blog.products.index', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $item = Product::findOrFail($id);

        return view('blog.products.show', compact('item'));
    }

}
