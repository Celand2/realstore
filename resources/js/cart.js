/**
 * Logique du panier modal côté client.
 */
document.addEventListener('DOMContentLoaded', () => {
    const saveUrl = document.body.dataset.cartSaveUrl || '/client/add-cart';
    const checkoutUrl = document.body.dataset.cartCheckoutUrl || '/cart/checkout';
    const cartPageUrl = document.body.dataset.cartPageUrl || '/client/cart';

    let cart = { products: [] };

    function logClientError(error, context = {}) {
        const message = error instanceof Error ? error.message : String(error);
        const stack = error instanceof Error ? error.stack : undefined;

        axios.post('/client/log', {
            message,
            stack,
            context: { ...context, url: window.location.href },
        }).catch(() => {});
    }

    try {
        cart.products = JSON.parse(document.body.dataset.cartItems || '[]');
    } catch (error) {
        logClientError(error, { operation: 'load_cart' });
    }

    const openBtn = document.getElementById('openModal');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const overlay = document.getElementById('modalOverlay');
    const modal = document.getElementById('modalContent');
    const cartItemsContainer = document.getElementById('cartItems');
    const cartItemTemplate = document.getElementById('cartItemTemplate');
    const productNumber = document.getElementById('productNumber');
    const btnSave = document.getElementById('btnSave');
    const modalAvailable = overlay && modal && cartItemsContainer && cartItemTemplate;

    function showNotification(message, type = 'info') {
        const existing = document.getElementById('cart-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.id = 'cart-toast';
        toast.className = `fixed right-4 top-4 z-50 rounded-md px-4 py-3 text-sm font-medium text-white shadow-lg ${
            type === 'error' ? 'bg-red-600' : type === 'success' ? 'bg-emerald-600' : 'bg-slate-800'
        }`;
        toast.textContent = message;

        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    function calculateTotal() {
        return cart.products.reduce(
            (sum, product) => sum + Number(product.price) * Number(product.quantity),
            0
        );
    }

    function updateTotal() {
        const totalEl = document.getElementById('total');
        if (totalEl) {
            totalEl.textContent = calculateTotal().toLocaleString('fr-FR') + ' FC';
        }

        if (productNumber) {
            productNumber.textContent = cart.products.reduce(
                (count, product, index, products) => products.findIndex((item) => item.id === product.id) === index
                    ? count + 1
                    : count,
                0
            );
        }
    }

    function addTocart(product) {
        const existing = cart.products.find((p) => p.id === product.id);

        if (existing) {
            existing.quantity += 1;
        } else {
            cart.products.push({
                id: product.id,
                name: product.title,
                price: product.price,
                description: product.description,
                quantity: 1,
            });
        }

        if (productNumber) {
            productNumber.textContent = cart.products.length;
        }

        updateTotal();
        showNotification(`${product.title} ajouté au panier`, 'success');
    }

    function saveCart() {
        if (!cart.products.length) {
            showNotification('Votre panier est vide.', 'error');
            return;
        }

        axios
            .post(saveUrl, { products: cart.products, sync: true })
            .then(() => {
                window.location.href = checkoutUrl;
            })
            .catch((error) => {
                logClientError(error, { operation: 'save_cart' });
                showNotification(
                    error.response?.data?.message || 'Erreur lors de la sauvegarde du panier',
                    'error'
                );
            });
    }

    function viewCart() {
        if (!modalAvailable) return;

        cartItemsContainer.innerHTML = '';

        if (!cart.products.length) {
            cartItemsContainer.innerHTML = '<p class="py-8 text-center text-gray-500">Votre panier est vide.</p>';
            updateTotal();
            return;
        }

        cart.products.forEach((product) => {
            const clone = cartItemTemplate.content.cloneNode(true);

            clone.querySelector('.productName').textContent = product.name;
            clone.querySelector('.productDescription').textContent = product.description;
            clone.querySelector('.productPrice').textContent =
                Number(product.price).toLocaleString('fr-FR') + ' FC / unité';
            clone.querySelector('.productQuantity').textContent = product.quantity;
            clone.querySelector('.totalPrice').textContent =
                (Number(product.price) * Number(product.quantity)).toLocaleString('fr-FR') + ' FC';

            const decreaseBtn = clone.querySelector('.decreaseBtn');
            const increaseBtn = clone.querySelector('.increaseBtn');
            const removeBtn = clone.querySelector('.removeBtn');

            if (decreaseBtn) {
                decreaseBtn.addEventListener('click', () => {
                    if (product.quantity > 1) {
                        product.quantity -= 1;
                        viewCart();
                    }
                });
            }

            if (increaseBtn) {
                increaseBtn.addEventListener('click', () => {
                    product.quantity += 1;
                    viewCart();
                });
            }

            if (removeBtn) {
                removeBtn.addEventListener('click', () => {
                    cart.products = cart.products.filter((p) => p.id !== product.id);
                    showNotification('Produit retiré du panier', 'info');
                    viewCart();
                });
            }

            cartItemsContainer.appendChild(clone);
        });

        updateTotal();
    }

    function openModal() {
        if (!modalAvailable) return;
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        modal.classList.remove('hidden', 'opacity-0', 'scale-95');
        modal.classList.add('opacity-100', 'scale-100');
        viewCart();
    }

    function closeModal() {
        if (!modalAvailable) return;
        modal.classList.remove('scale-100', 'opacity-100');
        modal.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
        }, 200);
    }

    const productModalOverlay = document.getElementById('productModalOverlay');
    const productModalContent = document.getElementById('productModalContent');
    const pdClose = document.getElementById('pd_close');

    function showDetails(product) {
        if (!productModalOverlay) return;
        document.getElementById('pd_name').textContent = product.title;
        document.getElementById('pd_category').textContent = product.category ? product.category.name : '';
        document.getElementById('pd_description').textContent = product.description || '';
        document.getElementById('pd_price').textContent = product.price + ' FC';
        document.getElementById('pd_image').src = product.image ? '/storage/' + product.image : '';

        productModalOverlay.classList.remove('hidden');
        productModalOverlay.classList.add('flex');
        setTimeout(() => {
            productModalContent.classList.remove('scale-95', 'opacity-0');
            productModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeProductModal() {
        if (!productModalOverlay) return;
        productModalContent.classList.remove('scale-100', 'opacity-100');
        productModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            productModalOverlay.classList.add('hidden');
            productModalOverlay.classList.remove('flex');
        }, 200);
    }

    if (pdClose) pdClose.addEventListener('click', closeProductModal);

    if (openBtn) {
        openBtn.addEventListener('click', () => {
            if (modalAvailable) {
                openModal();
            } else {
                window.location.href = cartPageUrl;
            }
        });
    }
    if (closeBtn && modalAvailable) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn && modalAvailable) cancelBtn.addEventListener('click', closeModal);
    if (btnSave && modalAvailable) btnSave.addEventListener('click', saveCart);

    updateTotal();
    window.addTocart = addTocart;
    window.showDetails = showDetails;
});
