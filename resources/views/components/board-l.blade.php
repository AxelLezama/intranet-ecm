@props(['show' => false, 'title', 'closeAction'])

<div class="p-6 bg-white rounded-lg shadow border border-gray-200">

    <!-- Header -->
    <div class="flex justify-between items-center mb-4">

        <!-- Titulo -->
        <h1 class="text-2xl font-bold text-gray-800">
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