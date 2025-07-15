<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Sistema de Administración')" class="grid">
                    <ul class="space-y-2 font-medium">
                        <!--<flux:button variant="ghost" class="py-7 flex w-full justify-start items-center gap-4" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                            <x-icon name="home" class="h-7 w-7" />
                            <span>{{ __('Dashboard') }}</span>
                        </flux:button>-->
                        
                        <a href="{{ route('dashboard') }}" 
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('dashboard') ? 'bg-gray-200 dark:bg-gray-700' : '' }}"
                            wire:navigate>
                            <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                                <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                                <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                            </svg>
                            <span class="ms-3">Dashboard</span>
                        </a>
                        
                        <!--<flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>-->

                        <!--<flux:button variant="ghost" class="py-7 flex w-full justify-start items-center gap-4" :href="route('proyectos')" :current="request()->routeIs('proyectos')" wire:navigate>
                            <x-icon name="folder" class="h-7 w-7" />
                            <span>{{ __('Proyectos') }}</span>
                        </flux:button>-->

                        <a href="{{route('proyectos')}}" 
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('proyectos') ? 'bg-gray-200 dark:bg-gray-700' : '' }}"
                            wire:navigate>
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                                <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                            </svg>
                            <span class="ms-3">Proyectos</span>
                        </a>

                        <!--<flux:navlist.item icon="folder" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Proyectos') }}</flux:navlist.item>-->

                        <div x-data="{ open: false }">
                            <button type="button" 
                                    @click="open = !open"
                                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
                                    <path d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
                                </svg>
                                <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Ventas</span>
                                <svg class="w-3 h-3 transition-transform duration-200" 
                                    :class="{ 'rotate-180': open }"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>
                            <ul x-show="open" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="py-2 space-y-2">
                                <livewire:Menu.menu-proyectos />
                            </ul>
                        </div>

                        <!--<flux:navlist.item icon="shopping-cart" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Ventas') }}</flux:navlist.item>-->

                        <!--<flux:button variant="ghost" class="py-7 flex w-full justify-start items-center gap-4" :href="route('usuarios')" :current="request()->routeIs('usuarios')" wire:navigate>
                            <x-icon name="user" class="h-7 w-7" />
                            <span>{{ __('Usuarios') }}</span>
                        </flux:button>-->

                        <a href="{{route('usuarios')}}" 
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('usuarios') ? 'bg-gray-200 dark:bg-gray-700' : '' }}"
                            wire:navigate>
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Usuarios</span>
                        </a>

                        <!--<flux:navlist.item icon="user" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Usuarios') }}</flux:navlist.item>-->
                        <!--<flux:dropdown variant="ghost" position="right" align="start" class="w-full" hover>
                            <flux:button variant="ghost" class="py-7 flex w-full justify-start items-center gap-4">
                                <x-icon name="wrench" class="h-7 w-7" />
                                <span>{{ __('Mantenimiento') }}</span>
                                <x-icon name="chevron-right" class="h-4 w-4 ml-auto" />
                            </flux:button>
                            <flux:menu class="w-48">
                                <flux:menu.item :href="route('tipo_venta')" wire:navigate>{{ __('Tipo de venta') }}</flux:menu.item>
                                <flux:menu.item :href="route('tipo_usuario')" wire:navigate>{{ __('Tipo de usuario') }}</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>-->

                        <div x-data="{ open: false }">
                            <button type="button" 
                                    @click="open = !open"
                                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
                                    <path d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
                                </svg>
                                <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Mantenimiento</span>
                                <svg class="w-3 h-3 transition-transform duration-200" 
                                    :class="{ 'rotate-180': open }"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>
                            <ul x-show="open" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="py-2 space-y-2">
                                <li>
                                    <a href="{{ route('tipo_usuario')}}" 
                                    wire:navigate 
                                    class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                        Tipo de Usuario
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </ul>

                    <!--<flux:navlist.item icon="wrench" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Mantenimiento') }}</flux:navlist.item>-->
                </flux:navlist.group>
            </flux:navlist>

            <!--<flux:dropdown>
                <flux:button icon-trailing="chevron-down">Options</flux:button>

                <flux:menu>
                    <flux:menu.item icon="plus">New post</flux:menu.item>

                    <flux:menu.separator />

                    <flux:menu.submenu heading="Sort by">
                        <flux:menu.radio.group>
                            <flux:menu.radio checked>Name</flux:menu.radio>
                            <flux:menu.radio>Date</flux:menu.radio>
                            <flux:menu.radio>Popularity</flux:menu.radio>
                        </flux:menu.radio.group>
                    </flux:menu.submenu>

                    <flux:menu.submenu heading="Filter">
                        <flux:menu.checkbox checked>Draft</flux:menu.checkbox>
                        <flux:menu.checkbox checked>Published</flux:menu.checkbox>
                        <flux:menu.checkbox>Archived</flux:menu.checkbox>
                    </flux:menu.submenu>

                    <flux:menu.separator />

                    <flux:menu.item variant="danger" icon="trash">Delete</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
            ventas:
            <flux:dropdown variant="ghost" position="right" align="start" class="w-full" hover>
                        <flux:button variant="ghost" class="py-7 flex w-full justify-start items-center gap-4">
                            <x-icon name="shopping-cart" class="h-7 w-7" />
                            <span>{{ __('Ventas') }}</span>
                            <x-icon name="chevron-right" class="h-4 w-4 ml-auto" />
                        </flux:button>
                            <flux:menu class="w-48">
                            
                        </flux:menu>
                    </flux:dropdown>
        
        
        -->

            <!--
            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Sistema de Administración')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>

                    Menú desplegable para Proyectos
                    <div class="px-3 py-1">
                        <flux:dropdown position="right" align="start" class="w-full" hover>
                            <flux:button variant="ghost" class="flex w-full justify-start items-center gap-2">
                                <x-icon name="folder" class="h-5 w-5" />
                                <span>{{ __('Proyectos') }}</span>
                            </flux:button>


                        </flux:dropdown>
                        <flux:dropdown position="right" align="start" class="w-full" hover>
                            <flux:button variant="ghost" class="flex w-full justify-start items-center gap-2">
                                <x-icon name="shopping-cart" class="h-5 w-5" />
                                <span>{{ __('Ventas') }}</span>
                                <x-icon name="chevron-right" class="h-4 w-4 ml-auto" />
                            </flux:button>

                            <flux:menu class="w-48">
                                <flux:menu.item :href="route('dashboard')" wire:navigate>{{ __('Por Escrito') }}</flux:menu.item>
                                <flux:menu.item :href="route('dashboard')" wire:navigate>{{ __('Por Cuotas') }}</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                        <flux:dropdown position="right" align="start" class="w-full" hover>
                            <flux:button variant="ghost" class="flex w-full justify-start items-center gap-2">
                                <x-icon name="user" class="h-5 w-5" />
                                <span>{{ __('Usuarios') }}</span>
                                <x-icon name="chevron-right" class="h-4 w-4 ml-auto" />
                            </flux:button>

                            <flux:menu class="w-48">
                                <flux:menu.item :href="route('dashboard')" wire:navigate>{{ __('Asistente Administrativo') }}</flux:menu.item>
                                <flux:menu.item :href="route('dashboard')" wire:navigate>{{ __('Asesor') }}</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </div>

                    <flux:navlist.item icon="shopping-cart" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Ventas') }}</flux:navlist.item>
                    <flux:navlist.item icon="user" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Usuarios') }}</flux:navlist.item>
                    <flux:navlist.item icon="wrench" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Mantenimiento') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>-->

            <!--<flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Proyectos')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>-->

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
