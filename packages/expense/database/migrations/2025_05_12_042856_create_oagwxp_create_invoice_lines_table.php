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
        $this->tableName = 'oagwxp_invoice_lines';
        $this->databaseUserMain = env('DB_USERNAME_ORACLE');
        $this->databaseUserDev  = env('DB_USERNAME_ORACLE_XXDEV');
    }

    public function up(): void
    {
        Schema::connection('oracle_oagwxp')->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_header_id');
            $table->string('source_type')->nullable();
            $table->integer('seq_number');
            $table->integer('supplier_id');
            $table->string('supplier_name')->nullable();
            $table->integer('supplier_site')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('budget_plan');
            $table->string('budget_type');
            $table->string('expense_type');
            $table->string('expense_description');
            $table->string('expense_account')->nullable();
            $table->integer('amount', 18,2);
            $table->string('description')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('policy_number')->nullable();
            $table->string('vehicle_oil_type')->nullable();
            $table->string('utility_type')->nullable();
            $table->string('utility_detail')->nullable();
            $table->integer('unit_quantity')->nullable();
            $table->string('req_invoice_number')->nullable();
            $table->date('req_invoice_date')->nullable();
            $table->string('req_receipt_number')->nullable();
            $table->date('req_receipt_date')->nullable();
            $table->boolean('remaining_receipt_flag')->nullable();
            $table->integer('remaining_receipt_id')->nullable();
            $table->string('remaining_receipt_number')->nullable();

            $table->string('tax_code')->nullable();
            $table->string('tax_amount', 18,2)->nullable();
            $table->string('wht_code')->nullable();
            $table->string('wht_amount', 18,2)->nullable();

            $table->integer('ar_receipt_id')->nullable();
            $table->string('ar_receipt_number')->nullable();
            $table->string('reference_req_number')->nullable();
            $table->integer('origin_amount', 18,2);
            $table->string('contract_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('oracle_oagwxp')->dropIfExists($this->tableName);
    }
};
