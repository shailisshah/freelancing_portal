<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->mediumInteger('messages');
            $table->longText('documents')->nullable();
            $table->date('due_date');
            $table->unsignedInteger('assigned_to');
            $table->tinyInteger('project_submission_status')->nullable()->comment('3->project submitted,4->send back to client	');
            $table->tinyInteger('status')->default(1)->comment('1->Active,2->Inactive,3->delete');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('projects');
    }

}
