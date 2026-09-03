<?php

namespace App\Http\Controllers;

use App\Http\Requests\ColetaRequest;
use App\Http\Requests\ListarColetasRequest;
use App\Http\Resources\ColetaResource;
use App\Models\Coleta;
use App\Services\ColetaService;
use App\Support\ColetasCsv;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ColetaController extends Controller
{
    public function __construct(private readonly ColetaService $service) {}

    public function index(ListarColetasRequest $request): AnonymousResourceCollection
    {
        return ColetaResource::collection(
            $this->service->listar($request->filtros(), $request->porPagina()),
        );
    }

    public function exportar(ListarColetasRequest $request): StreamedResponse
    {
        $coletas = $this->service->listarParaExportacao($request->filtros());

        return response()->streamDownload(
            fn () => ColetasCsv::escrever($coletas),
            ColetasCsv::NOME_ARQUIVO,
            ['Content-Type' => ColetasCsv::CONTENT_TYPE],
        );
    }

    public function store(ColetaRequest $request): JsonResponse
    {
        return ColetaResource::make($this->service->criar($request->validated()))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Coleta $coleta): ColetaResource
    {
        return ColetaResource::make($coleta->load('motorista'));
    }

    public function update(ColetaRequest $request, Coleta $coleta): ColetaResource
    {
        return ColetaResource::make($this->service->atualizar($coleta, $request->validated()));
    }

    public function destroy(Coleta $coleta): Response
    {
        $this->service->excluir($coleta);

        return response()->noContent();
    }
}
