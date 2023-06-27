<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;


class EmailTemplateController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:email-template-list|email-template-edit|email-template-delete', ['only' => ['index']]);
        $this->middleware('permission:email-template-create', ['only' => ['create','store']]);
        $this->middleware('permission:email-template-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:email-template-delete', ['only' => ['destroy']]);
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
        $data = $this->EmailTemplateObj->getEmailTemplates($posted_data);
        return view('email_template.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $posted_data = array();
        $data['short_codes'] = $this->EmailShortCodeObj->getEmailShortCode($posted_data);
        // echo '<pre>';print_r($data);echo '</pre>';exit;
        return view('email_template.add',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request_data = $request->all();
        // echo '<pre>';print_r($request_data);'</pre>';exit;
        $rules = array(
            'subject' => 'required',
            'send_on' => 'required',
            'message' => 'required'
        );

        $validator = \Validator::make($request_data, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {            
            $email_send_on_detail = $this->EmailTemplateObj->getEmailTemplates([
                'detail' => true,
                'send_on' => $request_data['send_on']
            ]);

            if ($email_send_on_detail && $email_send_on_detail->send_on == $request_data['send_on']) {
                \Session::flash('error_message', '"'.$request_data['send_on'].'" Email template already exists, if you want to update than go and edit in email template!');
                return redirect()->back()->withInput();
            }else{
                \Session::flash('message', 'Email Template created successfully!');
                $this->EmailTemplateObj->saveUpdateEmailTemplates($request_data);
                return redirect('/email_template');
            }
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
       
        // $data['short_codes'] = ShortCode::getEmailShortCode($posted_data);
        $data = $this->EmailTemplateObj->getEmailTemplates($posted_data);
        $data['short_codes'] = $this->EmailShortCodeObj::all();
        // echo '<pre>';print_r($data);echo '</pre>';exit;
        return view('email_template.add', compact('data'));
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
            'subject' => 'required',
            'send_on' => 'required',
            'message' => 'required'
        );

        $validator = \Validator::make($request_data, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $email_message_detail = $this->EmailTemplateObj->getEmailTemplates([
            'detail' =>true,
            'id' =>$request_data['update_id']
        ]);

        $email_send_on_detail = $this->EmailTemplateObj->getEmailTemplates([
            'detail' =>true,
            'send_on' =>$request_data['send_on']
        ]);
        if ($email_message_detail->send_on != $request_data['send_on'] && $email_send_on_detail) {
            \Session::flash('error_message', '"'.$request_data['send_on'].'" Email template already exists, if you want to update than go and edit in email template!');
        }else{
            \Session::flash('message', 'Email Template updated successfully!');
            $this->EmailTemplateObj->saveUpdateEmailTemplates($request_data);
        }
        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailTemplate $email_template)
    {
        $email_template->delete(); 
        \Session::flash('message', 'Email Template deleted successfully!');
        return redirect('/email_template');
    }
}