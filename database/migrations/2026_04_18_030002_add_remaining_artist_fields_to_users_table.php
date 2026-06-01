<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add missing artist profile fields
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('users', 'slug')) {
                $table->string('slug')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'specialization')) {
                $table->string('specialization')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('users', 'artist_statement')) {
                $table->text('artist_statement')->nullable()->after('specialization');
            }
            if (!Schema::hasColumn('users', 'education')) {
                $table->json('education')->nullable()->after('artist_statement');
            }
            if (!Schema::hasColumn('users', 'exhibitions')) {
                $table->json('exhibitions')->nullable()->after('education');
            }
            if (!Schema::hasColumn('users', 'awards')) {
                $table->json('awards')->nullable()->after('exhibitions');
            }
            if (!Schema::hasColumn('users', 'press')) {
                $table->json('press')->nullable()->after('awards');
            }
            if (!Schema::hasColumn('users', 'location')) {
                $table->string('location')->nullable()->after('press');
            }
            if (!Schema::hasColumn('users', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('location');
            }
            if (!Schema::hasColumn('users', 'years_active')) {
                $table->integer('years_active')->nullable()->after('cover_image');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('collector')->after('years_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name', 
                'phone',
                'bio',
                'avatar',
                'slug',
                'specialization',
                'artist_statement',
                'education',
                'exhibitions',
                'awards',
                'press',
                'location',
                'cover_image',
                'years_active',
                'role'
            ]);
        });
    }
};
