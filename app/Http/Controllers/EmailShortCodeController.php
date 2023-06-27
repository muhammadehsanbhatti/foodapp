<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortCode;


class EmailShortCodeController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:shortcode-list|shortcode-edit|shortcode-delete', ['only' => ['index']]);
        $this->middleware('permission:shortcode-create', ['only' => ['create','store']]);
        $this->middleware('permission:shortcode-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:shortcode-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $posted_data = array();
        $posted_data['paginate'] = 10;
        $data = $this->EmailShortCodeObj->getEmailShortCode($posted_data);

        return view('email_shortcode.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('email_shortcode.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'title' => 'required'
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $posted_data = $request->all();
            // echo '<pre>';print_r($posted_data);echo '</pre>';exit;
            $this->EmailShortCodeObj->saveUpdateEmailShortCode($posted_data);

            \Session::flash('message', 'ShortCode created successfully!');
            return redirect('/short_codes');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $posted_data = array();
        $posted_data['id'] = $id;
        $posted_data['detail'] = true;

        $data = $this->EmailShortCodeObj->getEmailShortCode($posted_data);
        return view('email_shortcode.add', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request_data = $request->all();
        $request_data['update_id'] = $id;

        $rules = array(
            'title' => 'required',
        );

        $validator = \Validator::make($request_data, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->EmailShortCodeObj->saveUpdateEmailShortCode($request_data);

        \Session::flash('message', 'Short Code updated successfully!');
        // return redirect('/enquiry_forms');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShortCode $ShortCode)
    {
        $ShortCode->delete(); 
        \Session::flash('message', 'Short Code deleted successfully!');
        return redirect('/short_codes');
    }
}