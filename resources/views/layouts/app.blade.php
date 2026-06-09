<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title')</title> {{-- @yield：各ページから差し込まれる場所 --}}
	@vite(['resources/css/app.css', 'resources/js/app.js'])	{{-- vite読み込み --}}
</head>
<body class="p-8">
	<div class="max-w-2xl mx-auto">
        @yield('content')
    </div>
</body>
</html>
