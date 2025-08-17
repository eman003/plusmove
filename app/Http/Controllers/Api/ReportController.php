<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ReportResource;
use App\Models\V1\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/report",
     * summary="Generate a daily report",
     * tags={"Reports"},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="date", type="string", format="date", example="2025-08-17"),
     * @OA\Property(property="driver_name", type="string", nullable=true, example="Contact Roux"),
     * @OA\Property(property="vehicle", type="string", example="Hyundai, Staria Panel Van"),
     * @OA\Property(property="delivered", type="integer", example=6),
     * @OA\Property(property="returned", type="integer", example=1),
     * @OA\Property(property="total", type="integer", example=7)
     * )
     * ),
     * @OA\Property(
     * property="links",
     * type="object",
     * @OA\Property(property="first", type="string", nullable=true, example="http://plusmove.test/api/v1/report?page=1"),
     * @OA\Property(property="last", type="string", nullable=true, example="http://plusmove.test/api/v1/report?page=1"),
     * @OA\Property(property="prev", type="string", nullable=true),
     * @OA\Property(property="next", type="string", nullable=true)
     * ),
     * @OA\Property(
     * property="meta",
     * type="object",
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="from", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=1),
     * @OA\Property(
     * property="links",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="url", type="string", nullable=true),
     * @OA\Property(property="label", type="string"),
     * @OA\Property(property="page", type="integer", nullable=true),
     * @OA\Property(property="active", type="boolean")
     * )
     * ),
     * @OA\Property(property="path", type="string", example="http://plusmove.test/api/v1/report"),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="to", type="integer", example=7),
     * @OA\Property(property="total", type="integer", example=7)
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to view this report."
     * )
     * )
     */
    public function __invoke(){
        Gate::authorize('reports', Report::class);
        $reports = Report::whereDate('created_at', now()->toDateString())->paginate(15);

        return ReportResource::collection($reports);
    }
}
