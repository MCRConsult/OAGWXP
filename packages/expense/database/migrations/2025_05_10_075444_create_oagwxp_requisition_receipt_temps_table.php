<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableName;
    protected $databaseUserMain;
    protected $databaseUserDev;

    public function __construct() {
        $this->tableName = 'oagwxp_requisition_receipt_temps';
        $this->databaseUserMain = env('DB_USERNAME_ORACLE');
        $this->databaseUserDev  = env('DB_USERNAME_ORACLE_XXDEV');
    }

    public function up(): void
    {
        Schema::connection('oracle_oagwxp')->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('org_id')->nullable();
            $table->integer('seq_number');
            $table->string('reference_number');
            $table->boolean('remaining_receipt_flag')->nullable();
            $table->number('remaining_receipt_id')->nullable();
            $table->string('remaining_receipt_number')->nullable();
            $table->number('amount', 18, 2)->nullable();
            $table->string('expense_account')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('invoice_type')->nullable();
            $table->string('remittance_flag')->nullable();
            $table->string('requisition_header_id')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('creation_by')->nullable();
            $table->integer('updation_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('oracle_oagwxp')->dropIfExists($this->tableName);
    }
};
