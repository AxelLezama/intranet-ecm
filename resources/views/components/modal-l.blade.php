@props(['show' => false, 'title', 'closeAction'])

@if($show)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">
                    {{ $title }}
                </h2>

                <button wire:click="{{ $closeAction }}" class="text-gray-500 text-sm uppercase">
                    Cerrar
                </button>
            </div>

            <!-- Body -->
            {{ $slot }}

        </div>

    </div>
@endif