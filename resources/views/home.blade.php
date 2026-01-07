<x-layouts.shop>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-6">Nuestros Productos</h1>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white p-4 rounded shadow">
                        <h2 class="font-bold text-lg">{{ $product->name }}</h2>
                        <p class="text-gray-600">${{ number_format($product->price, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.shop>
