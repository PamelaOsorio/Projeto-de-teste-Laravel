<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => $this->faker->company,
            'cnpj' => $this->faker->unique()->numerify('##############'),
            'email' => $this->faker->companyEmail,
            'telefone' => $this->faker->phoneNumber,
            'status' => $this->faker->randomElement(['pendente', 'aprovado']),
        ];
    }
}
