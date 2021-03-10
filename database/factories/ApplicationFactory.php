<?php

namespace Database\Factories;

use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'job_id' => rand(1, 10), 
            'firstname' => $this->faker->name,
            'lastname' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail, 
            'phone' => $this->faker->unique()->phoneNumber,
            'location' => $this->faker->name,
            'cv' => $this->faker->image(public_path('images'),400,300, null, false), 
            'created_by' => rand(1, 10), 
        ];
    }
}
