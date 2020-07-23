<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Johntaa\Arabic\I18N_Arabic;

class TagsController extends Controller
{
    protected $data;
    protected $homeURL = 'tags/show';

    private function initDataArr()
    {
        $this->data['items'] = Tags::all();
        $this->data['title'] = "Search Tags";
        $this->data['subTitle'] = "Manage all Tags used to improve search results";
        $this->data['cols'] = ['Ta', 'Arabic Name', 'Edit'];
        $this->data['atts'] = [ 'TAGS_NAME', 'TAGS_ARBC_NAME',
            ['edit' => ['url' => 'tags/edit/', 'att' => 'id']],
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    public function __construct(){
        $this->middleware("auth");
    }

    public function home(){
        $this->initDataArr();
        $this->data['formTitle'] = "Add Tags";
        $this->data['formURL'] = "tags/insert";
        $this->data['isCancel'] = false;
        return view('settings.tags', $this->data);
    }

    public function edit($id){
        $this->initDataArr();
        $this->data['tag'] = Tags::findOrFail($id);
        $this->data['formTitle'] = "Edit Tags ( " . $this->data['tag']->TAGS_NAME . " )";
        $this->data['formURL'] = "tags/update";
        $this->data['isCancel'] = false;
        return view('settings.tags', $this->data);
    }

    public function insert(Request $request){

        $arbcSoundex = new I18N_Arabic('Soundex');

        $request->validate([
            "name"          => "required|unique:tags,TAGS_NAME",
            "arbcName"      => "required",
        ]);

        $tag = new Tags();
        $tag->TAGS_NAME = $request->name;
        $tag->TAGS_ARBC_NAME = $request->arbcName;
        $tag->TAGS_SNDX = soundex($request->name);
        $tag->TAGS_ARBC_SNDX = $arbcSoundex->soundex($request->arbcName);
        $tag->save();

        return redirect($this->homeURL);
    }

    public function update(Request $request){
        $request->validate([
            "id" => "required",
        ]);
        $tag = Tags::findOrFail($request->id);

        $request->validate([
            "name" => ["required",  Rule::unique('tags', "TAGS_NAME")->ignore($tag->TAGS_NAME, "TAGS_NAME"),],
            "arbcName"      => "required",
            "id"        => "required",
        ]);

 
        $arbcSoundex = new I18N_Arabic('Soundex');

        $tag->TAGS_NAME = $request->name;
        $tag->TAGS_ARBC_NAME = $request->arbcName;
        $tag->TAGS_SNDX = soundex($request->name);
        $tag->TAGS_ARBC_SNDX = $arbcSoundex->soundex($request->arbcName);

        $tag->save();

        return redirect($this->homeURL);
    }
}
