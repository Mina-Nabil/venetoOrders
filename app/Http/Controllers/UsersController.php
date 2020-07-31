<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Gender;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    protected $data;
    protected $homeURL = "users/show/all";

    public function __construct()
    {
        $this->middleware("auth");
    }

    private function initHomeArr($type = -1) // 0 all - 1 latest - 2 top
    {

        $this->data['title'] = "All Registered Clients";
        $this->data['type'] = $type;
        if ($type == 1)
            $this->data['items'] = User::latest()->limit(100)->get();
        else
            $this->data['items'] = User::all()->sortByDesc('id');

        $this->data['subTitle'] = "Manage Clients";
        $this->data['cols'] = ['id', 'Full Name', 'Email', 'Mob#', 'Gender', 'Area', 'Since', 'Edit'];
        $this->data['atts'] = [
            'id',
            ['attUrl' => ["url" => 'users/profile', "urlAtt" => 'id', "shownAtt" =>  "USER_NAME"]],
            ['verified' => ['att' => 'USER_MAIL', 'isVerified' => 'USER_MAIL_VRFD']],
            ['verified' => ['att' => 'USER_MOBN', 'isVerified' => 'USER_MOBN_VRFD']],
            ['foreign' => ['gender', 'GNDR_NAME']],
            ['foreign' => ['area', 'AREA_NAME']],
            // ['sumForeign' => ['rel' => 'orders', 'att' => 'ORDR_TOTL']],
            ['date' => ['att' => 'created_at', 'format' => 'Y-M-d']],
            ['edit' => ['url' => 'users/edit/', 'att' => 'id']]
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    private function initAddArr($userID = -1)
    {
        if ($userID != -1) {
            $this->data['user'] = User::findOrFail($userID);
            $this->data['formURL'] = "users/update";
        } else {
            $this->data['formURL'] = "users/insert/";
        }
        $this->data['genders'] = Gender::all();
        $this->data['areas']  = Area::where("AREA_ACTV", "1")->get();
        $this->data['formTitle'] = "Add New User";
        $this->data['isCancel'] = true;
        $this->data['homeURL'] = $this->homeURL;
    }

    private function initProfileArr($id)
    {
        $this->data['user'] = User::findOrFail($id);
        $this->data['userMoney'] = $this->data['user']->moneyPaid();
        $this->data['formURL'] = "users/update";
        $this->data['genders'] = Gender::all();
        $this->data['areas']  = Area::where("AREA_ACTV", "1")->get();

        //Orders Array
        $this->data['orderList']    = Order::getOrdersByUser($id);
        $this->data['cardTitle'] = false;
        $this->data['ordersCols'] = ['id', 'Status', 'Payment',  'Items', 'Ordered On', 'Total'];
        $this->data['orderAtts'] = [
            ['attUrl' => ['url' => "orders/details", "shownAtt" => 'id', "urlAtt" => 'id']],
            [
                'stateQuery' => [
                    "classes" => [
                        "1" => "label-info",
                        "2" => "label-warning",
                        "3" =>  "label-dark bg-dark",
                        "4" =>  "label-success",
                        "5" =>  "label-danger",
                    ],
                    "att"           =>  "ORDR_STTS_ID",
                    'foreignAtt'    => "STTS_NAME",
                    'url'           => "orders/details/",
                    'urlAtt'        =>  'id'
                ]
            ],
            'PYOP_NAME',
            'itemsCount',
            'ORDR_OPEN_DATE',
            'ORDR_TOTL'
        ];

        //Cart Array
        $this->data['cartList'] = $this->data['user']->cart;
        $this->data['cartCols'] = ['Model', 'Color', 'Size', 'Count'];
        $this->data['cartAtts'] = [
            ['foreignUrl' => ['products/details', 'INVT_PROD_ID', 'product', 'PROD_NAME']],
            ['foreign' => ['color', 'COLR_NAME']],
            ['foreign' => ['size', 'SIZE_NAME']],
            'INVT_CUNT'
        ];

        //Wishlist Array
        $this->data['wishlistList'] = $this->data['user']->cart;
        $this->data['wishlistCols'] = ['Barcode', 'Model Title', 'Arabic Title', "in Stock", 'Price', 'Offer'];
        $this->data['wishlistAtts'] = [
            'PROD_BRCD',
            ['attUrl' => ['url' => 'products/details', 'urlAtt' => "id", "shownAtt" => "PROD_NAME"]],
            ['attUrl' => ['url' => 'products/details', 'urlAtt' => "id", "shownAtt" => "PROD_ARBC_NAME"]],
            ['sumForeign' => ['rel'=>"stock", "att"=>"INVT_CUNT"]] ,
            'PROD_PRCE',
            'PROD_OFFR',
        ];
        //Items Bought
        $this->data['boughtList'] = $this->data['user']->itemsBought();
        $this->data['boughtCols'] = ['Model', 'Color', 'Size', 'Count'];
        $this->data['boughtAtts'] = [
            'PROD_NAME',
             'COLR_NAME',
            'SIZE_NAME',
            'itemCount'
        ];
    }

    public function home()
    {
        $this->initHomeArr(0);
        return view("users.table", $this->data);
    }

    public function latest()
    {
        $this->initHomeArr(1);
        return view("users.table", $this->data);
    }

    public function top()
    {
        $this->initHomeArr(2);
        return view("users.table", $this->data);
    }

    public function add()
    {
        $this->initAddArr();
        return view("users.add", $this->data);
    }

    public function edit($id)
    {
        $this->initAddArr($id);
        return view("users.add", $this->data);
    }

    public function profile($id)
    {
        $this->initProfileArr($id);
        return view("users.profile", $this->data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            "name"              => "required",
            "pass"              => "required|between:4,24",
            "mob"               => "required|numeric",
            "mail"              => "required|email|unique:users,USER_MAIL",
            "gender"        => "required|exists:genders,id",
            "area"          => "required|exists:areas,id"
        ]);

        $user = new User();
        $user->USER_NAME = $request->name;
        $user->USER_PASS = $request->pass;
        $user->USER_MOBN = $request->mob;
        $user->USER_MOBN_VRFD = $request->isMobVerified ? 1 : 0;
        $user->USER_MAIL = $request->mail;
        $user->USER_MAIL_VRFD = $request->isMailVerified ? 1 : 0;
        $user->USER_GNDR_ID = $request->gender;
        $user->USER_AREA_ID = $request->area;

        $user->save();

        return redirect("users/profile/" . $user->id);
    }

    public function update(Request $request)
    {
        $request->validate([
            "id"          => "required",
        ]);
        $user = User::findOrFail($request->id);
        $request->validate([
            "name"          => "required",
            "pass"          => "nullable|between:4,24",
            "mob"           => "required|numeric",
            "mail"          => ["required", "email",  Rule::unique('users', "USER_MAIL")->ignore($user->USER_MAIL, "USER_MAIL"),],
            "gender"        => "required|exists:genders,id",
            "area"          => "required|exists:areas,id"
        ]);

        $user->USER_NAME = $request->name;
        if (isset($request->pass))
            $user->USER_PASS = $request->pass;
        $user->USER_MOBN = $request->mob;
        $user->USER_MOBN_VRFD = $request->isMobVerified ? 1 : 0;
        $user->USER_MAIL = $request->mail;
        $user->USER_MAIL_VRFD = $request->isMailVerified ? 1 : 0;
        $user->USER_GNDR_ID = $request->gender;
        $user->USER_AREA_ID = $request->area;

        $user->save();


        return redirect("users/profile/" . $user->id);
    }
}
