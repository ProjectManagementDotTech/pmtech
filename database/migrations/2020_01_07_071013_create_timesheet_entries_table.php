<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheet_entries', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('project_id', 36)->nullable();
            $table->char('task_id', 36)->nullable();
            $table->char('user_id', 36);
            $table->char('workspace_id', 36)->nullable();
            $table->string('description');
            $table->timestamp('ended_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesheet_entries');
    }
}
