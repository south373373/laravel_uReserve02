<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <!-- 新規作成したロゴの大きさを調整 -->
                        <x-application-logo class="block h-16 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- ユーザー会員のみアクセス可能 -->
                    @auth
                        @if(Auth::user()->role > 0 && Auth::user()->role <= 9)
                        <x-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                            イベントカレンダー
                        </x-nav-link>
                        <x-nav-link :href="route('mypage.index')" :active="request()->routeIs('mypage.index')">
                            マイページ
                        </x-nav-link>
                        @endif
                    @endauth

                    
                    <!-- 管理責任者以上でアクセス可能 -->
                    <!-- ※「Responsive Navigation Menu」も同様に設定 -->
                    @can('manager-higher')
                    <!-- 予約状況管理-論理削除実施の対象データ一覧 -->
                    <x-nav-link :href="route('reservations.index')" :active="request()->routeIs('reservations.index')">
                        予約履歴一覧
                    </x-nav-link>
                    <x-nav-link :href="route('conferences.index')" :active="request()->routeIs('conferences.index')">
                        イベント一覧
                    </x-nav-link>
                    <!-- イベント管理-論理削除実施の対象データ一覧 -->
                    <x-nav-link :href="route('conferences.trashed')" :active="request()->routeIs('conferences.trashed')">
                        無効イベント一覧
                    </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <!-- ログイン済みの場合のユーザー情報表示 -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- 未ログインの場合のリンク表示 -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700">Log in</a>
                        <a href="{{ route('register') }}" class="text-sm text-gray-700">Register</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- 管理責任者以上でアクセス可能 -->
            <!-- ※「Navigation Links」も同様に設定 -->
            @can('manager-higher')
            <x-responsive-nav-link :href="route('conferences.index')" :active="request()->routeIs('conferences.index')">
                イベント管理
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('conferences.trashed')" :active="request()->routeIs('conferences.trashed')">
                無効イベント一覧
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reservations.index')" :active="request()->routeIs('reservations.index')">
                予約一覧
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reservations.trashed')" :active="request()->routeIs('reservations.trashed')">
                無効予約一覧
            </x-responsive-nav-link>
            @endcan

            <!-- ユーザー会員のみアクセス可能 -->
            @auth
                @if(Auth::user()->role > 0 && Auth::user()->role <= 9)
                <!-- タブがアクセスした時に「水色の表示になっている時のアクションは「routeIs」が自分のrouteと合っていれば表示される」 -->
                <x-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                イベントカレンダー_[navigation.blade.php]
                </x-nav-link>
                <x-nav-link :href="route('mypage.index')" :active="request()->routeIs('mypage.index')">
                マイページ_[navigation.blade.php]
                </x-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4">
                    <a href="{{ route('login') }}" class="text-sm text-gray-700">Log in</a>
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700">Register</a>
                </div>
            @endauth 
        </div>
    </div>
</nav>
