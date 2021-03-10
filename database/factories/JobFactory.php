<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class JobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Job::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(){

    $categories = collect(['Tech', 'Health care', 'Hospitality', 'Customer Service', 'Marketing']);
    $types = collect(['Full-time', 'Temporary', 'Contract', 'Permanent', 'Internship', 'Volunteer']);
    $conditions = collect(['Remote', 'Part Remote', 'On-Premise']);
   
    return [
        'title' => $this->faker->sentence, 
        'description' => $this->faker->paragraph,
        'location' => $this->faker->sentence,
        'category' => $categories->random(), 
        'benefits' => $this->faker->sentence,
        'salary' => $this->faker->sentence,
        'type' => $types->random(), 
        'work_condition' => $conditions->random(),
        'created_by' => rand(1, 10), 
    ];
    
}
}