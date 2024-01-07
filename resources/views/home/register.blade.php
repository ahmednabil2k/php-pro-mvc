@extends('../layouts/app')

<h1 class="text-xl font-semibold mb-4">Register</h1>

<form
        method="post"
        action="{{ $router->route('register') }}"
        class="flex flex-col w-full space-y-4"
>

    <input
            id="csrf_token"
            name="csrf_token"
            type="hidden"
            value="{{csrf()}}"
    />

    <label for="name" class="flex flex-col w-full">
        <span class="flex">Name:</span>
        <input
                id="name"
                name="name"
                type="text"
                class="focus:outline-none focus:border-blue-300 border-b-2 bordergray-300"
                placeholder="Alex"
        />
    </label>
    <label for="email" class="flex flex-col w-full">
        <span class="flex">Email:</span>
        <input
                id="email"
                name="email"
                type="email"
                class="focus:outline-none focus:border-blue-300 border-b-2 bordergray-300"
                placeholder="alex.42@gmail.com"
        />
    </label>
    <label for="password" class="flex flex-col w-full">
        <span class="flex">Password:</span>
        <input
                id="password"
                name="password"
                type="password"
                class="focus:outline-none focus:border-blue-300 border-b-2 bordergray-300"
        />
    </label>
    <button
            type="submit"
            class="focus:outline-none focus:border-blue-500 focus:bg-blue-400
            border-b-2 border-blue-400 bg-blue-300 p-2">
        Register
    </button>
</form>