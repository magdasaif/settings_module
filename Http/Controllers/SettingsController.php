<?php

namespace Modules\Settings\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Settings\Entities\Setting;
use Modules\Settings\Http\Traits\MediaTrait;
use Modules\Settings\Http\Traits\SettingTrait;
use Modules\Settings\Http\Traits\SendMailProcess\MailConfigrationTrait;

class SettingsController extends Controller
{
    use MediaTrait,SettingTrait,MailConfigrationTrait;
    //==================================================================================================
    private $setting;
    //==================================================================================================
    public function __construct(){
        $this->middleware('auth');
        $this->setting = setting::first();
    }
    //==================================================================================================
    public function index(){
        return view('settings::index');
    }
    //==================================================================================================
    public function create(){
        return view('settings::create');
    }
    //==================================================================================================
    public function store(Request $request){
        //
    }
    //==================================================================================================
    public function show($id){
        return view('settings::show');
    }
    //==================================================================================================
    public function edit($id){
        dd('inside published controller');
        // return view('settings::edit');
        return view('settings::settings.edit', ['setting' => $this->setting]);
    }
    //==================================================================================================
    // public function update(Request $request, $id){
    public function update(Request $request){
        DB::beginTransaction();
        try{
            // dd($request->all());
            //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            $this->setting->update($request->input());
            //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            #---mailConfiguration---#
            $mailConfiguration=$this->prepare_mail_configration_request($request);
            //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            #---social---#
            $social =$this->prepare_social_request($request);
            //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            $exception_array=['mail_mailer','mail_host','mail_port','mail_username','mail_password','mail_encryption','mail_from_address','mail_from_name','facebook', 'twitter', 'instagram'];
            $requestData = $request->except($exception_array);
            //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            #---update---#
            $this->setting->update($requestData);
            $this->setting->mail_configuration = $mailConfiguration;
            $this->setting->social = $social;
            $this->setting->save();
            //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            if ($request->hasFile('logo')) {
                //****************************************************************************************** */
                // $this->UpdateMediaWithMediaLibrary($request,'logo',$request->logo,'App\Models\Setting',1,'logo');
                $this->UpdateMediaWithMediaLibrary($request,'logo',$request->logo,'Modules\Setting\Entities\Setting',1,'logo');
                //****************************************************************************************** */
            }
            //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            #----SetMailConfigurationFromSettingInConfig---#
            $SetMailConfigurationFromSettingInConfig = $this->updateMailConfigurationFromSetting();
            //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            #---response---#  
            DB::commit();      
            return redirect()->route('settings::settings.edit')->with('success', 'تم تحديث الاعدادات بنجاح');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('settings::settings.edit')->with('errors',$e->getMessage());
        }
    }
    // }
    //==================================================================================================
    public function destroy($id){
        //
    }
    //==================================================================================================
}
