<?php

namespace App\Services;

use App\Repositories\ProductImageRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Storage;

class ProductService
{
  protected $productRepo;
  protected $pImageService;
  
  public function __construct() {
    $this->productRepo = new ProductRepository();
    $this->pImageService = new ProductImageService();
  }

  public function get(array $filters = [])
  {
    return $this->productRepo->get($filters);
  }

  public function getBySlug(string $slug)
  {
    return $this->productRepo->getBySlug($slug);
  }

  public function create(array $data)
  {
    $newProduct = $this->productRepo->create($data);
    $images = $data['images'] ?? [];

    $this->pImageService->addImages($images, $newProduct->id);

    return $newProduct;
  }

  public function update($slug, array $data)
  {
    $product = $this->productRepo->getBySlug($slug);
    if (!$product) {
      return null;
    }
    $updatedProduct = $this->productRepo->update($slug, $data);
    return $updatedProduct;
  }

  public function delete($slug)
  {
    $product = $this->productRepo->getBySlug($slug);
    if (!$product) {
      return false;
    }
    $this->productRepo->delete($slug);
    $this->pImageService->deleteByProductId($product->id);
    return true;
  }
}