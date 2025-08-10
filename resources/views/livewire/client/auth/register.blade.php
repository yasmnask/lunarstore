<div>
    <section class="flex flex-col md:flex-row h-screen">
        <div class="hidden lg:block w-full md:w-1/2 xl:w-2/3 h-screen fixed left-0 top-0">
            <img src="{{ asset('assets/client/images/bg_login_lunar2.png') }}" alt="Auth Banner"
                class="w-full h-full object-cover" />
        </div>

        <div
            class="register-box bg-white w-full md:max-w-md lg:max-w-full md:w-1/2 xl:w-1/3 min-h-screen overflow-y-auto px-6 lg:px-16 xl:px-12 lg:ml-auto">
            <div class="w-full max-w-md py-12 mx-auto">
                <h1 class="text-xl md:text-2xl font-bold leading-tight mb-3">
                    Register New Account
                </h1>
                <a href="/" wire:navigate
                    class="flex items-center text-blue-500 hover:text-blue-700 w-max transition-colors">
                    <span>&laquo; Back To Home</span>
                </a>

                <x-alert :component="$this" />

                <form class="mt-6" wire:submit="register">
                    <div class="mb-4">
                        <label class="block text-gray-700" for="username">Username <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="username" id="username" placeholder="Enter Username"
                            class="w-full px-4 py-3 rounded-md mt-2 border @if ($errors->has('username')) border-red-500 @else focus:border-blue-500 @endif focus:bg-white focus:outline-none"
                            minlength="4" autofocus />
                        @error('username')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fullname field -->
                    <div class="mb-4">
                        <label class="block text-gray-700" for="name">Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" id="name" placeholder="Enter Your Full Name"
                            class="w-full px-4 py-3 rounded-md mt-2 border @if ($errors->has('name')) border-red-500 @else focus:border-blue-500 @endif focus:bg-white focus:outline-none"
                            minlength="6" />
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email field -->
                    <div class="mb-4">
                        <label class="block text-gray-700" for="email">Email Address <span
                                class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" id="email" placeholder="Enter Email Address"
                            class="w-full px-4 py-3 rounded-md mt-2 border @if ($errors->has('email')) border-red-500 @else focus:border-blue-500 @endif focus:bg-white focus:outline-none" />
                        @error('email')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4" x-data="{ show: false }">
                        <label class="block text-gray-700 mb-2" for="password">Password <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="password" id="password"
                                placeholder="Enter Password"
                                class="w-full px-4 py-3 rounded-md border @error('password') border-red-500 @else focus:border-blue-500 @enderror focus:bg-white focus:outline-none pr-10" />

                            <button type="button" @click="show = !show"
                                class="absolute top-1/2 -translate-y-1/2 right-3 text-gray-600 focus:outline-none"
                                tabindex="-1">
                                <i class="text-[19px] leading-none"
                                    :class="show ? 'far fa-eye-slash' : 'far fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Confirm Password field -->
                    <div class="mb-4" x-data="{ show: false }">
                        <label class="block text-gray-700 mb-2" for="confirm_password">Confirm Password <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="confirm_password"
                                id="confirm_password" placeholder="Confirm Password" minlength="6"
                                class="w-full px-4 py-3 rounded-md border @if ($errors->has('confirm_password')) border-red-500 @else focus:border-blue-500 @endif focus:bg-white focus:outline-none pr-10" />
                            <button type="button" @click="show = !show"
                                class="absolute top-1/2 -translate-y-1/2 right-3 text-gray-600 focus:outline-none"
                                tabindex="-1">
                                <i class="text-[19px] leading-none"
                                    :class="show ? 'far fa-eye-slash' : 'far fa-eye'"></i>
                            </button>
                        </div>
                        @error('confirm_password')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Terms and conditions checkbox -->
                    <div class="flex items-start mb-4 w-max">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="terms" wire:model="terms"
                                class="h-4 w-4 cursor-pointer text-blue-500 border-gray-300 rounded focus:ring-blue-500" />
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="text-gray-700 cursor-pointer">
                                I accept the
                                <a href="#" class="text-blue-500 hover:underline">
                                    Terms and Conditions
                                </a>
                            </label>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full block bg-blue-500 hover:bg-blue-600 focus:bg-blue-600 text-white font-semibold rounded-lg px-4 py-3 mt-6 text-center disabled:opacity-50"
                        wire:loading.attr="disabled">

                        <svg wire:loading class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z">
                            </path>
                        </svg>

                        <span wire:loading.remove>Register Now</span>
                        <span wire:loading>Creating account...</span>
                    </button>
                </form>

                <hr class="my-4 border-gray-300 w-full" />

                @livewire('client.auth.google-login', ['currentType' => 'register'])

                <p class="mt-5 text-center">
                    Already have an account?
                    <a href="{{ route('login') }}" wire:navigate
                        class="text-blue-500 hover:text-blue-700 font-semibold underline underline-offset-2">Login
                        Now</a>
                </p>
            </div>
        </div>
    </section>
</div>
