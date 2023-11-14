<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pdf_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('extracted_text');
            $table->timestamp('uploaded_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pdf_uploads');
    }
};
