<?php

if (! function_exists('is_image_exist')) {
    function is_image_exist($image_path = '') {
        
        $base_url = public_path().'/'.$image_path;
        $asset_url = config('app.url').'/';
        $image_url = $asset_url.$image_path;


        $default_img_name = 'default-thumbnail.jpg';
        
        if ( $image_path == '' || is_null($image_path) )
            return config('app.url').'/storage/default-images/'.$default_img_name;
        else if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$image_path))
            return config('app.url').'/'.$image_path;
        else if (file_exists($base_url))
            return config('app.url').'/'.$image_path;
        else
            return config('app.url').'/storage/default-images/'.$default_img_name;
    }
}

if (! function_exists('get_gayment_name')) {
    function get_gayment_name($id = 0) {
        
        if ( $id == 1)
            return 'Cash on Delivery';
        else if ( $id == 2)
            return 'Paypal';
        else if ( $id == 3)
            return 'Stripe';
        else
            return 'Unknown';
    }
}

if (! function_exists('get_status_name')) {
    function get_status_name($id = 0) {
        
        if ( $id == 1)
            return 'Pending';
        else if ( $id == 2)
            return 'In-Progress';
        else if ( $id == 3)
            return 'Complete';
        else
            return 'Unknown';
    }
}

if (! function_exists('upload_files_to_storage')) {
    function upload_files_to_storage($request, $file_param, $path)
    {
        $response = array();

        $file_name = time().'_'.$file_param->getClientOriginalName();
        $file_name = preg_replace('/\s+/', '_', $file_name);
        $file_request = $file_param->storeAs($path, $file_name, ['disk' => 'public']);
        // $file_path = 'storage/'.$path.'/'.$file_name;
        $file_path = $path.'/'.$file_name;

        if( $file_param->isValid() )
            return $response = array(
                'action'        => true,
                'message'       => 'Requested file is uploaded successfully.',
                'file_name'     => $file_name,
                'file_path'     => $file_path
            );
        else
            return $response = array(
                'action'        => false,
                'message'       => 'Something went wrong during uploading.'
            );    
    }
}

if (! function_exists('delete_files_from_storage')) {
    function delete_files_from_storage($file)
    {
        if( $file != "" ) {
            // File::delete(public_path('upload/bio.png'));
            $process = File::delete(public_path('storage').'/'.$file);
            // $process = File::delete(storage_path().'/'.$file);

            if ( $process )
                return $response = array('action' => true, 'message'   => 'Requested file is delete successfully.');
            else
                return $response = array('action' => false, 'message'   => 'Requested file is not exist.', 'file' => public_path('storage').'/'.$file);
        }
        else 
            return $response = array('action' => false, 'message'   => 'There is no file available to delete.');
    }
}