<div>
    <x-nav>
        <x-nav.link route="recipes.index">
            {{ __('Recipes') }}
        </x-nav.link>

        <x-nav.dropdown require="content">
            <x-slot name="trigger">
                {{ __('Settings') }}
            </x-slot>
            <x-nav.dd-link :show="check_read('recipe')" route="categories.index">
                {{ __('Categories') }}
            </x-nav.dd-link>
            <x-nav.dd-link :show="check_read('recipe')" route="units.index">
                {{ __('Units') }}
            </x-nav.dd-link>
            <x-nav.dd-link :show="check_read('recipe')" route="ingredients.index">
                {{ __('Ingredients') }}
            </x-nav.dd-link>
            <x-nav.dd-link :show="check_read('translate')" route="translations.index">
                Translations
            </x-nav.dd-link>
        </x-nav.dropdown>

        <x-nav.dropdown require="elevated">
            <x-slot name="trigger">
                {{ __('Admin menu') }}
            </x-slot>
            <x-nav.dd-link route="users.index">
                {{ __('Users') }}
            </x-nav.dd-link>
            <x-nav.dd-link route="globalsettings">
                {{ __('Global settings') }}
            </x-nav.dd-link>
        </x-nav.dropdown>
        <x-slot name="right">
            <x-nav.link require="loggedout" route="login">
                {{ __('Login') }}
            </x-nav.link>
            <x-nav.link class="bg-green-100 dark:bg-green-900" require="elevated">
                <div>
                    <div>{{ __('You have admin rights!') }}</div>
                    <div id="elevated_time">00:00:00</div>
                    <div>
                        <x-link class="text-green-600 dark:text-green-400"
                            route="user.unelevate">{{ __('Drop rights') }}</x-link>
                        <x-link class="text-green-600 dark:text-green-400"
                            route="user.elevate">{{ __('Extend') }}</x-link>
                    </div>
                </div>
            </x-nav.link>
            <x-nav.dropdown align="right" require="loggedin">
                <x-slot name="trigger">
                    <div class="flex items-center">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <img alt="" class="mr-2 h-8 w-8 rounded-full object-cover" height="32"
                                src="{{ Auth::user()?->profile_photo_url }}" width="32" />
                        @endif
                        <div>
                            <div>{{ Auth::user()?->firstname }} {{ Auth::user()?->lastname }}</div>
                            <div class="text-gray-500 dark:text-gray-400">{{ Auth::user()?->name }}</div>
                        </div>
                    </div>
                </x-slot>
                <!-- Account Management -->
                <x-nav.dd-group>
                    {{ __('Manage Account') }}
                </x-nav.dd-group>

                <x-nav.dd-link route="profile.show">
                    {{ __('Profile') }}
                </x-nav.dd-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-nav.dd-link route="api-tokens.index">
                        {{ __('API Tokens') }}
                    </x-nav.dd-link>
                @endif

                <x-nav.dd-separator />

                <x-nav.dd-link class="bg-red-100 dark:bg-red-900" require="unelevated" route="user.elevate">
                    <div>
                        <div class="text-red-600 dark:text-red-400">{{ __('Activate Admin') }}</div>
                    </div>
                </x-nav.dd-link>

                <!-- Authentication -->
                <x-nav.dd-link route="logout.fast">
                    {{ __('Log Out') }}
                </x-nav.dd-link>

            </x-nav.dropdown>

            @if ($color_on || $font_on)
                <x-nav.dropdown align="right">
                    <x-slot name="trigger">

                        <div class="block dark:hidden">
                            <x-icon class="h-6 w-6" name="sun" />
                        </div>
                        <div class="hidden dark:block">
                            <x-icon class="h-6 w-6" name="moon" />
                        </div>
                    </x-slot>

                    @if ($color_on)
                        <x-nav.dd-group>{{ __('Color mode') }}</x-nav.dd-group>
                        <x-nav.dd-link :active="session('theme.style') !== 'dark' && session('theme.style') !== 'light'" route="theme.style,system">
                            <div>{{ __('Automatic') }}</div>
                        </x-nav.dd-link>
                        <x-nav.dd-link :active="session('theme.style') === 'light'" route="theme.style,light">
                            <div><x-icon class="mr-1 inline h-5 w-5" name="sun" />{{ __('Light mode') }}</div>
                        </x-nav.dd-link>
                        <x-nav.dd-link :active="session('theme.style') === 'dark'" route="theme.style,dark">
                            <div><x-icon class="mr-1 inline h-5 w-5" name="moon" />{{ __('Dark mode') }}</div>
                        </x-nav.dd-link>
                    @endif

                    @if ($color_on && $font_on)
                        <x-nav.dd-separator />
                    @endif

                    @if ($font_on)
                        <x-nav.dd-group>{{ __('Font style') }}</x-nav.dd-group>
                        @if ($font['sans'])
                            <x-nav.dd-link :active="(session('theme.font') ?? 'sans') === 'sans'" route="theme.font,sans">
                                <div class="font-sans">{{ __('Sans serif') }}</div>
                            </x-nav.dd-link>
                        @endif
                        @if ($font['serif'])
                            <x-nav.dd-link :active="session('theme.font') === 'serif'" route="theme.font,serif">
                                <div class="font-serif">{{ __('Serif') }}</div>
                            </x-nav.dd-link>
                        @endif
                        @if ($font['mono'])
                            <x-nav.dd-link :active="session('theme.font') === 'mono'" route="theme.font,mono">
                                <div class="font-mono">{{ __('Monospace') }}</div>
                            </x-nav.dd-link>
                        @endif
                    @endif
                </x-nav.dropdown>
            @endif

            @if (count($available_locales) > 1)
                <x-nav.dropdown align="right">
                    <x-slot name="trigger">
                        <x-icon class="h-6 w-6" name="flag" /> {{ __('LANG:' . $current_locale) }}
                    </x-slot>

                    @foreach ($available_locales as $locale)
                        <x-nav.dd-link :active="$locale == $current_locale" route="language,{{ $locale }}">
                            <div>{{ __('LANG:' . $locale, [], $locale) }}</div>
                        </x-nav.dd-link>
                    @endforeach
                </x-nav.dropdown>
            @endif
        </x-slot>
    </x-nav>
    @if (Auth::user()?->elevated)
        <script type="module">
            var elevated_time = {{ Auth::user()->elevatetime() + 1 }};

            function elevated_timer() {
                if (elevated_time <= 0) {
                    Livewire.dispatch('refresh-navigation-menu');
                } else {
                    elevated_time--;
                    var h = (Math.floor(elevated_time / 3600)).toString().padStart(2, '0');
                    var m = (Math.floor(elevated_time / 60) % 60).toString().padStart(2, '0');
                    var s = (elevated_time % 60).toString().padStart(2, '0');
                    document.getElementById('elevated_time').innerHTML = h + ':' + m + ':' + s;
                    window.setTimeout(elevated_timer, 1000);
                }
            }

            elevated_timer();
        </script>
    @endif
</div>
