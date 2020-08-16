<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Cash;

class Client extends Model
{
    public $timestamps = false;

    static function insertTrans($client, $sales, $cash, $notespay, $discount, $return, $comment, $desc = null)
    {

        $clientLastTrans  = self::getLastTransaction($client);
        if ($clientLastTrans !== null) {

            $cashBalance    = $clientLastTrans->CLTR_CASH_BLNC;
            $discBalance    = $clientLastTrans->CLTR_DISC_BLNC;
            $notesBalance   = $clientLastTrans->CLTR_NTPY_BLNC;
            $salsBalance    = $clientLastTrans->CLTR_SALS_BLNC;
            $returnBalance  = $clientLastTrans->CLTR_RTRN_BLNC;
            $oldBalance     = $clientLastTrans->CLTR_BLNC;
        } else {
            $cashBalance    = 0;
            $discBalance    = 0;
            $notesBalance   = 0;
            $salsBalance    = 0;
            $returnBalance  = 0;
            $oldBalance     = self::getClientBalance($client);
        }

        DB::transaction(function () use (
            $client,
            $sales,
            $cash,
            $notespay,
            $discount,
            $return,
            $comment,
            $cashBalance,
            $discBalance,
            $salsBalance,
            $notesBalance,
            $returnBalance,
            $oldBalance,
            $desc
        ) {

            $newBalance     =   $oldBalance + $sales - $cash - $notespay - $return - $discount;

            $id = DB::table("client_trans")->insertGetId([
                "CLTR_CLNT_ID"      => $client,
                "CLTR_SALS_AMNT"    => (float) $sales,
                "CLTR_SALS_BLNC"    => (float) $salsBalance + $sales,
                "CLTR_CASH_AMNT"    => (float) $cash,
                "CLTR_CASH_BLNC"    => (float) $cashBalance + $cash,
                "CLTR_DISC_AMNT"    => (float) $discount,
                "CLTR_DISC_BLNC"    => (float) $discBalance + $discount,
                "CLTR_NTPY_AMNT"    => (float) $notespay,
                "CLTR_NTPY_BLNC"    => (float) $notesBalance + $notespay,
                "CLTR_RTRN_AMNT"    => (float) $return,
                "CLTR_RTRN_BLNC"    => (float) $returnBalance + $return,
                "CLTR_BLNC"         => $newBalance,
                "CLTR_Date"         => date("Y-m-d H:i:s"),
                "CLTR_CMNT"         =>  $comment,
                "CLTR_DESC"         =>  $desc
            ]);

            if ($cash != 0) {
                $clientDetails = self::getClient($client);
                Cash::insertTran("Client ({$clientDetails->CLNT_NAME}) TRN.# " . $id, $cash, 0, $comment);
            }


            self::updateBalance($client, $newBalance);
        });
    }

    static function getClient($id)
    {
        return DB::table('clients')->select('clients.*')
            ->where('clients.id', $id)
            ->first();
    }

    static function getClientBalance($clientID)
    {
        return DB::table('clients')->where('id', $clientID)->select("CLNT_BLNC")->first()->CLNT_BLNC;
    }

    static function getLastTransaction($clientID)
    {
        return DB::table("client_trans")->where("CLTR_CLNT_ID", $clientID)->orderBy('id', 'desc')->first();
    }

    static function updateBalance($clientID, $balance)
    {
        return DB::table('clients')->where('id', $clientID)->update([
            "CLNT_BLNC"         => $balance
        ]);
    }
}
