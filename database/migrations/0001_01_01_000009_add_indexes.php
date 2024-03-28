<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add indicies for better performance.
 *
 * Class AddIndexes
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket', function (Blueprint $table) {
            $table->index('subject');
            $table->index('status_id');
            $table->index('priority_id');
            $table->index('user_id');
            $table->index('agent_id');
            $table->index('category_id');
            $table->index('completed_at');
        });

        Schema::table('ticket_comments', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('ticket_id');
        });

        Schema::table('ticket_settings', function (Blueprint $table) {
            $table->index('lang');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket', function (Blueprint $table) {
            $table->dropIndex('ticket_subject_index');
            $table->dropIndex('ticket_status_id_index');
            $table->dropIndex('ticket_priority_id_index');
            $table->dropIndex('ticket_user_id_index');
            $table->dropIndex('ticket_agent_id_index');
            $table->dropIndex('ticket_category_id_index');
            $table->dropIndex('ticket_completed_at_index');
        });

        Schema::table('ticket_comments', function (Blueprint $table) {
            $table->dropIndex('ticket_comments_user_id_index');
            $table->dropIndex('ticket_comments_ticket_id_index');
        });

        Schema::table('ticket_settings', function (Blueprint $table) {
            $table->dropIndex('ticket_settings_lang_index');
            $table->dropIndex('ticket_settings_slug_index');
        });
    }
};