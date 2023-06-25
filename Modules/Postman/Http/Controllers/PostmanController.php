<?php

namespace Modules\Postman\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class PostmanController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'throttle:download-postman-collection'
        ]);
    }

    public function download()
    {

        try {

            /**
             * Fetch postman collection.
             */
            Artisan::call('export:postman');

            $collection = collect(Storage::disk('local')->files('postman'))->last();

            /**
             * Return download response.
             */

            return response()->download(storage_path('app/'.$collection));

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], 400);

        }
    }
}
