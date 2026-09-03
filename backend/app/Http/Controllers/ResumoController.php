<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResumoResource;
use App\Services\ResumoService;

class ResumoController extends Controller
{
    public function __construct(private readonly ResumoService $service) {}

    public function __invoke(): ResumoResource
    {
        return ResumoResource::make($this->service->gerar());
    }
}
