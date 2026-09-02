<?php

namespace App\Http\Controllers;

use App\Http\Requests\MotoristaRequest;
use App\Http\Requests\PaginacaoRequest;
use App\Http\Resources\MotoristaResource;
use App\Models\Motorista;
use App\Services\MotoristaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class MotoristaController extends Controller
{
    public function __construct(private readonly MotoristaService $service) {}

    public function index(PaginacaoRequest $request): AnonymousResourceCollection
    {
        return MotoristaResource::collection($this->service->listar($request->porPagina()));
    }

    public function store(MotoristaRequest $request): JsonResponse
    {
        return MotoristaResource::make($this->service->criar($request->validated()))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Motorista $motorista): MotoristaResource
    {
        return MotoristaResource::make($motorista->loadCount('coletas'));
    }

    public function update(MotoristaRequest $request, Motorista $motorista): MotoristaResource
    {
        return MotoristaResource::make($this->service->atualizar($motorista, $request->validated()));
    }

    public function destroy(Motorista $motorista): Response
    {
        $this->service->excluir($motorista);

        return response()->noContent();
    }
}
