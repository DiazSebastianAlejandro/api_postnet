<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('number', 8)->unique();
            $table->enum('brand', ['VISA', 'AMEX'])->index();
            $table->string('bank');
            $table->decimal('available_limit', 10, 2);

            $table->string('user_dni', 15)->index();
            $table->string('user_first_name');
            $table->string('user_last_name');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('cards');
    }
};
