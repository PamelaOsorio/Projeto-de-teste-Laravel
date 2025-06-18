<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class ClienteController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validatedData = $this->validateCliente($request);

        $cliente = Cliente::create([
            ...$validatedData,
            'status' => 'pendente',
        ]);

        $this->notificarApiExterna($cliente);

        return response()->json($cliente, 201);
    }
// permitir que apenas usuários admin aprovem o cadastro
    public function aprovar(int $id): JsonResponse
    {
        if (! $this->usuarioEhAdmin()) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        $cliente = Cliente::findOrFail($id);
        $cliente->update(['status' => 'aprovado']);

        return response()->json(['message' => 'Cliente aprovado com sucesso']);
    }

    public function reprovar(int $id): JsonResponse
    {
        if (! $this->usuarioEhAdmin()) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        $cliente = Cliente::findOrFail($id);
        $cliente->update(['status' => 'reprovado']);

        return response()->json(['message' => 'Cliente reprovado com sucesso']);
    }
//listar clientes com filtros status/nome
    public function index(Request $request): JsonResponse
    {
        $clientes = Cliente::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('nome'), fn ($query) => $query->where('nome', 'like', '%' . $request->nome . '%'))
            ->paginate(10);

        return response()->json($clientes);
    }

    // Valida os dados de entrada do cliente.
     
    private function validateCliente(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:clientes',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
        ]);
    }

    // Simula envio para API externa.
     
    private function notificarApiExterna(Cliente $cliente): void
    {
        Http::post('https://api.externa-ficticia.com/clientes', $cliente->toArray());
    }

    // Verifica se o usuário atual é administrador.
     
    private function usuarioEhAdmin(): bool
    {
        return auth()->user()?->role === 'admin';
    }
}
