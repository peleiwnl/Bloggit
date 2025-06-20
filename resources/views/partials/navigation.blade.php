<nav x-data="{ open: false }"
    class="sticky top-0 z-50 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 border-r border-gray-200 dark:border-gray-600 sm:py-2 pr-2">
    <div class="flex items-center justify-between w-full">

        <!--left items - app name and logo -->
        <div class="flex items-center ml-2">
            <a href="{{ route('posts.index') }}">
                <x-application-logo class="block h-8 sm:h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
            </a>
            <a>
                <span class="ml-2 text-base sm:text-2xl font-bold text-red-500 dark:text-gray-200">Bloggit</span>
            </a>
        </div>

        <!--search bar -->
        <div class="flex-grow px-3 sm:px-4 md:px-6 max-w-xl mx-auto w-full sm:w-auto">
            @livewire('search-posts')
        </div>

        <div class="hidden sm:flex sm:items-center sm:space-x-3">
            <a href="{{ route('posts.create') }}"
                class="flex items-center p-2 hover:bg-gray-200 rounded-full transition-colors duration-200 text-sm text-black">
                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M12 5a1 1 0 011 1v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H5a1 1 0 110-2h6V6a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                {{ __('Create') }}
            </a>

            <!-- notifications -->
            @auth
                @livewire('notifications-dropdown')
            @endauth

            <!-- avatar dropdown -->
            @auth
                <x-dropdown>
                    <x-slot name="trigger">
                        <div
                            class="flex items-center hover:bg-gray-200 p-1 rounded-full transition-colors duration-200 cursor-pointer">
                            @if (Auth::user()->profile && Auth::user()->profile->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}"
                                    alt="{{ Auth::user()->name }}'s Avatar"
                                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-full object-cover">
                            @else
                                <svg class="h-7 w-7 sm:h-8 sm:w-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            @endif
                        </div>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('users.show', Auth::user())" class="text-sm">
                            {{ __('View Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.edit')" class="text-sm">
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('news.index')" class="text-sm">
                            {{ __('News') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();" class="text-sm">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <a href="{{ route('login') }}"
                    class="text-black inline-flex items-center px-3 py-1 sm:px-3 sm:py-2 border border-transparent text-sm sm:text-sm leading-4 font-medium rounded-md  dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                    {{ __('Log In') }}
                </a>
            @endauth
        </div>
        
        <div class="flex items-center ml-auto sm:hidden">
            <button @click="open = ! open"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!--mobile menu-->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('posts.create')" :active="request()->routeIs('posts.create')" class="text-sm">
                {{ __('Create') }}
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('users.show', Auth::user())" :active="request()->routeIs('users.show')" class="text-sm">
                    {{ __('My Profile') }}
                </x-responsive-nav-link>
            @endauth

            <x-responsive-nav-link :href="route('news.index')" :active="request()->routeIs('news.index')" class="text-sm">
                {{ __('News') }}
            </x-responsive-nav-link>

        </div>


        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                @auth
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                @else
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">Guest</div>
                @endauth
            </div>

            <div class="mt-3 space-y-1">
                @livewire('notifications-dropdown')
                <x-responsive-nav-link :href="route('profile.edit')" class="text-sm">
                    {{ __('Edit Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();" class="text-sm">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
