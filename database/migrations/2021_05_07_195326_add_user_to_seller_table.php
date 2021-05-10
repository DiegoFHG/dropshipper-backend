<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserToSellerTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('sellers', function (Blueprint $table) {
      $table->foreignId('user')->onDelete('cascade')->constrained('users');
    });
  }
}
