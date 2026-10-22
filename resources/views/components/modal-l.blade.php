@props(['show' => false, 'title', 'closeAction'])

@if($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/50 p-3 sm:p-4">

        <div class="my-auto max-h-[calc(100vh-1.5rem)] w-full max-w-md overflow-y-auto rounded bg-white p-4 shadow-lg sm:max-h-[calc(100vh-2rem)] sm:p-6">

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
