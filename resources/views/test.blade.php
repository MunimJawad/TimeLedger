<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>User Dashboard</title>
        
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
          

            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.5);
            }

            .modal-content {
                background-color: white;
                margin: 2% auto;
                padding: 20px;
                border-radius: 8px;
                width: 90%;
                max-width: 500px;
                position: relative;
            }

            .close {
                color: #aaa;
                position: absolute;
                right: 16px;
                top: 10px;
                font-size: 24px;
                font-weight: bold;
                cursor: pointer;
            }

            .form label {
                display: block;
                margin-top: 10px;
                font-weight: 600;
            }

            .form input,
            .form textarea {
                width: 100%;
                padding: 8px;
                margin-top: 4px;
                border-radius: 4px;
                border: 1px solid #ccc;
            }

            .submit-btn {
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #10b981;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .submit-btn:hover {
                background-color: #059669;
            }
        </style>
    </head>
    <body class="bg-gray-100 text-gray-800 min-h-screen  font-sans">
        <div
            class="max-w-8xl mx-auto w-full bg-white shadow-xl rounded-lg p-8  space-y-10"
        >
            <!-- Modal -->
            <div id="productModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeModalBtn">&times;</span>

                    <h2>Add New Product</h2>

                    <form
                        action="{{ route('create.product') }}"
                        method="POST"
                        class="form"
                    >
                        @csrf

                        <label for="category_id">Category ID:</label>
                        <input
                            type="number"
                            name="category_id"
                            id="category_id"
                            required
                        />

                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" required />

                        <label for="slug">Slug:</label>
                        <input type="text" name="slug" id="slug" required />

                        <label for="description">Description:</label>
                        <textarea
                            name="description"
                            id="description"
                            rows="3"
                            required
                        ></textarea>

                        <label for="price">Price ($):</label>
                        <input
                            type="number"
                            step="0.01"
                            name="price"
                            id="price"
                            required
                        />

                        <label for="stock">Stock:</label>
                        <input type="number" name="stock" id="stock" required />

                        <button type="submit" class="submit-btn">
                            Create Product
                        </button>
                    </form>
                </div>
            </div>

            <!-- User Info -->
            <div class="flex justify-between">
                <div class="">
                <h2 class="text-3xl font-bold text-blue-700 mb-1">
                    Welcome, {{ $data["authUser"]["username"] }}
                </h2>
                <p class="text-lg text-gray-600">
                    Email: {{ $data["authUser"]["email"] }}
                </p>
                </div>
                            <!-- View Cart Button -->
                <div class="space-x-4">
                    <a href="{{ route('order.list') }}" 
                       class="bg-white-500 text-black border-2 border-pink-500 px-4 py-2 rounded hover:bg-pink-600 hover:border-0 hover:text-white">
                       Orders
                    </a>
                    <a href="{{ route('cart.page') }}" 
                       class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                       View Cart
                    </a>
                </div>
              </div>

            <!-- Category List -->
            <div>
                <div class="flex justify-between border-b">
                    <h3 class="text-2xl font-semibold text-gray-700 pb-2">
                        Categories
                    </h3>
                    <p class="text-blue-600 cursor-pointer" id="category_icon">
                        Show
                    </p>
                </div>
                <div
                    id="category_list"
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-4 transition duration-200"
                    style="display: none"
                >
                    @forelse ($categories['categories'] as $category)
                    <div
                        class="p-4 bg-blue-50 border border-blue-200 rounded-md shadow-sm text-center"
                    >
                        <span class="font-medium text-blue-800">{{
                            $category["name"]
                        }}</span>
                    </div>
                    @empty
                    <p class="text-gray-500 col-span-full">
                        No categories found.
                    </p>
                    @endforelse
                </div>
            </div>

            <!-- Product List -->
            <div class="transition-all">
                <div class="flex justify-between  mb-4">
                    <h3
                        class="text-2xl font-semibold text-gray-700  mb-4"
                    >
                        Products
                    </h3>
                    <!-- Trigger Button -->
                    <button id="openModalBtn" class="bg-white-500 border-2 border-blue-600 px-3 px-2 font-semibold rounded hover:bg-blue-600 hover:text-white hover:border-0" >
                        Add Product
                    </button>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"
                >
                    @forelse ($products['product_list'] as $product)
                    <div
                        class="p-4 bg-white border border-gray-200 rounded-lg shadow hover:shadow-md transition"
                    >
                    <div class="flex justify-between">
                        <h4 class="text-lg font-bold text-gray-800 mb-1">
                            {{ $product["title"] }}
                        </h4>
                        <div class="flex gap-2">

                        <form action="{{ route('delete.product', ['id' => $product['id']]) }}" method="POST" style="display:inline;" class="order-last">
    @csrf
    @method('DELETE')
    <button type="submit" onclick="return confirm('Are you sure?')" class="bg-red-500 py-1 px-3 rounded text-white">Delete</button>
