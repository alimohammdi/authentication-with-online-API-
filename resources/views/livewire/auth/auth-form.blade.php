<main class="card">
 <div class="tabs">
  <button
    type="button"
    wire:click="switchMode('login')"
    class="{{ $mode === 'login' ? 'active' : '' }}">
    ورود
  </button>
  <button
    type="button"
    wire:click="switchMode('register')"
    class="{{ $mode === 'register' ? 'active' : '' }}">
    ثبت‌نام
  </button>

</div>
  <!-- فرم ورود -->

  @if($mode === 'login')
  <section id="login">
    <h1>ورود به حساب</h1>
    <p class="sub">شماره تماس و رمز عبور خود را وارد کنید.</p>

    <form wire:submit="login">
      <div class="f">
        <label for="l-phone">شماره تماس</label>
        <input id="phone" name="mobile"  wire:model="mobile_login"  class="ltr" type="tel" inputmode="numeric"   placeholder="09123456789">
      @error('mobile_login')
      <span class="text-red-500 text-sm" style="color: red">{{ $message }}</span>
      @enderror
    </div>
      <div class="f">
        <label for="l-pass">رمز ورود</label>
        <input id="l-pass" name="password" wire:model="passwordLogin" class="ltr" type="password" autocomplete="current-password"  >
      @error('passwordLogin')
      <span class="text-red-500 text-sm " style="color: red">{{ $message }}</span>
      @enderror
    </div>

      <button class="go" type="submit">ورود</button>
    </form>
    @if($error)
    <div class="alert alert-danger" style="color: red; margin-bottom: 1rem;">
        {{ $error }}
    </div>
    @endif
  </section>
  @endif
  <!-- فرم ثبت‌نام -->
  @if ($mode === 'register')
  <section id="signup">
    <h1>ساخت حساب جدید</h1>
    <p class="sub">اطلاعات زیر را برای ثبت‌نام تکمیل کنید.</p>

    <form wire:submit="register">
      <div class="f">
        <label for="s-name">نام و نام خانوادگی</label>
        <input id="s-name" wire:model="name"  name="fullname" type="text"
               placeholder="مثلاً علی رضایی"  >
      @error('name')
      <span class="text-red-500 text-sm" style="color: red">{{ $message }}</span>
      @enderror
            </div>
      <div class="f">
        <label for="s-phone">شماره تماس</label>
        <input id="s-phone" wire:model="mobile_register" name="phone" class="ltr" type="tel"
        placeholder="09123456789"     >
       @error('mobile_register')
      <span class="text-red-500 text-sm" style="color: red">{{ $message }}</span>
      @enderror
            </div>
      <div class="f">
        <label for="s-pass">رمز</label>
        <input id="s-pass" wire:model="password" name="password" class="ltr" type="password" autocomplete="new-password"
                 >
      @error('password')
      <span class="text-red-500 text-sm" style="color: red">{{ $message }}</span>
      @enderror
    </div>
      <div class="f">
        <label for="s-pass2">تایید رمز</label>
        <input id="s-pass2" wire:model="password_confirmation" name="password_confirm" class="ltr" type="password" autocomplete="new-password" >
      </div>
      <button class="go" type="submit">ایجاد حساب</button>
    </form>
    @if($error)
    <div class="alert alert-danger" style="color: red; margin-bottom: 1rem;">
        {{ $error }}
    </div>
    @endif
  </section>

  @endif

</main>
