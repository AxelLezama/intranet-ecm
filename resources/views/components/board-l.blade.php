@props(['show' => false, 'title', 'closeAction'])

<div class="p-4 sm:p-6 bg-white rounded-lg shadow border border-gray-200">

    <!-- Header -->
    <div class="flex flex-col items-start gap-3 mb-4 sm:flex-row sm:items-center sm:justify-between">

        <!-- Titulo -->
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">
            {{ $title }}
        </h1>

        <!-- Acciones (botones, etc.) -->
        <div>
            {{ $actions ?? '' }}
        </div>

    </div>

    <!-- Contenido -->
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>

</div>
