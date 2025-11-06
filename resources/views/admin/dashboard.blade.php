<x-admin-layout>
    <div class= "grid grid-cols-1 gap-6">

        <div class= "bg-white rounded-lg shadow-lg p-8">
            <div class="flex items-center">
                <div class="ml-4 flex-1">
                    <h2 class="text-lg font-semibold text-align-center">
                        Bienvenido al panel de adminstrador, {{ auth()->user()->name }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
