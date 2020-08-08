<?php

namespace App\Http\Controllers;

use App\Models\Icon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IconsController extends Controller
{
    
    protected $data;
    protected $homeURL = 'icons/show';

    private function initDataArr()
    {
        $this->data['items'] = Icon::all();
        $this->data['title'] = "Available Icons";
        $this->data['subTitle'] = "Manage all Available Icons";
        $this->data['cols'] = ['Image', 'Name', 'Edit'];
        $this->data['atts'] = [ 
            ['assetImg' => ['filename' => 'ICON_PATH', 'assetPath' => 'storage/icons']],
            'ICON_NAME',
            ['edit' => ['url' => 'icons/edit/', 'att' => 'id']],
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    public function __construct(){
        $this->middleware("auth");
    }

    public function home(){
        $this->initDataArr();
        $this->data['formTitle'] = "Add Icon";
        $this->data['formURL'] = "icons/insert";
        $this->data['isCancel'] = false;
        return view('settings.icons', $this->data);
    }

    public function edit($id){
        $this->initDataArr();
        $this->data['icon'] = Icon::findOrFail($id);
        $this->data['formTitle'] = "Edit Icon ( " . $this->data['icon']->ICON_NAME . " )";
        $this->data['formURL'] = "icons/update";
        $this->data['isCancel'] = false;
        return view('settings.icons', $this->data);
    }

    public function insert(Request $request){

        $request->validate([
            "name"      => "required|unique:icons,ICON_NAME",
            "photo"      => "required",
        ]);

        $icon = new Icon();
        $icon->ICON_NAME = $request->name;
        if ($request->hasFile('photo')) {
            $path = $request->photo->store('images/icons', 'public');
            $icon->ICON_PATH = $path;
        }
        $icon->save();
        return redirect($this->homeURL);
    }

    public function update(Request $request){
        $request->validate([
            "id" => "required",
        ]);
        $icon = Icon::findOrFail($request->id);

        $request->validate([
            "name" => ["required",  Rule::unique('icons', "ICON_NAME")->ignore($icon->ICON_NAME, "ICON_NAME"),],
        ]);

        $icon->ICON_NAME = $request->name;
        if ($request->hasFile('photo')) {
            $path = $request->photo->store('images/icons', 'public');
            $icon->ICON_PATH = $path;
        }
        $icon->save();

        return redirect($this->homeURL);
    }
}
