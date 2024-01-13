@props(['user' => null])
@if ($user)
    <div class="inline">
        <div class="inline align-middle"><x-icon class="{{ user_icon_color($user) }} mr-2 inline h-4 w-4 rounded-md p-0.5"
                name="user" /></div>
        @if (\Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <img class="mr-2 inline h-6 w-6 rounded-full object-cover align-middle" height="24"
                src="{{ $user->profile_photo_url }}" width="24" />
        @endif
        {{ $user->extendedname }}
    </div>
@endif
