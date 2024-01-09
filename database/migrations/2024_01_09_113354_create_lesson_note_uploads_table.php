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
        Schema::create('lesson_note_uploads', function (Blueprint $table) {
            $table->id();
            $table->longText("NotesTicket")->nullable();
            $table->longText("TeacherId")->nullable();
            $table->longText("TeacherName")->nullable();
            $table->longText("NoteFiles")->nullable();
            $table->longText("HeadTeacherComment")->nullable();
            $table->dateTime("HeadTeacherDateSigned")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_note_uploads');
    }
};
