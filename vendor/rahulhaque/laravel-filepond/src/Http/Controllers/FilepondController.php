<?php

namespace RahulHaque\Filepond\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use RahulHaque\Filepond\Services\FilepondService;
use App\Models\FilePond;

class FilepondController extends Controller
{
    /**
     * FilePond ./process route logic.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request, FilepondService $service)
    {
        // Check if chunk upload
        if ($request->hasHeader('upload-length')) {
            return Response::make($service->initChunk(), 200, ['content-type' => 'text/plain']);
        }
        
        if (isset($request->images) )
            $validator_images = $service->validator($request, config('filepond.validation_rules_images', []));
        else if (isset($request->videos) )
            $validator_videos = $service->validator($request, config('filepond.validation_rules_videos', []));

        // $validator = $service->validator($request, config('filepond.validation_rules', []));

        if (isset($request->videos) && $validator_videos->fails()) {
            return Response::make($validator_videos->errors(), 422);
        }
        if (isset($request->images) && $validator_images->fails()) {
            return Response::make($validator_images->errors(), 422);
        }

        return Response::make($service->store($request), 200, ['content-type' => 'text/plain']);
    }

    /**
     * FilePond ./patch route logic.
     *
     * @param  Request  $request
     * @param  FilepondService  $service
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function patch(Request $request, FilepondService $service)
    {
        if (isset($request->images))
            $validator_images = $service->validator($request, config('filepond.validation_rules_images', []));
        else if (isset($request->videos))
            $validator_videos = $service->validator($request, config('filepond.validation_rules_videos', []));

        // $validator = $service->validator($request, config('filepond.validation_rules', []));

        if (isset($request->videos) && $validator_videos->fails()) {
            return Response::make($validator_videos->errors(), 422);
        }
        if (isset($request->images) && $validator_images->fails()) {
            return Response::make($validator_images->errors(), 422);
        }
        
        return Response::make('Ok', 200)->withHeaders(['upload-offset' => $service->chunk($request)]);
    }

    /**
     * FilePond ./head route logic.
     *
     * @param  Request  $request
     * @param  FilepondService  $service
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function head(Request $request, FilepondService $service)
    {
        return Response::make('Ok', 200)->withHeaders(['upload-offset' => $service->offset($request->patch)]);
    }

    /**
     * FilePond ./revert route logic.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function revert(Request $request, FilepondService $service)
    {
        $filepond = $service->retrieve($request->getContent());

        $service->delete($filepond);

        return Response::make('Ok', 200, ['content-type' => 'text/plain']);
    }

    /**
     * FilePond ./Custom function to GET the filepond data from database.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_records(Request $request) {

        $params = $request->all();

        if (isset($params['filepond_id']))
            return Filepond::getRecord($params);
        else
            return [];
    }

    /**
     * FilePond ./Custom function to DELETE the filepond data from database.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy_records(Request $request) {

        $params = $request->all();

        if (isset($params['filepond_id']))
            return Filepond::deleteRecord($params['filepond_id']);
        else
            return false;
    }
}
