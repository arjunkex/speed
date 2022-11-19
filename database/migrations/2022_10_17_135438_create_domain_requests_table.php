<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('domain_requests', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->unsignedBigInteger('modified_by_id')->nullable()->comment('Admin\'s userId who modified the request');
            $table->string('requested_domain');
            $table->integer('status')->default(0)->comment('0: Pending, 1: Connected, 2: Rejected, 3: Removed');
            $table->timestamp('modified_at')->nullable();
            $table->timestamps();

            $table->foreign('modified_by_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domain_requests', function (Blueprint $table) {
            $table->dropForeign(['modified_by']);
            $table->dropForeign(['tenant_id']);
            $table->dropIfExists();
        });
    }
};
