<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'api.externa-ficticia.com/*' => Http::response(['message' => 'Mocked response'], 200),
        ]);
    }

    private function authenticate(?array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $this->actingAs($user, 'sanctum');

        return $user;
    }

    #[Test]
    public function deve_cadastrar_cliente_com_campos_validos()
    {
        $this->authenticate();

        $response = $this->postJson('/api/clientes', [
            'nome' => 'Empresa Teste',
            'cnpj' => '12345678000199',
            'email' => 'empresa_teste@gmail.com',
            'telefone' => '11999999999',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('clientes', [
            'nome' => 'Empresa Teste',
            'cnpj' => '12345678000199',
            'status' => 'pendente',
        ]);
    }

    #[Test]
    public function nao_deve_cadastrar_cliente_sem_autenticacao()
    {
        $response = $this->postJson('/api/clientes', [
            'nome' => 'Empresa Teste',
            'cnpj' => '12345678000199',
            'email' => 'empresa@teste.com',
            'telefone' => '11999999999',
        ]);

        $response->assertUnauthorized();
    }

    #[Test]
    public function nao_deve_permitir_cnpj_duplicado()
    {
        $this->authenticate();

        Cliente::factory()->create(['cnpj' => '12345678000199']);

        $response = $this->postJson('/api/clientes', [
            'nome' => 'Empresa Nova',
            'cnpj' => '12345678000199',
            'email' => 'nova@teste.com',
            'telefone' => '11999999999',
        ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function apenas_admin_pode_aprovar_cadastro()
    {
        $cliente = Cliente::factory()->create(['status' => 'pendente']);

        // Funcionario não pode aprovar
        $this->authenticate(['role' => 'funcionario']);

        $this->postJson("/api/clientes/{$cliente->id}/aprovar")
            ->assertForbidden();

        // Admin pode aprovar
        $this->authenticate(['role' => 'admin']);

        $this->postJson("/api/clientes/{$cliente->id}/aprovar")
            ->assertOk();

        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'status' => 'aprovado',
        ]);
    }

     #[Test]
    public function apenas_admin_pode_reprovar_cadastro()
    {
        $cliente = Cliente::factory()->create(['status' => 'pendente']);

        // Funcionario não pode aprovar
        $this->authenticate(['role' => 'funcionario']);

        $this->postJson("/api/clientes/{$cliente->id}/reprovar")
            ->assertForbidden();

        // Admin pode aprovar
        $this->authenticate(['role' => 'admin']);

        $this->postJson("/api/clientes/{$cliente->id}/reprovar")
            ->assertOk();

        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'status' => 'reprovado',
        ]);
    }
    #[Test]
    public function deve_listar_clientes_com_paginacao()
    {
        $this->authenticate();

        Cliente::factory()->count(15)->create();

        $response = $this->getJson('/api/clientes');

        $response->assertOk();
        $this->assertCount(10, $response->json('data'));
    }

    #[Test]
    public function deve_filtrar_clientes_por_status()
    {
        $this->authenticate();

        Cliente::factory()->create(['status' => 'aprovado']);
        Cliente::factory()->create(['status' => 'pendente']);

        $response = $this->getJson('/api/clientes?status=aprovado');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
    }

    #[Test]
    public function deve_filtrar_clientes_por_nome()
    {
        $this->authenticate();

        Cliente::factory()->create(['nome' => 'Empresa Alfa']);
        Cliente::factory()->create(['nome' => 'Empresa Beta']);

        $response = $this->getJson('/api/clientes?nome=Alfa');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
    }
}
