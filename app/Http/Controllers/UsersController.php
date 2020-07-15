<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Gender;
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

        $this->data['title'] = "All Registered Users";
        $this->data['type'] = $type;
        $this->data['items'] = User::all();
        $this->data['subTitle'] = "Manage Users";
        $this->data['cols'] = ['id', 'Full Name', 'Email', 'Mob#', 'Gender', 'Area', 'Edit'];
        $this->data['atts'] = [
            'id',
            ['url' => ['user/profile/', "att" =>  "USER_NAME"]],
            'USER_MAIL',
            'USER_MOBN',
            ['foreign' => ['gender', 'GNDR_NAME']],
            ['foreign' => ['area', 'AREA_NAME']],
            ['edit' => ['url' => 'users/edit/', 'att' => 'id']]
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    private function initAddArr($userID = -1)
    {
        if ($userID != -1) {
            $this->data['user'] = User::findOrFail($userID);
            $this->data['formURL'] = "users/update/" . $userID;
        } else {
            $this->data['formURL'] = "users/insert/";
        }
        $this->data['genders'] = Gender::all();
        $this->data['areas']  = Area::all();
        $this->data['formTitle'] = "Add New User";
        $this->data['isCancel'] = true;
        $this->data['homeURL'] = $this->homeURL;
    }

    private function initProfileArr($id){
        $this->data['user'] = User::findOrFail($id);
    }

    public function home()
    {
        $this->initHomeArr(1);
        return view("users.table", $this->data);
    }

    public function latest()
    {
        $this->initHomeArr(2);
        return view("users.table", $this->data);
    }

    public function top()
    {
        $this->initHomeArr(3);
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

    public function profile($id){
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
            "gender"        => "required|exists:gender,id",
            "area"          => "required|exists:areas,id"
        ]);

        $user = new User();
        $user->USER_NAME = $request->name;
        $user->USER_PASS = $request->pass;
        $user->USER_MOBN = $request->mob;
        $user->USER_MOBN_VRFD = $request->isMobVerified;
        $user->USER_MAIL = $request->mail;
        $user->USER_MAIL_VRFD = $request->isMailVerified;
        $user->USER_GNDR_ID = $request->gender;
        $user->USER_AREA_ID = $request->area;

        $user->save();

        return redirect($this->homeURL);
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $request->validate([
            "name"          => "required",
            "pass"          => "required|between:4,24",
            "mob"           => "required|numeric",
            "mail"          => ["required", "email",  Rule::unique('users', "USER_MAIL")->ignore($user->USER_MAIL, "USER_MAIL"),],
            "gender"        => "required|exists:gender,id",
            "area"          => "required|exists:areas,id"
        ]);

        $user->USER_NAME = $request->name;
        if (isset($request->pass))
            $user->USER_PASS = $request->pass;
        $user->USER_MOBN = $request->mob;
        $user->USER_MOBN_VRFD = $request->isMobVerified;
        $user->USER_MAIL = $request->mail;
        $user->USER_MAIL_VRFD = $request->isMailVerified;
        $user->USER_GNDR_ID = $request->gender;
        $user->USER_AREA_ID = $request->area;

        $user->save();


        return redirect($this->homeURL);
    }
}
