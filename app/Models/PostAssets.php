<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostAssets extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    // protected $fillable = [
    //     'user_id',
    //     'device_id',
    //     'device_token'
    // ];

    // db fields ==> id	post_id	filepond_id	asset_type	created_at	updated_at

    protected $table = 'post_assets';


    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    
    public function getPostAssets($posted_data = array()) {
        
        $query = PostAssets::latest();

        if(isset($posted_data) && count($posted_data)>0){
            if(isset($posted_data['id'])){
                $query = $query->where('post_assets.id', $posted_data['id']);
            }
            if(isset($posted_data['post_id'])){
                $query = $query->where('post_assets.post_id', $posted_data['post_id']);
            }
            if(isset($posted_data['filepond_id'])){
                $query = $query->where('post_assets.filepond_id', $posted_data['filepond_id']);
            }
            if(isset($posted_data['asset_type'])){
                $query = $query->where('post_assets.asset_type', $posted_data['asset_type']);
            }
        }
        
        $query->join('fileponds', 'fileponds.id', '=', 'post_assets.filepond_id');
        $query->select('post_assets.*', 'fileponds.filename', 'fileponds.filepath');
        
        // $query->getQuery()->orders = null;
        // if(isset($posted_data['orderBy_name'])){
        //     $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        // }else{
        //     $query->orderBy('id', 'DESC');
        // }

        
        if(isset($posted_data['paginate'])){
            $result = $query->paginate($posted_data['paginate']);
        }else{
            if(isset($posted_data['detail'])){
                $result = $query->first();
            }else{
                $result = $query->get();
            }
            // $result = $query->toSql();
        }
        return $result;
    }

    public function saveUpdatePostAssets($posted_data = array()) {
        if(isset($posted_data['update_id'])){
            $data = PostAssets::find($posted_data['update_id']);
        }else{
            $data = new PostAssets;
        }
        if(isset($posted_data['post_id'])){
            $data->post_id = $posted_data['post_id'];
        }
        if(isset($posted_data['filepond_id'])){
            $data->filepond_id = $posted_data['filepond_id'];
        }
        if(isset($posted_data['asset_type'])){
            $data->asset_type = $posted_data['asset_type'];
        }
        $data->save();
        return $data->id;
    }

    public function deletePostAssets($id=0) {
        $data = PostAssets::find($id);
        return $data->delete();
    }
}