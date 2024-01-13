<div>
    <x-form wire:submit="updateProfile">

        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div class="mb-4">
                <!-- Profile Photo File Input -->

                <x-input class="hidden" label="{{ __('Photo') }}" name="photo" type="file" wire:model="photo"
                    x-ref="photo" />

                <!-- Current Profile Photo -->
                <div class="mt-2 flex items-center gap-4">
                    <img alt="{{ $this->user->name }}" class="h-20 w-20 cursor-pointer rounded-full object-cover"
                        src="{{ $this->user->profile_photo_url }}" x-on:click.prevent="$refs.photo.click()">
                    @if ($photo)
                        <x-icon class="h-4 w-4" name="arrow-right" />
                        <img class="h-20 w-20 cursor-pointer rounded-full object-cover"
                            src="{{ $photo->temporaryUrl() }}" x-on:click.prevent="$refs.photo.click()">
                    @endif
                    <div class="text-yellow-700 dark:text-yellow-300" wire:loading wire:target="photo">
                        {{ __('Uploading...') }}</div>
                </div>

                <!-- New Profile Photo Preview -->

                <x-button class="mr-2 mt-2" secondary x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-button>

                @if ($user->profile_photo_path)
                    <x-button class="mt-2" secondary wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-button>
                @endif

            </div>
        @endif

        <div class="mt-4">
            <x-input label="{{ __('Username') }}" required wire:model="name" />
        </div>

        <div class="mt-4">
            <x-input label="{{ __('First name') }}" wire:model="firstname" />
        </div>

        <div class="mt-4">
            <x-input label="{{ __('Last name') }}" wire:model="lastname" />
        </div>

        <div class="mt-4">
            <x-input label="{{ __('Email') }}" type="email" wire:model="email" />
        </div>

        @if (
            $this->user->current &&
                Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) &&
                !$this->user->hasVerifiedEmail())
            @if ($this->verificationLinkSent)
                <p class="mt-2 text-sm font-medium text-green-600 dark:text-green-400" v-show="verificationLinkSent">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
            @endif
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                {{ __('Your email address is unverified.') }}

                <x-button class="button" secondary wire:click.prevent="sendEmailVerification">
                    {{ __('Click here to re-send the verification email.') }}
                </x-button>
            </p>
        @endif

        <div class="buttonrow mt-4">
            <x-button primary type="submit">
                {{ __('Save') }}
            </x-button>
        </div>
    </x-form>
</div>
