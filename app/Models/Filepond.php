<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filepond extends Model
{
    use HasFactory;

    public function getRecord($posted_data = array())
    {
        $query = Filepond::latest();

        if (isset($posted_data['filepond_id'])) {
            $query = $query->where('id', $posted_data['filepond_id']);
        }

        if (isset($posted_data['paginate'])) {
            $result = $query->paginate($posted_data['paginate']);
        }
        else {
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
    
    public function deleteRecord($id = 0)
    {
        $data = Filepond::find($id);
        
        if ($data)
            return $data->delete();
        else 
            return false;
    }
    
}
