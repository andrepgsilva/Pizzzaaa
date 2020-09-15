<nav class="flex items-center justify-between flex-wrap p-6 bg-red-600">
    <div class="flex items-center flex-shrink-0 text-white mr-6">
        <img src="{{ asset('img/pizzzaaa-logo.png') }}" alt="">
    </div>
    <div class="block lg:hidden">
        <button
            class="flex items-center px-3 py-2 border rounded text-white border-teal-400 hover:text-white hover:border-white">
            <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <title>Menu</title>
                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
            </svg>
        </button>
    </div>
    <div class="w-full flex justify-end flex-grow lg:flex lg:w-auto">
        <div>
            <a href="#"
                class="inline-block text-md px-4 py-2 leading-none border rounded bg-yellow-400 text-gray-100 border-yellow-400 hover:border-transparent mt-4 lg:mt-0"
            >
                Buy now!
            </a>
            <a href="#responsive-header" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-white ml-4">
                Login
            </a>
            <a href="#responsive-header" class="block mt-4 lg:inline-block lg:mt-0 text-white hover:text-white ml-4">
                Sign up
            </a>
        </div>
    </div>
</nav>