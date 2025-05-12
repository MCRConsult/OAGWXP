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
        $this->tableName = 'oagwxp_requisition_headers';
        $this->databaseUserMain = env('DB_USERNAME_ORACLE');
        $this->databaseUserDev  = env('DB_USERNAME_ORACLE_XXDEV');
    }

    public function up(): void
    {
        Schema::connection('oracle_oagwxp')->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('org_id')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('budget_source');
            $table->string('invoice_type');
            $table->string('document_category');
            $table->string('req_number');
            $table->date('req_date');
            $table->string('payment_type');
            $table->integer('supplier_id')->nullable();  // vendor_id
            $table->string('supplier_name')->nullable();  // vendor_id
            $table->string('multiple_supplier')->nullable();
            $table->string('description')->nullable();
            $table->integer('total_amount', 18, 2)->nullable();
            $table->string('requester');
            $table->string('status');
            $table->string('encumbrance_flag');
            $table->integer('invoice_reference_id')->nullable(); // invoice update
            $table->string('invioce_no_ref')->nullable(); // invoice update
            $table->date('clear_reference_id')->nullable(); // clear update

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
