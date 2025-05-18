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
        $this->tableName = 'oagwxp_invoice_headers';
        $this->databaseUserMain = env('DB_USERNAME_ORACLE');
        $this->databaseUserDev  = env('DB_USERNAME_ORACLE_XXDEV');
    }

    public function up(): void
    {
        Schema::connection('oracle_oagwxp')->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('org_id')->nullable();
            $table->string('source_type')->nullable();
            $table->string('voucher_number')->nullable();
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->string('invoice_type');
            $table->string('document_category');
            $table->integer('supplier_id')->nullable();  // vendor_id
            $table->string('supplier_name')->nullable();  // vendor_id
            $table->string('payment_method')->nullable();
            $table->string('payment_term')->nullable();
            $table->date('clear_date')->nullable();
            $table->string('currency')->nullable();
            $table->date('contact_date')->nullable();
            $table->string('final_judgment')->nullable();
            $table->string('gfmis_document_number')->nullable();
            $table->integer('total_amount', 18, 2)->nullable();
            $table->string('description')->nullable();
            $table->string('note')->nullable();
            $table->string('requester');
            $table->string('status');
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
