<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Slug
{
  public static function generate(string $text): string 
  {
    return Str::slug($text);
  }
}