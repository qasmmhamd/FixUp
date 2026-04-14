<?php

namespace App\Http\Controllers\DashboardAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Service;
use App\Models\Images;

class EnterWorkersController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "user_id" =>"exists:users,id",
            "career_id" => "exists:careers,id",
            "abut" => "string",
            "name_service" => "string",
            "years_experience" => "integer",
            "garlery_works" => "array",
            "garlery_works.*.description" => "string",
            "garlery_works.*.images" => "array",
            "garlery_works.*.images.*" => "image|mimes:jpeg,png,jpg|max:2048",
            "services" => "array",
            "services.*" => "exists:services,id",
            "account_status" => "boolean",

        ]);
            $worker = Worker::create([
                "user_id" => $request->user_id,
                "career_id" => $request->career_id,
                "about" => $request->abut,
                "years_experience" => $request->years_experience,
                "status" => $request->account_status,
                "account_status" => $request->account_status,
            ]);
            $serves = Service::create([
                "worker_id" => $worker->id,
                "services" => $request->services,
                "career_id" => $request->career_id,
                "name" => $request->name_service,
            ]);
                if ($request->has('garlery_works')) {
                    foreach ($request->garlery_works as $work) {
                        $galleryWork = $worker->galleryWorks()->create([
                            'description' => $work['description'],
                        ]);
    
                        if (isset($work['images'])) {
                            foreach ($work['images'] as $image) {
                                $imagePath = $image->store('gallery_images', 'public');
                                $galleryWork->images()->create([
                                    'image_path' => $imagePath,
                                ]);
                            }
                        }
                    }
                }
               
             
            return response()->json([
                "message" => "Worker created successfully",
                "worker" => $worker
            ], 201);
    
    }
}
