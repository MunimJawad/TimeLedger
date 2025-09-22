<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center py-10">

    <div class="w-full max-w-4xl bg-white rounded-lg shadow p-6">
        <div class="flex justify-between">
        <h2 class="text-2xl font-bold mb-6">Your Shopping Cart</h2>
        </div>
        <div id="cart"></div>
      
        <p id="totalPrice" class="text-lg font-semibold"></p>
     
        <div class=" mt-6 ">
            <a href="{{ route('django.users') }}" class="bg-white-600 border-2 border-blue-600 text-black px-3 py-2 rounded hover:bg-blue-700 hover:text-white transition duration-200">Continue Shopping</a>
            <a href="" class="bg-white-600 border-2 border-green-600 text-black px-3 py-2 rounded hover:bg-green-700 hover:text-white transition duration-200">Checkout</a>
        </div>
    </div>

    <script>
        const API_URL = "http://127.0.0.1:8000/api/cart/"; 
        const TOKEN = "{{ session('django_token') }}";

        // Fetch Cart
        async function fetchCart() {
            let res = await fetch(API_URL, {
                headers: { 'Authorization': 'Bearer ' + TOKEN }
            });
            let data = await res.json();
            renderCart(data);
            console.log(data)
        }

        // Update quantity
        async function updateQuantity(itemId, qty) {
            let res = await fetch(API_URL, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + TOKEN
                },
                body: JSON.stringify({
                    item_id: itemId,
                    quantity: qty
                })
            });
            let data = await res.json();
            renderCart(data);
        }

        // Remove item
        async function removeItem(itemId) {
            try {
                let res = await fetch(API_URL, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": "Bearer " + TOKEN
                    },
                    body: JSON.stringify({ item_id: itemId })
                });
                let data = await res.json();
                if (res.ok) {
                    renderCart(data);
                } else {
                    alert(data.error || data.detail || "Failed to remove item");
                }
            } catch (err) {
                console.error("Remove item error:", err);
                alert("Network error");
            }
        }

        // Render cart
        function renderCart(cart) {
            let cartDiv = document.getElementById("cart");

            if (!cart.items || cart.items.length === 0) {
                cartDiv.innerHTML = "<p class='text-gray-500'>Your cart is empty.</p>";
                document.getElementById('totalPrice').innerText = '';
                return;
            }

            let html = `<div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Product</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Quantity</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Price</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">`;

            cart.items.forEach(item => {
                html += `<tr>
                            <td class="px-4 py-2 text-gray-800 font-medium">${item.product_title}</td>
                            <td class="px-4 py-2">
                                <input type="number" value="${item.quantity}" min="1" 
                                       class="w-16 border rounded px-2 py-1"
                                       onchange="updateQuantity(${item.id}, this.value)">
                            </td>
                            <td class="px-4 py-2 text-gray-800">$${item.item_total_price}</td>
                            <td class="px-4 py-2">
                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
                                        onclick="removeItem(${item.id})">Remove</button>
                            </td>
                         </tr>`;
            });

            html += `</tbody></table></div>`;
            cartDiv.innerHTML = html;

            // Update total price
            const totalPrice = document.getElementById('totalPrice');
            totalPrice.innerText = `Total Price: $${cart.total_price}`;
        }

        // Initial load
        fetchCart();
    </script>

</body>
</html>
