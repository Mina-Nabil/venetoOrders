<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ColorsController extends Controller
{
    protected $data;
    protected $homeURL = 'colors/show';

    private function initDataArr()
    {
        $this->data['items'] = Color::all();
        $this->data['title'] = "Available Colors";
        $this->data['subTitle'] = "Manage all Available Colors";
        $this->data['cols'] = ['Color', 'Arabic Name', 'Code', 'Edit'];
        $this->data['atts'] = [ 'COLR_NAME', 'COLR_ARBC_NAME', 'COLR_CODE',
            ['edit' => ['url' => 'colors/edit/', 'att' => 'id']],
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    public function __construct(){
        $this->middleware("auth");
    }

    public function home(){
        $this->initDataArr();
        $this->data['formTitle'] = "Add Color";
        $this->data['formURL'] = "colors/insert";
        $this->data['isCancel'] = false;
        return view('settings.colors', $this->data);
    }

    public function edit($id){
        $this->initDataArr();
        $this->data['color'] = Color::findOrFail($id);
        $this->data['formTitle'] = "Edit Color ( " . $this->data['color']->COLR_NAME . " )";
        $this->data['formURL'] = "colors/update";
        $this->data['isCancel'] = false;
        return view('settings.colors', $this->data);
    }

    public function insert(Request $request){

        $request->validate([
            "name"      => "required|unique:colors,COLR_NAME",
            "arbcName"  => "required",
            "code"      => "required",
        ]);

        $color = new Color();
        $color->COLR_NAME = $request->name;
        $color->COLR_ARBC_NAME = $request->arbcName;
        $color->COLR_CODE = $request->code;
        $color->save();
        return redirect($this->homeURL);
    }

    public function update(Request $request){
        $request->validate([
            "id" => "required",
        ]);
        $color = Color::findOrFail($request->id);

        $request->validate([
            "name" => ["required",  Rule::unique('colors', "COLR_NAME")->ignore($color->COLR_NAME, "COLR_NAME"),],
            "arbcName"  => "required",
            "code"      => "required",
            "id"        => "required",
        ]);

        $color->COLR_NAME = $request->name;
        $color->COLR_ARBC_NAME = $request->arbcName;
        $color->COLR_CODE = $request->code;
        $color->save();

        return redirect($this->homeURL);
    }
}
