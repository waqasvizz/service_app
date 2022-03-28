<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;


    public function getCategories($posted_data = array())
    {
        $query = Category::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('categories.id', $posted_data['id']);
        }
        if (isset($posted_data['name'])) {
            $query = $query->where('categories.name', 'like', '%' . $posted_data['name'] . '%');
        }

        $query->select('categories.*');
        
        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'ASC');
        }

        if (isset($posted_data['paginate'])) {
            $result = $query->paginate($posted_data['paginate']);
        } else {
            if (isset($posted_data['detail'])) {
                $result = $query->first();
            } else if (isset($posted_data['count'])) {
                $result = $query->count();
            } else {
                $result = $query->get();
            }
        }
        return $result;
    }



    public function saveUpdateCategory($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Category::find($posted_data['update_id']);
        } else {
            $data = new Category;
        }

        if (isset($posted_data['name'])) {
            $data->name = $posted_data['name'];
        }

        $data->save();
        return $data->id;
    }
}