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
                    <flux:button variant="ghost" class="py-7 flex w-full justify-start items-center gap-4" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        <x-icon name="home" class="h-7 w-7" />
                        <span>{{ __('Dashboard') }}</span>
                    </flux:button>
                    <!--<flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>-->
                    
                    <flux:button variant="ghost" class="py-7 flex w-full justify-start items-center gap-4" :href="route('proyectos')" :current="request()->routeIs('proyectos')" wire:navigate>
                        <x-icon name="folder" class="h-7 w-7" />
                        <span>{{ __('Proyectos') }}</span>
                    </flux:button>
                    <!--<flux:navlist.item icon="folder" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Proyectos') }}</flux:navlist.item>-->

                    <flux:dropdown variant="ghost" position="right" align="start" class="w-full" hover>
                        <flux:button variant="ghost" class="py-7 flex w-full justify-start items-center gap-4">
                            <x-icon name="shopping-cart" class="h-7 w-7" />
                            <span>{{ __('Ventas') }}</span>
                            <x-icon name="chevron-right" class="h-4 w-4 ml-auto" />
                        </flux:button>                        
                            <flux:menu class="w-48">
                            <livewire:Menu.menu-proyectos />
                        </flux:menu>
                    </flux:dropdown>
                    <!--<flux:navlist.item icon="shopping-cart" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Ventas') }}</flux:navlist.item>-->

                    <flux:button variant="ghost" class="py-7 flex w-full justify-start items-center gap-4" :href="route('separados')" :current="request()->routeIs('separados')" wire:navigate>
                        <x-icon name="clipboard" class="h-7 w-7" />
                        <span>{{ __('Separados') }}</span>
                    </flux:button>
                    
                    <flux:button variant="ghost" class="py-7 flex w-full justify-start items-center gap-4" :href="route('usuarios')" :current="request()->routeIs('usuarios')" wire:navigate>
                        <x-icon name="user" class="h-7 w-7" />
                        <span>{{ __('Usuarios') }}</span>
                    </flux:button>
                    
                    <!--<flux:navlist.item icon="user" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Usuarios') }}</flux:navlist.item>-->
                    <flux:dropdown variant="ghost" position="right" align="start" class="w-full" hover>
                        <flux:button variant="ghost" class="py-7 flex w-full justify-start items-center gap-4">
                            <x-icon name="wrench" class="h-7 w-7" />
                            <span>{{ __('Mantenimiento') }}</span>
                            <x-icon name="chevron-right" class="h-4 w-4 ml-auto" />
                        </flux:button>
                        <flux:menu class="w-48">
                            <flux:menu.item :href="route('tipo_venta')" wire:navigate>{{ __('Tipo de venta') }}</flux:menu.item>
                            <flux:menu.item :href="route('tipo_usuario')" wire:navigate>{{ __('Tipo de usuario') }}</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
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
            </flux:dropdown>-->

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
