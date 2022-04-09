<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\Tenant;
use http\Client\Curl\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $tenant = Tenant::create([
            "name" => "Empresa inicial",
            "email" => "teste@teste.com"
        ]);

        $user = \App\Models\User::create([
            "name" => "Empresa inicial",
            "email" => "teste@teste.com",
            "password" => Hash::make("123456"),
            "tenant_id" => $tenant->id
        ]);

        $students = Student::factory()->count(60)->create([
            "tenant_id" => $tenant->id
        ]);

        $plans = Plan::factory()->count(20)->create([
            "tenant_id" => $tenant->id
        ]);

        foreach ($students as $student) {
            $plan = $plans->random();
            $dataInicio = (new \DateTime())->format("Y-m-d");
            $dataFim = (new \DateTime())
                ->add(\DateInterval::createFromDateString("+{$plan->duration}months"))
                ->format("Y-m-d");

            Subscription::create([
                "student_id" => $student->id,
                "plan_id" => $plan->id,
                "dt_start" => $dataInicio,
                "dt_end" => $dataFim,
            ]);
        }
    }
}
