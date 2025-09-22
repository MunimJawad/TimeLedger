<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg mt-8">
        <h1 class="text-3xl font-semibold text-center text-blue-600 mb-6">Order Details</h1>

        <div class="space-y-4">
            <!-- Order ID and Created Date -->
            <div class="flex justify-between items-center">
                <p class="text-lg font-medium">Order ID: <span class="font-semibold text-indigo-600">{{ $order["id"] }}</span></p>
                <p class="text-sm text-gray-600">Created: {{ \Carbon\Carbon::parse($order['created_at'])->format('F j, Y') }}</p>
            </div>

            <!-- Order Items -->
            <div class="space-y-4">
                @foreach ($order["items"] as $item)
                    <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                        <p class="text-lg font-semibold">{{ $item["product_title"] }}</p>
                        <p class="text-sm text-gray-500">Quantity: {{ $item["quantity"] }}</p>
                        <p class="text-sm text-gray-700">Item Total Price: ${{ number_format($item["item_total_price"], 2) }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Shipping Address -->
            @if($order["shipping_address"])
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-3">Shipping Address</h3>
                    <p class="text-sm text-gray-700">Address: {{ $order["shipping_address"]["address"] }}</p>
                    <p class="text-sm text-gray-700">City: {{ $order["shipping_address"]["city"] }}</p>
                    <p class="text-sm text-gray-700">Postal Code: {{ $order["shipping_address"]["zip_code"] }}</p>
                </div>
            @else
                <p class="text-sm text-gray-600">No shipping address available.</p>
            @endif

            <!-- Order Status and Totals -->
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm mt-4">
                <div class="flex justify-between items-center">
                    <p class="font-medium">Status: <span class="text-blue-600">{{ $order["status"] }}</span></p>
                    <p class="text-sm text-gray-600">Order Total Price: ${{ number_format($order["total_price"], 2) }}</p>
                </div>
                <p class="text-sm text-gray-600 mt-2">Total Items: {{ $order["total_items"] }}</p>
            </div>

            <!-- Add Button/Link for more actions -->
            <div class="flex justify-end mt-6">
                <a href="{{ route('order.list') }}" class="px-6 py-2 text-white bg-blue-500 hover:bg-blue-600 rounded-md font-semibold transition duration-300">Back to Orders</a>
            </div>
        </div>
    </div>

</body>
</html>
