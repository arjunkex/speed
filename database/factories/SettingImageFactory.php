<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SettingImage>
 */
class SettingImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'image' => $this->faker->imageUrl(),
            'title' => $this->faker->title,
            'description' => $this->faker->text,
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement(['why_us_cards', 'features', 'explorers', 'all_features', 'software_overview_images', 'testimonials', 'brands']),
            'image_align_left' => $this->faker->boolean,
            'points' => json_encode(['test point 1', 'test point 2']),
            'button_text' => 'click here',
            'button_link' => $this->faker->url,
            'status' => true,
        ];
    }
}
