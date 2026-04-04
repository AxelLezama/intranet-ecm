<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}

    @if ($isModalOpen)
        <div>
            <div>
                <h2>
                    {{ $isEditing ? 'Editar Departamento' : 'Crear Departamento' }}
                </h2>

                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}"></form>
            </div>
        </div>
    @endif
</div>
