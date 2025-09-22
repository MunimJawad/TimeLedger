<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Page Heading -->
    <div class="container mx-auto my-8 px-4">
        <h1 class="text-3xl font-bold text-center mb-6">Order List</h1>
        <div class="relative overflow-x-auto shadow-lg rounded-lg bg-white">
            <table class="w-full text-sm text-left text-black-500">
                <thead class="text-xs text-gray-700 uppercase bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-3">Order Number</th>
                        <th class="px-6 py-3">Total Items</th>
                        <th class="px-6 py-3">Total Price</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $order)

                        <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200 hover:bg-gray-100">
                            <td class="px-6 py-4">{{ $order["id"] }}</td>
                            <td class="px-6 py-4">{{ $order["total_items"] }}</td>
                            <td class="px-6 py-4">{{ $order["total_price"] }}</td>
                            <td class="px-6 py-4">{{ $order["status"] }}</td>
                            <td class="px-6 py-4"><a href="{{ route('order.detail',['id' => $order['id']]) }}" class="bg-white-500 border-2 border-purple-500 px-3 py-2 text-black hover:bg-purple-500 hover:text-white rounded font-semibold">View Details</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
