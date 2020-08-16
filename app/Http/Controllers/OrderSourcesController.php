<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\OrderSource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderSourcesController extends Controller
{
    protected $data;
    protected $homeURL = 'sources/show';

    private function initDataArr()
    {
        $this->data['items'] = OrderSource::all();
        $this->data['title'] = "Orders Sources";
        $this->data['subTitle'] = "Manage all Order Sources and the respective clients accounts";
        $this->data['cols'] = ['Source Name', 'Client Account', 'Edit'];
        $this->data['atts'] = [
            'ORSC_NAME', 
            ['foreign' => ['client_account', 'CLNT_NAME']],
          
            ['edit' => ['url' => 'sources/edit/', 'att' => 'id']],
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function home()
    {
        $this->initDataArr();
        $this->data['clients'] = Client::all();
        $this->data['formTitle'] = "Add Orders Source";
        $this->data['formURL'] = "sources/insert";
        $this->data['isCancel'] = false;
        return view('settings.source', $this->data);
    }

    public function edit($id)
    {
        $this->initDataArr();
        $this->data['clients'] = Client::all();
        $this->data['source'] = OrderSource::findOrFail($id);
        $this->data['formTitle'] = "Edit Order Source ( " . $this->data['source']->ORSC_NAME . " )";
        $this->data['formURL'] = "sources/update";
        $this->data['isCancel'] = false;
        return view('settings.source', $this->data);
    }

    public function insert(Request $request)
    {

        $request->validate([
            "name"      => "required|unique:order_sources,ORSC_NAME",
            "client"  => "required",

        ]);

        $source = new OrderSource();
        $source->ORSC_NAME = $request->name;
        $source->ORSC_CLNT_ID = $request->client;
        $source->save();
        return redirect($this->homeURL);
    }

    public function update(Request $request)
    {
        $request->validate([
            "id" => "required",
        ]);
        $source = OrderSource::findOrFail($request->id);

        $request->validate([
            "name" => ["required",  Rule::unique('order_sources', "ORSC_NAME")->ignore($source->ORSC_NAME, "ORSC_NAME"),],
            "client"  => "required",
        ]);

        $source->ORSC_NAME = $request->name;
        $source->ORSC_CLNT_ID = $request->client;
        $source->save();

        return redirect($this->homeURL);
    }
}
