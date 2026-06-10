<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('monthly_assessments', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('patient_inquiry_id');
            $table->string('patient_id')->nullable();
            
            // Basic info
            $table->date('assessment_date');
            $table->string('status')->default('draft'); 
            
            
            $table->decimal('waist_upper', 5, 1)->nullable();
            $table->decimal('waist_middle', 5, 1)->nullable();
            $table->decimal('waist_lower', 5, 1)->nullable();
            $table->decimal('hips', 5, 1)->nullable();
            $table->decimal('thighs', 5, 1)->nullable();
            $table->decimal('arms', 5, 1)->nullable();
            $table->decimal('waist_hips_ratio', 5, 2)->nullable();
            $table->decimal('weight', 5, 1)->nullable();
            $table->decimal('bmi', 5, 1)->nullable();
            
            // Section 2: BCA Subcutaneous Fat
            $table->decimal('bca_vbf', 5, 1)->nullable();
            $table->decimal('bca_arms', 5, 1)->nullable();
            $table->decimal('bca_trunk', 5, 1)->nullable();
            $table->decimal('bca_legs', 5, 1)->nullable();
            $table->decimal('bca_sf', 5, 1)->nullable();
            $table->decimal('bca_vf', 5, 1)->nullable();
            
            // Section 3: Skeletal Muscle Mass
            $table->decimal('muscle_vbf', 5, 1)->nullable();
            $table->decimal('muscle_arms', 5, 1)->nullable();
            $table->decimal('muscle_trunk', 5, 1)->nullable();
            $table->decimal('muscle_legs', 5, 1)->nullable();
            
            // Additional fields
            $table->text('notes')->nullable();
            $table->string('assessed_by')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('patient_inquiry_id')->references('id')->on('patient_inquiry')->onDelete('cascade');
            
            // Indexes
            $table->index('branch_id');
            $table->index('patient_inquiry_id');
            $table->index('assessment_date');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('monthly_assessments');
    }
};

