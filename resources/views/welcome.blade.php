<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans">
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">

        <div class="relative bg-cover bg-center bg-no-repeat min-h-screen w-full flex flex-col justify-between items-center selection:bg-[#818181] bg-gray-50 text-black/50 dark:bg-black dark:text-white/50"
            style="background-image: url('{{ asset('img/fondowelcome.png') }}');">

            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#818181]">
    
                <div class="flex lg:justify-center lg:col-start-2">
                    <img src="{{ asset('img/logo_HDLM_White_Horizontal_Registro.png') }}" alt="Logo HDLM">
                </div>
    
                <div class="flex">
                    @if (Route::has('login'))
                        <livewire:welcome.navigation />
                    @endif
                </div>
                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                    Intranet-ECM Hacienda De Los Morales by Axel Lezama
                </footer>
    
            </div>
        </div>

    </div>
</body>

</html>
