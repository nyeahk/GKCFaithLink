<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebugController extends Controller
{
    public function checkUserModel()
    {
        $provider = Auth::getProvider();
        $model = $provider->getModel();
        
        return [
            'model_path' => $model,
            'exists' => class_exists($model),
            'app_user_exists' => class_exists('App\\User'),
            'app_models_user_exists' => class_exists('App\\Models\\User')
        ];
    }
}