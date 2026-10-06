<div class="layout">
  {{-- پروفایل کاربر --}}
  <aside class="card profile" aria-label="پروفایل">
    <div class="av big">{{ mb_substr(auth()->user()->name ?? '؟', 0, 1) }}</div>

    <dl class="info">
      <div>
        <dt>نام و نام خانوادگی</dt>
        <dd> {{  $currentUser['name'] }}  </dd>
      </div>
      <div>
        <dt>شماره تماس</dt>
        <dd><bdi dir="ltr">{{  $currentUser['mobile'] }}</bdi></dd>
      </div>
    </dl>
      <button class="out" wire:click="logout" type="button" wire:loading.attr="disabled">خروج از حساب</button>
  </aside>

<main class="card">

  <h1>نظرات کاربران</h1>
  <p class="sub">نظر خود را بنویسید یا به نظر دیگران پاسخ بدهید.</p>

  <!-- فرم ثبت نظر -->
  <form  wire:submit="storeComment">
    <div class="f">
      <label for="m-text">متن نظر</label>
      <textarea id="m-text" name="comment" wire:model="content" placeholder="نظر خود را اینجا بنویسید..."  ></textarea>
    @error('content')
            <span style="color: red; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
    </div>
    <input  class="go" type="submit"  value="ثبت نظر">
  </form>

  <h2>{{  count($comments) }} نظر</h2>
<section class="list">
    @forelse($comments as $comment)
        <article class="c">
            <div class="av">{{ mb_substr($comment['user']['name'] ?? '؟', 0, 1) }}</div>
            <div class="body">
                <div class="head">
                    <span class="name">{{ $comment['user']['name'] ?? 'کاربر' }}</span>
                    <span class="time">{{ $comment['created_at'] ?? '' }}</span>
                </div>
                <p class="text">{{ $comment['content'] }}</p>
                <details class="reply" @if($replyingTo === $comment['id']) open @endif>
                <summary wire:click="$set('replyingTo', {{ $comment['id'] }})">پاسخ</summary>

                @if($replyingTo === $comment['id'])
               <form class="rform" wire:submit="storeReply">
                <textarea
                    wire:model="replyContent"
                    placeholder="پاسخ شما..."
                    required>
                </textarea>

            @error('replyContent')
                <span style="color: red; font-size: 0.875rem;">{{ $message }}</span>
            @enderror

            <div style="margin-top: 0.5rem;">
                <button class="go sm" type="submit" wire:loading.attr="disabled">
                    <span wire:loading.remove>ثبت پاسخ</span>
                    <span wire:loading>در حال ارسال...</span>
                </button>

                <button type="button" class="go sm" style="background: #999;"
                        wire:click="$set('replyingTo', null)">
                    انصراف
                </button>
                          </div>
                      </form>
                  @endif
              </details>
                @if(!empty($comment['replies']))
                    <div class="replies">
                        @foreach($comment['replies'] as $reply)
                            <article class="c">
                                <div class="av">{{ mb_substr($reply['user']['name'] ?? '؟', 0, 1) }}</div>
                                <div class="body">
                                    <div class="head">
                                        <span class="name">{{ $reply['user']['name'] ?? 'کاربر' }}</span>
                                        <span class="time">{{ $reply['created_at'] ?? '' }}</span>
                                    </div>
                                    <p class="text">{{ $reply['content'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </article>
    @empty
        <p>هنوز نظری ثبت نشده است.</p>
    @endforelse
</section>
</main>
</div>
