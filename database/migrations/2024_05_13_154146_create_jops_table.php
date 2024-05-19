<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jops', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->integer('required_candidates');
            $table->enum('work_area', ['Central', 'Northern', 'Southern', 'Eastern', 'Western']);
            $table->string('city');
            $table->enum('academic_qualification', ['Primary', 'Intermediate', 'Diploma', 'Bachelor', 'Master', 'Ph.D', 'Student']);
            $table->string('specialization')->nullable();
            $table->enum('language_level', ['Beginner', 'Intermediate', 'Advanced']);
            $table->string('nationality');
            $table->enum('disabilities_allowed', ['Yes', 'No']);
            $table->string('disability_type')->nullable();
            $table->integer('required_age')->nullable();
            $table->enum('work_type', ['remotly', 'onSite','hybrid']);
            $table->enum('gender', ['Female', 'Male']);
            $table->enum('working_hours', ['Full', 'Part']);
            $table->integer('basic_salary');
            $table->string('housing_allowance')->nullable();
            $table->string('transportation_allowance')->nullable();
            $table->string('other_allowances')->nullable();
            $table->integer('monthly_attendance_days');
            $table->integer('weekly_rest_days');
            $table->text('job_description')->nullable();
            $table->text('job_requirements')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jops');
    }
}
