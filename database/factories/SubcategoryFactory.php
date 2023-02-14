<?php

namespace Database\Factories;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subcategory>
 */
class SubcategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $subcategory_name = $this->faker->unique(true)->words($nb=2, $asText=true);
        $slug = Str::slug($subcategory_name);
        return [
            'name' => $subcategory_name,
            'slug' => $slug,
            'category_id'=>$this->faker->numberBetween(26,37)
        ];
    }
}
