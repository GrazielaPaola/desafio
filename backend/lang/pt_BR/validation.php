<?php

return [
    'after' => 'O campo :attribute deve ser uma data posterior a :date.',
    'after_or_equal' => 'O campo :attribute deve ser uma data igual ou posterior a :date.',
    'date' => 'O campo :attribute deve ser uma data válida.',
    'date_format' => 'O campo :attribute deve estar no formato :format.',
    'exists' => 'O :attribute selecionado não existe.',
    'in' => 'O valor de :attribute é inválido.',
    'integer' => 'O campo :attribute deve ser um número inteiro.',
    'max' => [
        'numeric' => 'O campo :attribute não pode ser maior que :max.',
        'string' => 'O campo :attribute não pode ter mais que :max caracteres.',
    ],
    'min' => [
        'numeric' => 'O campo :attribute deve ser no mínimo :min.',
        'string' => 'O campo :attribute deve ter no mínimo :min caracteres.',
    ],
    'required' => 'O campo :attribute é obrigatório.',
    'string' => 'O campo :attribute deve ser um texto.',

    'custom' => [
        'data' => [
            'after' => 'A data do agendamento deve ser maior que a data atual.',
        ],
    ],

    'attributes' => [
        'nome' => 'nome',
        'data' => 'data do agendamento',
        'fornecedor_nome' => 'nome do fornecedor',
        'fornecedor_cnpj' => 'CNPJ do fornecedor',
        'cliente_nome' => 'nome do cliente',
        'cliente_cnpj' => 'CNPJ do cliente',
        'motorista_id' => 'motorista',
        'placa_veiculo' => 'placa do veículo',
        'por_pagina' => 'quantidade por página',
        'busca' => 'busca',
        'data_inicio' => 'data inicial',
        'data_fim' => 'data final',
        'ordenar_por' => 'ordenação',
        'direcao' => 'direção',
    ],
];
