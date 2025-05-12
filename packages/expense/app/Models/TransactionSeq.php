<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionSeq extends Model
{
    protected $table = 'oagwxp_transaction_sequence';
    protected $connection = 'oracle_oagwxp';
    protected $fillable = ['org_id', 'name', 'year', 'prefix'];

    public static function getTranID($orgId, $name, $year, $prefix)
    { 
        $new_tran_id = '';
        try {
            \DB::beginTransaction();
            // year format date('y') ex. 16,17
            // $last_seq = self::where('name',$name)->first();
            $last_seq = self::firstOrCreate(['org_id'=> $orgId, 'name' => $name, 'year' => date('y', strtotime($year)), 'prefix' => $prefix]);
            $last_seq = self::lockForUpdate()->find($last_seq->id);
            $new_tran_id = (int)$last_seq->tran_id+1;
            $last_seq->tran_id = $new_tran_id;
            $last_seq->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Database error: ' . $e->getMessage());
        }

        return $new_tran_id;
    }
}
