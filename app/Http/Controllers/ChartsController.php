<?php

namespace App\Http\Controllers;

use App\Models\SizeChart;
use Illuminate\Http\Request;

class ChartsController extends Controller
{
    protected $data;
    protected $homeURL = 'charts/show';

    private function initDataArr()
    {
        $this->data['items'] = SizeChart::all();
        $this->data['title'] = "Size Chart Images";
        $this->data['subTitle'] = "Manage all Size Charts";
        $this->data['cols'] = ['Size Chart Name', 'URL', 'Edit'];
        $this->data['atts'] = [ 'SZCT_NAME', 
        ['remoteURL' => ['att' => "SZCT_URL"]], 
        ['edit' => ['url' => 'areas/edit/', 'att' => 'id']],
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    public function __construct(){
        $this->middleware("auth");
    }

    public function home(){
        $this->initDataArr();
        $this->data['formTitle'] = "Add Size Chart";
        $this->data['formURL'] = "charts/insert";
        $this->data['isCancel'] = false;
        return view('settings.charts', $this->data);
    }

    public function edit($id){
        $this->initDataArr();
        $this->data['chart'] = SizeChart::findOrFail($id);
        $this->data['formTitle'] = "Edit Chart ( " . $this->data['chart']->SZCT_NAME . " )";
        $this->data['formURL'] = "charts/update";
        $this->data['isCancel'] = false;
        return view('settings.charts', $this->data);
    }

    public function insert(Request $request){

        $request->validate([
            "name"      => "required",
            "uploadedImage"      => "required|active_url",
        ]);

        $chart = new SizeChart();
        $chart->SZCT_NAME = $request->name;
        $chart->SZCT_URL = $request->uploadedImage;
        $chart->save();
        return redirect($this->homeURL);
    }

    public function update(Request $request){
        $request->validate([
            "name"                  => "required",
            "uploadedImage"         => "nullable|active_url",
            "id"                    => "required",
        ]);

        $chart = SizeChart::findOrFail($request->id);
        $chart->SZCT_NAME = $request->name;
        if(isset($request->uploadedImage) && strlen($request->uploadedImage) > 0)
        $chart->SZCT_URL = $request->uploadedImage;

        $chart->save();

        return redirect($this->homeURL);
    }
}