</form>


<!-- Edit Button inside your product card -->
<button 
    class="bg-green-500 text-white px-2 py-1 rounded openEditModal"
    data-id="{{ $product['id'] }}"
    data-title="{{ $product['title'] }}"
    data-slug="{{ $product['slug'] }}"
    data-description="{{ $product['description'] }}"
    data-price="{{ $product['price'] }}"
    data-stock="{{ $product['stock'] }}"
    data-category="{{ $product['category']['id'] }}"
>
    Edit
</button>

</div>
<!--Edit Modal -->
<div id="editProductModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEditModalBtn">&times;</span>

        <h2>Edit Product</h2>

        <form
            action=""
            method="POST"
            class="form"
            id="editProductForm"
        >
            @csrf
            @method('PUT')

            <input type="hidden" id="edit_product_id" name="product_id">

            <label for="edit_category_id">Category ID:</label>
            <input type="number" name="category_id" id="edit_category_id" required />

            <label for="edit_title">Title:</label>
            <input type="text" name="title" id="edit_title" required />

            <label for="edit_slug">Slug:</label>
            <input type="text" name="slug" id="edit_slug" required />

            <label for="edit_description">Description:</label>
            <textarea name="description" id="edit_description" rows="3" required></textarea>

            <label for="edit_price">Price ($):</label>
            <input type="number" step="0.01" name="price" id="edit_price" required />

            <label for="edit_stock">Stock:</label>
            <input type="number" name="stock" id="edit_stock" required />

            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</div>



                    </div>
                        <p class="text-sm text-gray-500 mb-2">
                            {{ \Illuminate\Support\Str::limit($product['description'] ?? 'No description', 50, '...') }}
                        </p>
                        <div
                            class="flex justify-between text-sm text-gray-600 mb-1"
                        >
                            <span
                                >Price:
                                <span class="text-green-600 font-semibold"
                                    >${{ $product["price"] }}</span
                                ></span
                            >
                            <span
                                >Stock:
                                <span class="font-semibold">{{
                                    $product["stock"]
                                }}</span></span
                            >
                        </div>
                        <div class="text-xs text-gray-500">
                            Category: {{ $product["category"]["name"] }}
                        </div>
                        <div>
                          <button 
                    class=" font-semibold mt-3 w-full bg-white-600 text-black border-2 border-blue-500 px-4 py-2 rounded hover:bg-blue-700 hover:text-white hover:border-0"
                   onclick="openModal({{ $product['id'] }}, '{{ addslashes($product['title']) }}')">
                    Add to Cart
                </button>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 col-span-full">
                        No products found.
                    </p>
                    @endforelse
                </div>
            </div>

            <!-- User List -->
            <div>
                <h3
                    class="text-2xl font-semibold text-gray-700 border-b pb-2 mb-4"
                >
                    Users (Total:
                    <span class="text-green-600">{{
                        $data["total_users"]
                    }}</span
                    >)
                </h3>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"
                >
                    @foreach ($data['users'] as $user)
                    <div
                        class="p-4 bg-gray-50 border border-gray-200 rounded-lg shadow-sm hover:bg-blue-50 transition"
                    >
                        <div class="text-lg font-semibold text-gray-800">
                            {{ $user["username"] }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ $user["email"] }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            Role: {{ ucfirst($user["role"]) }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!--Cart Modal-->
            <div id="cartModal" class=" hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                    <h3 class="text-lg font-bold mb-4">Add to Cart</h3>
                    <p id="modalProductName" class="mb-2 font-medium text-gray-700"></p>
                    <input type="hidden" id="modalProductId">
                    <label for="modalQuantity" class="block text-sm font-medium text-gray-600">Quantity</label>
                    <input type="number" id="modalQuantity" min="1" value="1" class="w-full border rounded px-3 py-2 mt-1 mb-4">
                    
        <div class="flex justify-end space-x-3">
            <button class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"  onclick="closeCartModal()">
                 
                Cancel
            </button>
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                   onclick="submitToCart()" >
                Add
            </button>
        </div>
                </div>
            </div>
        </div>

        <script>
            const icon = document.getElementById("category_icon");
            const category_list = document.getElementById("category_list");
            icon.addEventListener("click", () => {
                category_list.style.display =
                    category_list.style.display === "none" ? "grid" : "none";
                icon.innerText = icon.innerText === "Show" ? "Hide" : "Show";
            });

            const modal = document.getElementById("productModal");
            const openBtn = document.getElementById("openModalBtn");
            const closeBtn = document.getElementById("closeModalBtn");

            openBtn.onclick = function () {
                modal.style.display = "block";
            };

            closeBtn.onclick = function () {
                modal.style.display = "none";
            };

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };
document.addEventListener("DOMContentLoaded", function () {
    const editModal = document.getElementById("editProductModal");
    const openEditBtns = document.querySelectorAll(".openEditModal");
    const closeEditButton = document.getElementById("closeEditModalBtn");
    const form = document.getElementById("editProductForm");

    openEditBtns.forEach(button => {
        button.addEventListener("click", function () {
            
             const productId = this.dataset.id;
            
             form.action = `/update-product/${productId}`;

            // ✅ Get elements safely
            const productIdInput = document.getElementById("edit_product_id");
            const titleInput = document.getElementById("edit_title");
            const slugInput = document.getElementById("edit_slug");
            const descriptionInput = document.getElementById("edit_description");
            const priceInput = document.getElementById("edit_price");
            const stockInput = document.getElementById("edit_stock");
            const categoryIdInput = document.getElementById("edit_category_id");

          

            // ✅ Set values
            productIdInput.value = this.dataset.id;
            titleInput.value = this.dataset.title;
            slugInput.value = this.dataset.slug;
            descriptionInput.value = this.dataset.description;
            priceInput.value = this.dataset.price;
            stockInput.value = this.dataset.stock;
            categoryIdInput.value = this.dataset.category;

            editModal.style.display = "block";
        });
    });

    closeEditButton.onclick = function () {
        editModal.style.display = "none";
    };

    window.onclick = function (event) {
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    };
});


//Cart Modal

const API_URL= "http://127.0.0.1:8000/api/cart/";
const Token= "{{ session('django_token') }}"

 function openModal(productId, productName) {
     console.log(productId)
     console.log(productName)
       document.getElementById("modalProductId").value = productId
        document.getElementById("modalProductName").innerText = productName
        document.getElementById("modalQuantity").value = 1;

        document.getElementById("cartModal").classList.remove("hidden");
    }

     function closeCartModal() {
        document.getElementById("cartModal").classList.add("hidden");
    }

   async function submitToCart() {
        let productId = document.getElementById("modalProductId").value;
        let qty = document.getElementById("modalQuantity").value;

        let res = await fetch(API_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + Token
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: qty
            })
        });

        let data = await res.json();
        console.log(data)

        if (res.ok) {
            alert("Product added to cart!");

            
        } else {
            alert(data.error || "Something went wrong");
        }
    }
</script>

    </body>
</html>
