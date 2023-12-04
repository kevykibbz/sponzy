<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $guarded = [];
    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class)->first();
    }

    /**
     * Get the seller
     */
    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get Images Previews
     */
    public function previews()
    {
        return $this->hasMany(MediaProducts::class);
    }

    /**
     * Get Purchases
     */
    public function purchases()
    {
        return $this->hasMany(Purchases::class);
    }

    /**
     * Country Free Shipping
     */
    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_free_shipping')->first();
      }

      /**
       * Country Free Categories
       */
      public function categoryId()
   	 {
   	 	return $this->belongsTo(ShopCategories::class, 'category');
   	 }
}
