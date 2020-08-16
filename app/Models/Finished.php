<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Finished extends Model
{
    //
    protected   $table = "finished";
    public      $timestamps = false;

    public static function getAvailable(){
        $ret = array();
        $ret['data'] = DB::table("finished")->join("models", "FNSH_MODL_ID", '=', 'models.id')
                            ->join("brands", "FNSH_BRND_ID", '=', "brands.id")
                            ->join("types", "MODL_TYPS_ID", '=', 'types.id')
                            ->join("raw", "TYPS_RAW_ID", '=', 'raw.id')
                            ->select("finished.id", "brands.BRND_NAME", "models.MODL_UNID", "models.MODL_IMGE", "types.TYPS_NAME", "raw.RAW_NAME")
                            ->selectRaw("SUM(FNSH_36_AMNT + FNSH_38_AMNT + FNSH_40_AMNT + FNSH_42_AMNT + FNSH_44_AMNT + FNSH_46_AMNT + FNSH_48_AMNT + FNSH_50_AMNT + FNSH_52_AMNT) as itemsCount")
                            ->where("FNSH_36_AMNT"  , '!=', '0')
                            ->orWhere("FNSH_38_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_40_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_42_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_44_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_46_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_48_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_50_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_52_AMNT"  , '!=', '0' )
                            ->groupBy('finished.id')
                            ->get();
        $ret['totals'] = DB::table("finished")->join("models", "FNSH_MODL_ID", '=', 'models.id')
                            ->join("brands", "FNSH_BRND_ID", '=', "brands.id")
                            ->selectRaw("SUM(FNSH_36_AMNT) as total36, SUM(FNSH_38_AMNT) as total38, SUM(FNSH_40_AMNT) as total40, SUM(FNSH_42_AMNT) as total42, SUM(FNSH_44_AMNT) as total44, SUM(FNSH_46_AMNT) as total46, SUM(FNSH_48_AMNT) as total48, SUM(FNSH_50_AMNT) as total50, SUM(FNSH_52_AMNT) as total52 ")
                            ->where("FNSH_36_AMNT"  , '!=', '0')
                            ->orWhere("FNSH_38_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_40_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_42_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_44_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_46_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_48_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_50_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_52_AMNT"  , '!=', '0' )
                            ->get()->first();
        return $ret;
    }
    public static function getAllFinished(){
        $ret['data'] = DB::table("finished")->join("models", "FNSH_MODL_ID", '=', 'models.id')
                            ->join("brands", "FNSH_BRND_ID", '=', "brands.id")
                            ->select("finished.*", "brands.BRND_NAME", "models.MODL_UNID")
                            ->get();
        return $ret['data'];
    }

    public static function getFinishedRow($modelID, $brandID){
        return DB::table("finished")->where([
            ["FNSH_MODL_ID" , '=',  $modelID],
            ["FNSH_BRND_ID" , '=', $brandID]
            ])->first();
    }

    public static function insertFinishedEntry($entryArr){
        DB::transaction(function () use ($entryArr){
            foreach($entryArr as $entry){
                self::insertFinished($entry['model'], $entry['brand'], $entry['price'], $entry['amount36'], $entry['amount38'], $entry['amount40'], $entry['amount42'], $entry['amount44'], $entry['amount46'], $entry['amount48'], $entry['amount50']);
            }
        });
    }

    public static function insertSoldEntry($entryArr, $isReturn=-1){
        DB::transaction(function () use ($entryArr, $isReturn){
            foreach($entryArr as $entry){
                self::insertFinished(null, null, $isReturn*$entry['price'], $isReturn*$entry['amount36'], $isReturn*$entry['amount38'], $isReturn*$entry['amount40'], $isReturn*$entry['amount42'], $isReturn*$entry['amount44'], $isReturn*$entry['amount46'], $isReturn*$entry['amount48'], $isReturn*$entry['amount50'], $entry['finished']);
            }
        });
    }

    public static function insertFinished($modelID, $brandID, $price=0, $amount36 = 0, $amount38 = 0, $amount40 = 0, $amount42 = 0, $amount44 = 0, $amount46 = 0, $amount48 = 0, $amount50 = 0, $finished=null){

            if($finished == null){
                $finished = self::getFinishedRow($modelID, $brandID);
                if($finished !== null)
                    $finished = $finished->id;
            }
            
                if($finished == null){
                    return $id = DB::table("finished")->insertGetId([
                        "FNSH_MODL_ID" => $modelID, 
                        "FNSH_BRND_ID" => $brandID, 
                        "FNSH_36_AMNT" => $amount36, 
                        "FNSH_38_AMNT" => $amount38, 
                        "FNSH_40_AMNT" => $amount40, 
                        "FNSH_42_AMNT" => $amount42, 
                        "FNSH_44_AMNT" => $amount44, 
                        "FNSH_46_AMNT" => $amount46, 
                        "FNSH_48_AMNT" => $amount48, 
                        "FNSH_50_AMNT" => $amount50, 
                        "FNSH_PRCE" => $price, 
                    ]);
                }
                else {
                    if($amount36!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_36_AMNT", $amount36);
                    if($amount38!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_38_AMNT", $amount38);
                    if($amount40!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_40_AMNT", $amount40);
                    if($amount42!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_42_AMNT", $amount42);
                    if($amount44!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_44_AMNT", $amount44);
                    if($amount46!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_46_AMNT", $amount46);
                    if($amount48!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_48_AMNT", $amount48);
                    if($amount50!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_50_AMNT", $amount50);
                }
                
    }

    public static function updatePrice($id, $price){
        return DB::table("finished")->where('id', '=', $id)->update([
            "FNSH_PRCE" => $price
        ]);
    }

    public function incrementSizeQuantity($quantity, $size){
        switch($size){
            case 36:
                $this->FNSH_36_AMNT = $this->FNSH_36_AMNT + $quantity;
                return $this->save();
            break;
            case 38:
                $this->FNSH_38_AMNT = $this->FNSH_38_AMNT + $quantity;
                return $this->save();
            break;
            case 40:
                $this->FNSH_40_AMNT = $this->FNSH_40_AMNT + $quantity;
                return $this->save();
            break;
            case 42:
                $this->FNSH_42_AMNT = $this->FNSH_42_AMNT + $quantity;
                return $this->save();
            break;
            case 44:
                $this->FNSH_44_AMNT = $this->FNSH_44_AMNT + $quantity;
                return $this->save();
            break;
            case 46:
                $this->FNSH_46_AMNT = $this->FNSH_46_AMNT + $quantity;
                return $this->save();
            break;
            case 48:
                $this->FNSH_48_AMNT = $this->FNSH_48_AMNT + $quantity;
                return $this->save();
            break;
            case 50:
                $this->FNSH_50_AMNT = $this->FNSH_50_AMNT + $quantity;
                return $this->save();
            break;

            default:
            return false;
            break;
        }
    }
}
