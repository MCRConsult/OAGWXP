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
        $this->tableName = 'oagwxp_requisition_lines';
        $this->databaseUserMain = env('DB_USERNAME_ORACLE');
        $this->databaseUserDev  = env('DB_USERNAME_ORACLE_XXDEV');
    }

    public function up(): void
    {
        Schema::connection('oracle_oagwxp')->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('req_header_id');
            $table->integer('seq_no');
            $table->integer('supplier_id');
            $table->string('supplier_name')->nullable();
            $table->string('bank_account_number');
            $table->integer('amount', 18,2);
            $table->string('budget_plan');
            $table->string('budget_type');
            $table->string('expense_type');
            $table->string('expense_description');
            $table->string('segment_account')->nullable();
            $table->string('description')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('policy_no')->nullable();
            $table->string('vehicle_oil_type')->nullable();
            $table->string('utility_type')->nullable();
            $table->string('utility_detail')->nullable();
            $table->integer('unit_quantity')->nullable();
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('receipt_no')->nullable();
            $table->date('receipt_date')->nullable();
            $table->boolean('remaining_receipt_flag')->nullable();
            $table->string('remaining_receipt_no')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('oracle_oagwxp')->dropIfExists($this->tableName);
    }
};
