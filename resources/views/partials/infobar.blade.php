<div class="infobar">


    <!--information section-->
    <h3 class="text-l font-bold mb-4">Welcome!</h3>
    <p class="text-sm text-gray-600 mb-4">
        Explore our latest posts, dive into interesting topics,
        and join our community of readers and writers.
    </p>

    <!--statistics section-->
    <div class="flex justify-center space-x-8 text-sm text-gray-600 mb-5">
        <div class="flex items-center">
            <span><span class="text-black font-bold">{{ \App\Models\User::count() }}</span> Members</span>
        </div>
        <div class="flex items-center">
            <span><span class="text-black font-bold">{{ \App\Models\Post::count() }}</span> Total Posts</span>
        </div>
    </div>

    <!--rules section-->
    <div class="border-t pt-4">
        <h3 class="text-l font-bold mb-3">Rules</h3>
        <ul class="text-sm text-gray-600 space-y-2">
            <li class="flex items-start">
                <span class="text-black font-medium mr-2">1.</span>
                <span>Rule 1 - Be respectful and kind to other community members</span>
            </li>
            <li class="flex items-start">
                <span class="text-black font-medium mr-2">2.</span>
                <span>Rule 2 - No spam or self-promotion without permission</span>
            </li>
            <li class="flex items-start">
                <span class="text-black font-medium mr-2">3.</span>
                <span>Rule 3 - Posts must be clear and direct</span>
            </li>
            <li class="flex items-start">
                <span class="text-black font-medium mr-2">4.</span>
                <span>Rule 4 - No personal info</span>
            </li>
            <li class="flex items-start">
                <span class="text-black font-medium mr-2">5.</span>
                <span>Rule 5 - No begging for goods or services</span>
            </li>
            <li class="flex items-start">
                <span class="text-black font-medium mr-2">6.</span>
                <span>Rule 6 - No Harmful Misinformation</span>
            </li>
        </ul>
    </div>

    <!--administrators section-->
    <div class="border-t pt-4 mt-6">
        <h3 class="text-l font-bold mb-3">Administrators</h3>
        <div class="space-y-3">
            @foreach ($admins as $admin)
                <div class="flex items-center space-x-3">
                    @if ($admin->profile && $admin->profile->avatar)
                        <img src="{{ Storage::url($admin->profile->avatar) }}" alt="{{ $admin->name }}'s avatar"
                            class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500 text-sm">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    <a href="{{ route('users.show', $admin) }}"
                        class="text-sm text-gray-600 hover:text-gray-900 hover:underline">
                        {{ $admin->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
