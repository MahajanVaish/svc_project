<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('type', ['complaint', 'diagnosis']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert complaints
        $complaints = [
            'Fever with Rigor',
            'Fever with Chill',
            'Fever',
            'Low Grade',
            'High Grade',
            'Intermittent',
            'Continuous',
            'Headache',
            'Bodyache',
            'Generalized Weakness',
            'Fatigue',
            'Nausea',
            'Vomiting',
            'Abdominal Pain',
            'Throat Pain',
            'Throat Discomfort',
            'Sore throat',
            'Chest pain',
            'Coughing',
            'Dry Cough',
            'Cough With Yellow Expectorant',
            'Cough with White Expectorant',
            'Gabharaman',
            'Perspiration',
            'Anorexia',
            'Loose motion',
            'Lower Abdominal Pain',
            'Backache',
            'Muscular Pain',
            'Vertigo',
            'Tinnitus',
            'Giddiness',
            'Insomnia',
            'Bore Adam'
        ];

        // Insert diagnoses
        $diagnoses = [
            'P.Vivex Malaria',
            'P.Falciparum Malaria',
            'Dengue Fever',
            'Enteric Fever',
            'Viral Fever',
            'URTICARIA',
            'URTI',
            'LRTI',
            'Bronchial Asthma',
            'Acute Bronchitis',
            'Pneumonia',
            'Diabetes Type 2',
            'Hypertension',
            'Pre-Diabetic',
            'Borderline Hypertension',
            'Hypothyroidism',
            'Hyperthyroidism',
            'PCOD',
            'Pregnancy',
            'Acne',
            'Tinea pedis (Athlete\'s foot)',
            'Tinea corporis (Ringworm of the body)',
            'Tinea cruris (Jock itch)',
            'Tinea capitis (Scalp ringworm)',
            'Tinea unguium (Nail fungus)',
            'Onychomycosis',
            'Tinea manuum (Hand ringworm)',
            'Tinea faciei (Facial ringworm)',
            'Tinea barbae (Beard ringworm)',
            'Tinea versicolor (Pityriasis versicolor)',
            'Eczema',
            'Atopic Dermatitis',
            'Hairfall'
        ];

        // Insert complaints
        foreach ($complaints as $complaint) {
            DB::table('medical_conditions')->insert([
                'name' => $complaint,
                'type' => 'complaint',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Insert diagnoses
        foreach ($diagnoses as $diagnosis) {
            DB::table('medical_conditions')->insert([
                'name' => $diagnosis,
                'type' => 'diagnosis',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_conditions');
    }
};
