<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
  protected $productRepo;
  
  public function __construct() {
    $this->productRepo = new ProductRepository();
  }

  public function get()
  {
    return $this->productRepo->get();
  }

  public function getBySlug(string $slug)
  {
    return $this->productRepo->getBySlug($slug);
  }

  public function create(array $data)
  {
    return $this->productRepo->create($data);
  }

  public function update($slug, array $data)
  {
    return $this->productRepo->update($slug, $data);
  }

  public function delete($slug)
  {
    return $this->productRepo->delete($slug);
  }
}