<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductsController extends Controller
{

    protected $data;
    protected $homeURL = "products/show/all";
    protected $detailsURL = "products/details/";

    private function initTableArr()
    {
        $this->data['items'] = Product::all();
        $this->data['title'] = "All Models";
        $this->data['subTitle'] = "Showing all Models";
        $this->data['cols'] = ['Barcode', 'Model Title', 'Arabic Title', 'Price', 'Cost', 'Offer', 'Edit'];
        $this->data['atts'] = [ 'PROD_BRCD', 
        ['attUrl' => ['url' => 'products/details', 'urlAtt' => "id", "shownAtt" => "PROD_NAME"]], 
        ['attUrl' => ['url' => 'products/details', 'urlAtt' => "id", "shownAtt" => "PROD_ARBC_NAME"]], 
        'PROD_PRCE', 
        'PROD_COST',
        'PROD_OFFR',
        ['edit' => ['url' => 'products/edit/', 'att' => 'id']],
        ];
        $this->data['homeURL'] = $this->homeURL;
    }

    private function initAddArr($prodID = -1)
    {
        if ($prodID != -1) {
            $this->data['product'] = Product::findOrFail($prodID);
            $this->data['formURL'] = "products/update";
        } else {
            $this->data['formURL'] = "products/insert/";
        }
        $this->data['categories'] = SubCategory::with('category')->get();
        $this->data['formTitle'] = "Add New Model";
        $this->data['isCancel'] = true;
        $this->data['homeURL'] = $this->homeURL;
    }

    public function __construct(){
        $this->middleware('auth');
    }

    public function home(){
        $this->initTableArr();
        return view("products.table", $this->data);
    }

    public function add(){
        $this->initAddArr();
        return view("products.add", $this->data);
    }

    public function edit($prodID){
        $this->initAddArr($prodID);
        return view("products.add", $this->data);
    }

    public function details($prodID){
        $this->data['product'] = Product::findOrFail($prodID);
        return view("products.details", $this->data);
    }

    public function insert(Request $request){
        $request->validate([
            "name" => "required|unique:products,PROD_NAME",
            "arbcName" => "required",
            "desc" => "required",
            "arbcDesc" => "required",
            "category" => "required|exists:sub_categories,id",
            "price" => "required|numeric",
            "cost" => "nullable|numeric",
        ]);

        $product = new Product();

        $product->PROD_NAME = $request->name;
        $product->PROD_ARBC_NAME = $request->arbcName;
        $product->PROD_DESC = $request->desc;
        $product->PROD_ARBC_DESC = $request->arbcDesc;
        $product->PROD_SBCT_ID = $request->category;
        $product->PROD_PRCE = $request->price;
        $product->PROD_BRCD = $request->barCode;
        $product->PROD_COST = $request->cost;
        if(isset($product->offer))
        $product->PROD_OFFR = $request->offer;

        $product->save();
        return redirect('products/details/' . $product->id);
    }

    public function update(Request $request)
    {
        $request->validate([
            "id"          => "required",
        ]);
        $product = Product::findOrFail($request->id);
        $request->validate([
            "name"          => ["required",  Rule::unique('products', "PROD_NAME")->ignore($product->PROD_NAME, "PROD_NAME"),],
            "arbcName" => "required",
            "category" => "required|exists:sub_categories,id",
            "price" => "required|numeric",
            "cost" => "nullable|numeric",
        ]);

        $product->PROD_NAME = $request->name;
        $product->PROD_ARBC_NAME = $request->arbcName;
        $product->PROD_DESC = $request->desc;
        $product->PROD_ARBC_DESC = $request->arbcDesc;
        $product->PROD_SBCT_ID = $request->category;
        $product->PROD_PRCE = $request->price;
        $product->PROD_BRCD = $request->barCode;
        $product->PROD_COST = $request->cost;
        if(isset($product->offer))
        $product->PROD_OFFR = $request->offer;

        $product->save();
        return redirect('products/details/' . $product->id);
    }


}
