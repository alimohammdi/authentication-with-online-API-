<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>ورود و ثبت‌نام</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700&display=swap">
<link rel="stylesheet" href="{{  asset('css/style.css') }}">
@livewireStyles()
</head>
<x-toast />
<body class="auth">


{{  $slot }}


@livewireScripts()
</body>
</html>
