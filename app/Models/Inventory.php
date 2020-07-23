<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Inventory extends Model
{
    protected $table = "inventory";
    protected $fillable = ['INVT_PROD_ID', 'INVT_COLR_ID', 'INVT_SIZE_ID', 'INVT_CUNT'];

    public function product()
    {
        return $this->belongsTo("App\Models\Product", "INVT_PROD_ID", "id");
    }
    public function color()
    {
        return $this->belongsTo("App\Models\Color", "INVT_COLR_ID", "id");
    }
    public function size()
    {
        return $this->belongsTo("App\Models\Size", "INVT_SIZE_ID", "id");
    }


    static public function insertEntry($entryArr, $orderID = null)
    {
        $transactionCode = date_format(now(), "ymdHis");
        $date = date_format(now(), "Y-m-d H:i:s");
        DB::transaction(function () use ($entryArr, $transactionCode, $orderID, $date) {
            foreach ($entryArr as $row) {
                self::insert($row['modelID'], $row['colorID'], $row['sizeID'], (($orderID == null)) ? $row['count'] : -1 * $row['count'], $transactionCode, $orderID, $date);
            }
        });
    }

    static public function insert($modelID, $colorID, $sizeID, $count, $transactionCode, $orderID = null, $date=null)
    {

        DB::transaction(function () use ($modelID, $colorID, $sizeID, $count, $transactionCode, $orderID, $date) {
            $inventoryRow = self::firstOrNew(
                ["INVT_PROD_ID" => $modelID,
                "INVT_COLR_ID" => $colorID,
                "INVT_SIZE_ID" => $sizeID]
            );
            $inventoryRow->INVT_CUNT += $count;
           // dd($inventoryRow);
            $inventoryRow->save();
            // if (isset($inventoryRow->INVT_CUNT)) {
            //     $inventoryRow->INVT_CUNT += $count;
            //     $inventoryRow->save();
            // } else {
            //     $inventoryRow = new Inventory();
            //     $inventoryRow->INVT_PROD_ID = $modelID;
            //     $inventoryRow->INVT_COLR_ID = $colorID;
            //     $inventoryRow->INVT_SIZE_ID = $sizeID;
            //     $inventoryRow->INVT_CUNT = $count;
            //     $inventoryRow->save();
            // }
            if ($count > 0)
                self::addNewTransaction($inventoryRow->id, $inventoryRow->INVT_CUNT, $transactionCode, $orderID, $count, 0, $date);
            else
                self::addNewTransaction($inventoryRow->id, $inventoryRow->INVT_CUNT, $transactionCode, $orderID, 0, $count, $date);
        });
    }



    /////////////Insert New Transaction function
    static public function getGroupedTransactions()
    {
        return DB::table("inventory_transactions")->join("dash_users", "INTR_DASH_ID", "=", "dash_users.id")
            ->selectRaw("INTR_CODE, INTR_DATE, INTR_DASH_ID, SUM(INTR_IN) as totalIn, SUM(INTR_OUT) as totalOut, DASH_USNM")
            ->groupByRaw("INTR_CODE, INTR_DATE, INTR_DASH_ID, DASH_USNM")
            ->orderByDesc("INTR_DATE")
            ->limit(500)
            ->get();
    }

    static public function getTransactionByCode($code)
    {
        return DB::table("inventory_transactions")->join("dash_users", "INTR_DASH_ID", "=", "dash_users.id")
        ->join("inventory", "INTR_INVT_ID", "=", "inventory.id")
        ->join("products", "INVT_PROD_ID", "=", "products.id")
        ->join("colors", "INVT_COLR_ID", "=", "colors.id")
        ->join("sizes", "INVT_SIZE_ID", "=", "sizes.id")
        ->select("inventory_transactions.*", "dash_users.DASH_USNM", 'sizes.SIZE_NAME', "colors.COLR_NAME", "products.PROD_NAME", "inventory.INVT_PROD_ID")
        ->where("INTR_CODE", $code)
        ->get();
    }




    static private function addNewTransaction($inventoryID, $balance, $transactionCode, $orderID = null, $in = 0, $out = 0, $date=null)
    {
        DB::table("inventory_transactions")->insert([
            "INTR_DATE"     =>  ($date) ?? date_format(now(), "Y-m-d H:i:s"),
            "INTR_CODE"     =>  $transactionCode,
            "INTR_INVT_ID"  =>  $inventoryID,
            "INTR_DASH_ID"  =>  Auth::id(),
            'INTR_IN'       =>  $in,
            'INTR_OUT'      =>  $out,
            'INTR_BLNC'     =>  $balance,
            'INTR_ORDR_ID' =>  $orderID
        ]);
    }
}
