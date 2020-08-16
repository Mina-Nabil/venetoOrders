<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Cash extends Model
{
    //Cash Model
    static function getTrans($subType = 0)
    {
        $baseQuery = DB::table('cash')->select('cash.*', 'trans_subtype.TRST_NAME', 'trans_type.TRTP_NAME')
            ->leftJoin('trans_subtype', 'CASH_TRST_ID', '=', 'trans_subtype.id')
            ->leftJoin('trans_type', 'trans_subtype.TRST_TRTP_ID', '=', 'trans_type.id');

        if ($subType != 0 && is_numeric($subType))
            $baseQuery = $baseQuery->where([["CASH_TRST_ID", $subType]]);

        return  $baseQuery->orderBy('id', 'desc')->limit(500)->get();
    }

    static function getReport($from, $to)
    {
        return DB::table('cash')->select('cash.*', 'trans_subtype.TRST_NAME', 'trans_type.TRTP_NAME')->leftJoin('trans_subtype', 'CASH_TRST_ID', '=', 'trans_subtype.id')
            ->leftJoin('trans_type', 'trans_subtype.TRST_TRTP_ID', '=', 'trans_type.id')->where([
                ['CASH_DATE', '>', $from],
                ['CASH_DATE', '<', date('Y-m-d', strtotime('+1 day', strtotime($to)))],
                ['CASH_EROR', 0]
            ])->orderBy('id', 'desc')->limit(500)->get();
    }

    static function getCashSpent($from, $to, $subType)
    {
        $from = (new DateTime($from))->format('Y-m-d H:i:s');
        $to = ((new DateTime($to))->setTime(23, 59, 59))->format('Y-m-d H:i:s');
        return DB::table('cash')->selectRaw("SUM(CASH_IN) as totalIn, SUM(CASH_OUT) as totalOut ")->where([
            ["CASH_TRST_ID", '=', $subType],
            ["CASH_DATE", '>=', $from],
            ["CASH_DATE", '<=', $to],
        ])->get()->first();
    }

    static function getCashSpentByType($from, $to, $type)
    {
        $from = (new DateTime($from))->format('Y-m-d H:i:s');
        $to = ((new DateTime($to))->setTime(23, 59, 59))->format('Y-m-d H:i:s');
        return DB::table('cash')->selectRaw("SUM(CASH_IN) as totalIn, SUM(CASH_OUT) as totalOut ")
        ->join('trans_subtype', "CASH_TRST_ID", "=", "trans_subtype.id")
        ->join('trans_type', "TRST_TRTP_ID", "=", "trans_type.id")
        ->where([
            ["TRST_TRTP_ID", '=', $type],
            ["CASH_DATE", '>=', $from],
            ["CASH_DATE", '<=', $to],
        ])->get()->first();
    }

    static function insertTran($title, $in = 0, $out = 0, $comment = null, $isError = 0, $transType = null)
    {

        DB::transaction(function () use ($title, $in, $out, $comment, $isError, $transType) {

            $balance = self::getCashBalance() + $in - $out;
            $insertArr = [
                'CASH_NAME' => $title,
                'CASH_IN'   => $in,
                'CASH_OUT'  => $out,
                'CASH_BLNC' => $balance,
                'CASH_CMNT' => $comment,
                'CASH_EROR' => $isError,
                'CASH_TRST_ID' => $transType,
                'CASH_DATE' => date('Y-m-d H:i:s')
            ];
            if (isset($transType)) {
                $typeBalance = self::getCashTypeBalance($transType) - $in + $out;
                $insertArr['CASH_TRST_BLNC'] = $typeBalance;
            }
            DB::table('cash')->insertGetId($insertArr);
        });
    }

    static function getCashBalance()
    {
        return DB::table('cash')->latest('id')->first()->CASH_BLNC;
    }

    static function getCashTypeBalance($subtype)
    {
        $latestRow = DB::table('cash')->where([[
            "CASH_TRST_ID", $subtype
        ]])->orderByDesc("id")->first();

        if (isset($latestRow->CASH_TRST_BLNC) && $latestRow->CASH_TRST_BLNC != 0)
            return $latestRow->CASH_TRST_BLNC;
        else
            return 0;
    }

    static function correctFaultyTran($id)
    {
        $faulty = self::getOneRecord($id);
        if ($faulty == null || $faulty->CASH_EROR != 0) return 0;
        try {
            $exception = DB::transaction(function () use ($id, $faulty) {
                self::markTranError($id);
                //self::insertTran("Error Correction for TR#" . $id, $faulty->CASH_IN*-1, $faulty->CASH_OUT*-1, "Automated Transaction to correct Transaction number " . $id, 2);
            });
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    static function unmarkTranError($id)
    {
        $faulty = self::getOneRecord($id);
        if ($faulty == null || $faulty->CASH_EROR == 0) return 0;
        return DB::table("cash")->where('id', $id)->update([
            "CASH_EROR" => 0
        ]);
    }

    static private function getOneRecord($id)
    {
        return DB::table('cash')->find($id);
    }

    static private function markTranError($id)
    {
        return DB::table("cash")->where('id', $id)->update([
            "CASH_EROR" => 1
        ]);
    }
}
