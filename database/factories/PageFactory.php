<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->realText(50),
            'type' => $this->faker->randomElement([
                Page::TYPE_INFORMATION,
                Page::TYPE_NEED_HELP,
            ]),
            'content' => $this->faker->randomHtml,
            'status' => $this->faker->boolean,
        ];
    }
}
