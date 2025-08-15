<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\V1\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __invoke(){
        return Report::whereDate('created_at', now()->toDateString())->paginate(15);
    }
}
