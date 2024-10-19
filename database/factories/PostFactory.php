<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Har bir post uchun yangi foydalanuvchi yaratish
            'title' => $this->faker->sentence(),
            'short_content' => $this->faker->paragraph(1),
            'content' => $this->faker->paragraph(3),
            'image_url' => 'https://picsum.photos/640/480?random=' . rand(1, 1000), // Manzarali rasm URL'si
        ];
    }
}
