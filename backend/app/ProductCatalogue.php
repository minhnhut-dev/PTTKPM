<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCatalogue extends Model
{
    //
    protected $table = 'product_catalogues';

    public function products()
    {
        return $this->hasMany(SanPham::class);
    }
}
