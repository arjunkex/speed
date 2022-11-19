<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\DomainRequest;
use App\Models\ModifiedBy;
use App\Models\Tenant;

class DomainRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DomainRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tenant_id' => Tenant::inRandomOrder()->first()->id,
            'requested_domain' => $this->faker->domainName,
            'status' => $this->faker->randomElement([0, 1, 2, 3]),
            'modified_by_id' => User::inRandomOrder()->first()->id,
            'modified_at' => $this->faker->dateTime(),
        ];
    }
}
