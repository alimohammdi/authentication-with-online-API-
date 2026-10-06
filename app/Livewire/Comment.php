<?php

namespace App\Livewire;

use App\Livewire\Concerns\WithToast;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Comment extends Component
{
    use WithToast;
    public $comments = [];
    public $content = '';
    public $replyContent = '';
    public $replyingTo = null;
    public $error = '';
    public $currentUser = null ;


    public function mount(){
        if (!session('api_token') || !session('api_user')) {
        return redirect('/login');
         }
        $this->currentUser = session('api_user');
        $this->getComments();
    }

    public function getComments (){

     try{
        $response = Http::withToken(session('api_token'))
    ->get('https://api.elbargroup.ir/api/comments');

    if ($response->successful()) {
            $result = $response->json();
            $this->comments = $result['data'] ?? [];

        } else {
            $this->error = 'خطا در دریافت نظرات';
            $this->toastError('خطا در دریافت نظرات', title: 'خطا', duration: 8000);
        }

     }catch(\Exception $e){
      $this->error = 'خطا در ارتباط با سرور';
     }
    }

    public function storeComment(){
        $this->validate([
            'content' => 'required|min:3|max:100'
        ]);
       try{
        $response  = Http::withToken(session('api_token'))->post('https://api.elbargroup.ir/api/comments',
        ['content' => $this->content]);
        if ($response->successful()) {
            $this->content = '';
             $this->toastSuccess(' نظر شما ثبت شد ');
            $this->getComments();
        } else {
            $this->error = 'خطا در ثبت نظر';
        }
    } catch (\Exception $e) {
        $this->error = 'خطا در ارتباط با سرور';
    }
    }



    public function storeReply() {
    $this->validate([
        'replyContent' => 'required|min:3|max:1000',
        'replyingTo'   => 'required|integer',
    ]);

    try {
        $response = Http::withToken(session('api_token'))
            ->post('https://api.elbargroup.ir/api/comments', [
                'content'   => $this->replyContent,
                'parent_id' => $this->replyingTo,
            ]);

        if ($response->successful()) {
            $this->replyContent = '';
            $this->replyingTo = null;
            $this->toastSuccess(' نظر شما ثبت شد ');
            $this->getComments();
        } else {
            $this->error = 'خطا در ثبت پاسخ';
        }
    } catch (\Exception $e) {
        $this->error = 'خطا در ارتباط با سرور';
    }
    }

    public function logout()
     {
    session()->forget(['api_token', 'api_user']);
    return redirect('/login');
    }

    public function render()
    {
        return view('livewire.comment');
    }
}
