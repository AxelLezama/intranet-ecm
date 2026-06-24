<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 p-6 bg-gray-50">

        <!-- Tarjeta 1: Usuarios Totales -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-4 rounded-xl bg-blue-50 text-blue-600 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>

            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Usuarios Totales</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">1</h3>

            </div>
        </div>

        <!-- Tarjeta 2: Ingresos -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-4 rounded-xl bg-green-50 text-green-600 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Documentos</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">5</h3>

            </div>
        </div>

    </div>

</x-app-layout>
