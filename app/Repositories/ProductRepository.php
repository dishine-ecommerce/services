<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
  protected $model;

  public function __construct() {
    $this->model = new Product();
  }

  public function get()
  {
    return $this->model->all();
  }

  public function getById($id)
  {
    return $this->model->find($id);
  }

  public function getBySlug(string $slug)
  {
    return $this->model->firstWhere('slug', $slug);
  }

  public function create(array $data)
  {
    return $this->model->create($data);
  }

  public function update($slug, array $data)
  {
    $product = $this->model->firstWhere('slug', $slug);
    if ($product) {
        $product->update($data);
        return $product;
    }
    return null;
  }

  public function delete($slug)
  {
    $product = $this->model->firstWhere('slug', $slug);
    if ($product) {
        return $product->delete();
    }
    return false;
  }
}