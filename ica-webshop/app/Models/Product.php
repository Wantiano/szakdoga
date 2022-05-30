<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\FileUtil;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'is_deleted', 'description', 'category_id', 'created_by', 'last_modified_by'
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lastModifier() {
        return $this->belongsTo(User::class, 'last_modified_by');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function customers() {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'customer_id');
    }

    public function stocks() {
        return $this->hasMany(Stock::class);
    }

    public function images() {
        return $this->hasMany(ProductImage::class);
    }
    
    public function coverImage() {
        $image = $this->images->first();
        return asset(( !is_null($image) ? $image->imageUrl() : 'storage/images/products/default.jpg' ));
    }

    public function setDeleted() {
        $this->is_deleted = true;
        $this->customers()->detach();
        foreach ($this->stocks as $stock) {
            foreach ($stock->carts as $cart) {
                $cart->delete();
            }
        }
        $this->save();
    }

    public function inStock() {
        $amount = 0;
        foreach ($this->stocks as $stock) {
            $amount += $stock->in_stock;
        }
        return $amount;
    }

    /**
     * Synchronize stocks from data and return relevants.
     * 
     * @param int $stockFieldsCounter
     * @param array $validated
     * @return array
     */
    public function syncStocks($stockFieldsCounter, $validated) {
        $stocks = [];
        for($i = 0; $i < $stockFieldsCounter; ++$i) {
            $stocks[] = $this->syncStock([
                'color' => $validated['stock_color_' . $i], 
                'size' => $validated['stock_size_' . $i], 
                'in_stock' => $validated['stock_in_stock_' . $i]
            ]);
        }
        
        return $stocks;
    }

    /**
     * Update stock if exists, save new if not in storage.
     * 
     * @param array $stockData
     * @return App\Models\Stock
     */
    private function syncStock($stockData) {
        $stock = $this->stocks->where('color', $stockData['color'])->where('size', $stockData['size'])->first();

        if ($stock == null) {
            if($stockData['in_stock'] > 0) {
                $stock = new Stock(['product_id' => $this->id,'color' => $stockData['color'], 
                                    'size' => $stockData['size'], 'in_stock' => $stockData['in_stock']]);
                $stock->save();
            }
        } elseif ($stock->in_stock != $stockData['in_stock']) {
            $stock->in_stock = $stockData['in_stock'];
            $stock->update();
        }

        return $stock;
    }

    /**
     * Synchronize product images from validated and return relevants.
     * 
     * @param int $imageFieldsCounter
     * @param App\Models\Product $product
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function saveProductImages($imageFieldsCounter, $request) {
        $images = [];
        for($i = 0; $i < $imageFieldsCounter; ++$i) {
            $images[] = $this->saveProductImage($request->file('image_' . $i));;
        }
        
        return $images;
    }

    /**
     * Save new product image in database.
     * 
     * @param App\Models\Product $product
     * @param \Illuminate\Http\UploadedFile $file
     * @return App\Models\ProductImage
     */
    private function saveProductImage($image) {
        $imageUrl = FileUtil::storeFile($image, 'images/products/');
        return ProductImage::create(['product_id' => $this->id,'image_url' => $imageUrl]);
    }

    /**
     * Delete product's stocks that are irrelevant.
     * 
     * @param array $relevantStocks
     */
    public function deleteIrrelevantStocks($relevantStocks) {
        foreach ($this->stocks as $stock) {
            if( !$stock->isRelevant($relevantStocks) ) {
                $stock->delete();
            }
        }
    }

    /**
     * Delete images that are marked to remove.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function deleteRemovedImages($request) {
        $ind = 0;
        foreach ($this->images as $image) {
            $checkBoxInd = 'removeStoredProductImageCheckBox_' . $ind;
            if($request->has($checkBoxInd)) {
                $imagePath = 'images/products/' . $image->image_url;
                FileUtil::deleteFile($imagePath);
                $image->delete();
            }
            ++$ind;
        }
    }
}
