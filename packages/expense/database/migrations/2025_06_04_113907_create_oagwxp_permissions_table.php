<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableName;
    public function __construct() {
        $this->tableName = 'oagwxp_permissions';
    }

    public function up(): void
    {
        Schema::connection('oracle_oagwxp')->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('permission_group')->nullable();
            $table->string('permission_code')->unique();
            $table->string('description')->nullable();
            $table->boolean('status');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('oracle_oagwxp')->dropIfExists($this->tableName);
    }
};
