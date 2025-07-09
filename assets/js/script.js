document.addEventListener('DOMContentLoaded', function () {
    // Confirm delete buttons
    const deleteButtons = document.querySelectorAll('.btn-danger');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to remove this item?')) {
                e.preventDefault();
            }
        });
    });

    // Handle Add to Cart buttons
    document.querySelectorAll('.add-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name') || 'Product';

            addToCart(productId, productName);
        });
    });

    // Show payment modal
    document.getElementById('placeOrderBtn')?.addEventListener('click', () => {
        document.getElementById('paymentModal').style.display = 'block';
    });
});

// Show popup message
function showPopup(message) {
    const popup = document.createElement('div');
    popup.innerText = message;
    popup.style.position = 'fixed';
    popup.style.top = '20px';
    popup.style.right = '20px';
    popup.style.backgroundColor = '#ff69b4';
    popup.style.color = '#fff';
    popup.style.padding = '15px 25px';
    popup.style.borderRadius = '8px';
    popup.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';
    popup.style.zIndex = '1000';
    popup.style.fontWeight = 'bold';
    popup.style.animation = 'fadeInOut 3s ease';
    document.body.appendChild(popup);

    setTimeout(() => {
        popup.remove();
    }, 3000);
}

// Add product to cart
function addToCart(productId, productName) {
    fetch(cart.php?add=${productId})
        .then(response => {
            if (response.ok) {
                showPopup(✅ ${productName} added to cart!);
            } else {
                showPopup(❌ Failed to add ${productName}.);
            }
        });
}

// Payment methods
function payWithABA() {
    window.location.href = "aba_pay.php";
}

function payWithCOD() {
    window.location.href = "cod_process.php";
}

// Optional: Popup animation
const style = document.createElement('style');
style.innerHTML = 
@keyframes fadeInOut {
    0% { opacity: 0; transform: translateY(-10px); }
    10% { opacity: 1; transform: translateY(0); }
    90% { opacity: 1; }
    100% { opacity: 0; transform: translateY(-10px); }
}
;
document.head.appendChild(style);
