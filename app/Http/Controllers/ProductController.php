<?php

namespace App\Http\Controllers;

use App\Helpers\Slug;
use App\Http\Requests\CreateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct() {
        $this->productService = new ProductService();
    }

    public function index()
    {
        return response()->json($this->productService->get());
    }

    public function show($slug)
    {
        $product = $this->productService->getBySlug($slug);
        if (!$product) return response()->json(['message' => 'Not found'], 404);
        return response()->json($product);
    }

    public function store(CreateProductRequest $request)
    {
        $validated = $request->validated();
        $data = [
            ...$validated,
            'slug' => Slug::generate($validated['name']),
        ];
        $product = $this->productService->create($data);
        return response()->json($product, 201);
    }

    public function update(Request $request, $slug)
    {
        $product = $this->productService->update($slug, $request->all());
        if (!$product) return response()->json(['message' => 'Not found'], 404);
        return response()->json($product);
    }

    public function destroy($slug)
    {
        $deleted = $this->productService->delete($slug);
        if (!$deleted) return response()->json(['message' => 'Not found'], 404);
        return response()->json(['message' => 'Deleted']);
    }
}