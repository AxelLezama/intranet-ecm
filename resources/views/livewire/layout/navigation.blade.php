<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex items-center">

                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                {{-- ── Links desktop ─────────────────────────────── --}}
                <div class="hidden sm:flex sm:items-center sm:ms-10 space-x-1">

                    {{-- Empleado: ve sus documentos activos (cards) --}}
                    @if (auth()->user()->isEmployee())
                        <x-nav-link :href="route('documents.public')" :active="request()->routeIs('documents.public')" wire:navigate>
                            Documentos
                        </x-nav-link>
                    @endif

                    {{-- Supervisor / Committee / Admin: gestión (tabla) --}}
                    @if (auth()->user()->hasAnyRole(['supervisor', 'committee', 'admin']))
                        <x-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.index', 'documents.create', 'documents.versions')" wire:navigate>
                            Documentos
                        </x-nav-link>
                        {{-- Dashboard — todos los roles a exepcion de employees --}}
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                            Dashboard
                        </x-nav-link>
                    @endif

                    {{-- Admin: catálogos del sistema --}}
                    @if (auth()->user()->isAdmin())
                        <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')" wire:navigate>
                            Roles
                        </x-nav-link>

                        <x-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.index')" wire:navigate>
                            Departamentos
                        </x-nav-link>

                        <x-nav-link :href="route('documentTypes.index')" :active="request()->routeIs('documentTypes.index')" wire:navigate>
                            Tipos de doc.
                        </x-nav-link>

                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" wire:navigate>
                            Usuarios
                        </x-nav-link>
                    @endif

                </div>
            </div>

            {{-- ── Dropdown usuario ──────────────────────────────── --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent
                                       text-sm leading-4 font-medium rounded-md text-gray-500 bg-white
                                       hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name">
                            </div>
                            <svg class="ms-1 fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0
                                         111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- Info del usuario --}}
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs text-gray-400">Signed in as</p>
                            <p class="text-sm font-medium text-gray-700 truncate">
                                {{ auth()->user()->name }}
                            </p>
                            <span
                                class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full
                                         text-xs font-medium bg-indigo-50 text-indigo-700 capitalize">
                                {{ auth()->user()->role?->name ?? '—' }}
                            </span>
                        </div>

                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            Perfil
                        </x-dropdown-link>

                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                Cerrar sesión
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- ── Hamburger ─────────────────────────────────────── --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400
                               hover:text-gray-500 hover:bg-gray-100 focus:outline-none
                               focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- ── Responsive menu ───────────────────────────────────── --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                Dashboard
            </x-responsive-nav-link>

            @if (auth()->user()->isEmployee())
                <x-responsive-nav-link :href="route('documents.public')" :active="request()->routeIs('documents.public')" wire:navigate>
                    Documentos
                </x-responsive-nav-link>
            @endif

            @if (auth()->user()->hasAnyRole(['supervisor', 'committee', 'admin']))
                <x-responsive-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.index', 'documents.create', 'documents.versions')" wire:navigate>
                    Documentos
                </x-responsive-nav-link>
            @endif

            @if (auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')" wire:navigate>
                    Roles
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.index')" wire:navigate>
                    Departamentos
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('documentTypes.index')" :active="request()->routeIs('documentTypes.index')" wire:navigate>
                    Tipos de doc.
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" wire:navigate>
                    Usuarios
                </x-responsive-nav-link>
            @endif

        </div>

        {{-- Info usuario responsive --}}
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name">
                </div>
                <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                <span
                    class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full
                             text-xs font-medium bg-indigo-50 text-indigo-700 capitalize">
                    {{ auth()->user()->role?->name ?? '—' }}
                </span>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    Perfil
                </x-responsive-nav-link>

                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        Cerrar sesión
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
