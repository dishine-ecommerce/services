<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;

class CategoryController extends Controller
{
  protected $categoryService;

  public function __construct()
  {
    $this->categoryService = new CategoryService();
  }

  public function index()
  {
    $categories = $this->categoryService->get();
    return response()->json($categories);
  }

  public function show($id)
  {
    $category = $this->categoryService->getById($id);
    if (!$category) {
        return response()->json(['message' => 'Category not found'], 404);
    }
    return response()->json($category);
  }

  public function store(Request $request)
  {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'parent_id' => 'nullable|integer|exists:categories,id',
    ]);

    $category = $this->categoryService->create($data);
    return response()->json($category, 201);
  }

  public function update(Request $request, $id)
  {
    $data = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'parent_id' => 'nullable|integer|exists:categories,id',
    ]);

    $category = $this->categoryService->update($id, $data);

    if (!$category) {
        return response()->json(['message' => 'Category not found'], 404);
    }

    return response()->json($category);
  }

  public function destroy($id)
  {
    $deleted = $this->categoryService->delete($id);
    if (!$deleted) {
        return response()->json(['message' => 'Category not found'], 404);
    }
    return response()->json(['message' => 'Category deleted successfully']);
  }

  public function getByParentId($parentId)
  {
    $categories = $this->categoryService->getByParentId($parentId);
    return response()->json($categories);
  }

  public function root()
  {
    $categories = $this->categoryService->getRootCategories();
    return response()->json($categories);
  }
}
