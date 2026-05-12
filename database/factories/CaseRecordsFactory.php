<?php

namespace Database\Factories;

use App\Models\CaseRecords;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CaseRecords>
 */
class CaseRecordsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'title' => 'Case #001',
            'description' => 'Assistance Needed',
            'status' => 'open',
            'assigned_to' => 2, // case worker
            'client_id' => 1,
            'category_id' => 1,
            'created_by' => 1, // admin
        ];
    }
}
