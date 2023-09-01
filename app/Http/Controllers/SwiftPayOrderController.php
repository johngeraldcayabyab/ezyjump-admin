<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class SwiftPayOrderController extends Controller
{
    public function index(Request $request)
    {
        try {
            DB::connection('do_mysql')->getPdo();
            if(DB::connection('do_mysql')->getDatabaseName()){
                echo "Yes! Successfully connected to the DB: " . DB::connection()->getDatabaseName();
            }else{
                die("Could not find the database. Please check your configuration.");
            }
        } catch (\Exception $exception) {
            info($exception->getMessage());
        }
        return [];
    }
}
