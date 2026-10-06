<?php
namespace App\Livewire\Auth;

use App\Livewire\Concerns\WithToast;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AuthForm extends Component
{
    use WithToast;
    public string $mode = 'login';
    public  string $name = '';
    public  string $mobile_login = '' ;
    public  string $mobile_register = '' ;
    public  string $passwordLogin = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $error = '';
    public array $data = [];


    public function login(){
       $this->validate([
        'mobile_login'  => ['required','min:11','regex:/^09[0-9]{9}$/'],
        'passwordLogin' => 'required|min:6|max:12',
        ],[ 'mobile_login.regex' => 'شماره موبایل باید با ۰۹ شروع شود و ۱۱ رقم باشد.'
    ]);
        $this->data = [
            'mobile' => $this->mobile_login,
            'password' => $this->passwordLogin
        ];
        $response  = Http::post('https://api.elbargroup.ir/api/auth/login',[
            'mobile' => $this->data['mobile'],
            'password' => $this->data['password']
        ]);
        $result = $response->json();
        if($response->successful() && ($result['success'] ?? false)){

            session(['api_token' => $result['data']['token']]);
            session(['api_user' => $result['data']['user']]);

            return redirect()->route('comments')->with('toast', [
                'type' => 'success',
                'title' => ' تایید شد ',
                'message' => ' شما با موفقیت وارد شدید  ',
                'duration' => 7000,
              ]);
        }else {
          $this->error = $result['message'] ?? 'ورود با خطا مواجه شد. لطفاً دوباره تلاش کنید.';
          $this->toastError('ورود با خطا مواجه شد. لطفاً دوباره تلاش کنید.', title: 'خطا', duration: 8000);
         }
    }
    public function register(){
        $this->validate([
           'name'                  => 'required|min:2|max:50',
           'mobile_register'       => ['required','regex:/^09[0-9]{9}$/','min:11'],
           'password'              => 'required|min:6|confirmed',
         ],['mobile_register.regex' => 'شماره موبایل باید با ۰۹ شروع شود و ۱۱ رقم باشد.',
    ]);
        try{
            $response = Http::post('https://api.elbargroup.ir/api/auth/register',[
            'name' => $this->name ,
            'mobile' => $this->mobile_register,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation  ]);

             $result = $response->json();

             if ($response->successful() && ($result['success'] ?? false )){

             session(['api_token' => $result['data']['token']]);
             session(['api_user' => $result['data']['user']]);

              session()->flash('success', $result['message'] ?? 'ثبت‌نام با موفقیت انجام شد');
              return redirect()->route('comments')->with('toast', [
                'type' => 'success',
                'title' => 'ثبت نام شدید',
                'message' => ' ثبت نام و ورود شما به سایت موفقیت آمیز بود ',
                'duration' => 7000,
              ]);
            }

            if(isset($result['errors'])) {
            foreach ($result['errors'] as $field => $messages) {
                $this->addError($field === 'mobile' ? 'mobile_register' : $field, $messages[0]);
            }
            } else {
            $this->error = $result['message'] ?? 'ثبت‌نام با خطا مواجه شد. لطفاً دوباره تلاش کنید.';
            }

        }catch(\Exception $e){
         $this->error = 'خطا در ارتباط با سرور. لطفاً چند لحظه دیگر تلاش کنید.';
        }
    }

    public function switchMode(string $mode)
    {
    $this->mode = $mode;
    $this->resetValidation();
    $this->reset(['name', 'mobile_register', 'password','passwordLogin','mobile_login', 'password_confirmation','error']);
    }
    public function render()
    {
        return view('livewire.auth.auth-form');
    }
}
