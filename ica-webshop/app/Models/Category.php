<?php

namespace App\Models;

use App\Utils\FileUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'parent_category_id', 'image_url', 'created_by', 'last_modified_by'
    ];

    public function parentCategory() {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function products() {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function coverImage() {
        return asset('storage/images/category_covers/' . (is_null($this->image_url) ? "default.jpg" : $this->image_url));
    }

    public function children() {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    public function deleteCoverImageFile() {
        $oldImagePath = 'images/category_covers/' . $this->image_url;
        FileUtil::deleteFile($oldImagePath);
    }

    public function isBaseCategory() {
        return $this->parentCategory == null;
    }

    public function getParents() {
        if ($this->isBaseCategory()) {
            return [$this];
        }

        $parents = $this->parentCategory->getParents();
        $parents[] = $this;

        return $parents;
    }

    public function getAllActiveProducts() {
        $products = $this->products()->where('is_deleted', false)->get();
        $childrenCategories = $this->children;
        foreach ($childrenCategories as $category) {
            foreach ($category->getAllActiveProducts() as $product) {
                $products[] = $product;
            }
        }
        return $products;
    }

    public function getAllDeletedProducts() {
        $products = $this->products()->where('is_deleted', true)->get();
        $childrenCategories = $this->children;
        foreach ($childrenCategories as $category) {
            foreach ($category->getAllDeletedProducts() as $product) {
                $products[] = $product;
            }
        }
        return $products;
    }

    public function getAllProducts() {
        $products = $this->products()->get();
        $childrenCategories = $this->children;
        foreach ($childrenCategories as $category) {
            foreach ($category->getAllProducts() as $product) {
                $products[] = $product;
            }
        }
        return $products;
    }

    public function reassignCategoriesToParent() {
        foreach ($this->children as $category) {
            $category->update(['parent_category_id' => $this->parent_category_id]);
        }
    }

    public function reassignProductsToParent() {
        foreach ($this->products as $product) {
            $product->update(['category_id' => $this->parent_category_id]);
        }
    }
}
