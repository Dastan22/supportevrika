<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('initiator_name');
            $table->string('text');
            $table->string('image_src');
            $table->string('initiator_contact');
            $table->string('initiator_anydesk');
            $table->foreignId('dispatcher_id')->constrained("users");
            $table->foreignId('category_id')->constrained();
            $table->timestamp('taken_at')->useCurrent();
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
        Schema::dropIfExists('issues');
    }
}
