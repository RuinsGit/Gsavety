/**
 * Sipariş API kullanım örneği
 * Bu dosya, sipariş API'lerinin nasıl kullanılacağını gösterir.
 */

// Sipariş oluşturma örneği
function createOrder(orderData) {
    return fetch('/api/orders', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Başarılı sipariş işlemi
            console.log('Sipariş başarıyla oluşturuldu:', data);
            // Sepeti temizle ve teşekkür sayfasına yönlendir
            clearCart();
            window.location.href = '/thank-you?order=' + data.data.order.id;
        } else {
            // Hata durumunda
            console.error('Sipariş oluşturulurken hata:', data.message, data.errors);
            showErrorMessages(data.errors);
        }
    })
    .catch(error => {
        console.error('Bağlantı hatası:', error);
        alert('Bağlantı hatası. Lütfen daha sonra tekrar deneyin.');
    });
}

// Sepet boşaltma fonksiyonu
function clearCart() {
    localStorage.removeItem('cart');
    // Eğer oturum kullanıyorsanız, oturum sepetini de temizleyin
    fetch('/api/cart/clear', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });
}

// Hata mesajlarını gösterme
function showErrorMessages(errors) {
    // Form alanlarındaki hataları göster
    Object.keys(errors).forEach(field => {
        const inputElement = document.querySelector(`[name="${field}"]`);
        if (inputElement) {
            inputElement.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = errors[field][0];
            inputElement.parentNode.appendChild(errorDiv);
        }
    });
}

// Checkout formu gönderim işlemi
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Formdaki hata mesajlarını temizle
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(el => {
                el.remove();
            });
            
            // Form verilerini al
            const formData = new FormData(checkoutForm);
            
            // Sepet verilerini al
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            
            // API isteği için sipariş verilerini hazırla
            const orderData = {
                first_name: formData.get('first_name'),
                last_name: formData.get('last_name'),
                email: formData.get('email'),
                phone: formData.get('phone'),
                address: formData.get('address'),
                city: formData.get('city'),
                state: formData.get('state'),
                comment: formData.get('comment'),
                cart_items: cart.map(item => ({
                    product_id: item.product_id,
                    quantity: item.quantity,
                    price: item.price,
                    color_id: item.color_id,
                    size_id: item.size_id
                }))
            };
            
            // Kullanıcı oturum açmışsa user_id ekle
            if (window.userId) {
                orderData.user_id = window.userId;
            }
            
            // Sipariş oluştur
            createOrder(orderData);
        });
    }
});

// Sipariş detaylarını gösterme örneği
function getOrderDetails(orderId) {
    fetch(`/api/orders/${orderId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Sipariş detaylarını göster
            console.log('Sipariş detayları:', data.data);
            displayOrderDetails(data.data);
        } else {
            console.error('Sipariş detayları alınamadı.');
        }
    })
    .catch(error => {
        console.error('Bağlantı hatası:', error);
    });
}

// Sipariş detaylarını gösterme
function displayOrderDetails(order) {
    // Bu fonksiyon, sipariş detaylarını sayfada göstermek için kullanılabilir
    // Örnek için burada basit bir yerleştirme yapıyoruz
    const orderDetailsContainer = document.getElementById('order-details');
    
    if (orderDetailsContainer) {
        // Sipariş özeti
        let orderSummary = `
            <div class="order-summary">
                <h4>Sipariş #${order.order_number}</h4>
                <p><strong>Tarih:</strong> ${new Date(order.created_at).toLocaleDateString()}</p>
                <p><strong>Toplam:</strong> ${order.total_amount} ₼</p>
                <p><strong>Durum:</strong> 
                    <span class="badge ${getBadgeClass(order.status)}">
                        ${getStatusText(order.status)}
                    </span>
                </p>
                <p><strong>Ödeme Durumu:</strong> 
                    <span class="badge ${getPaymentBadgeClass(order.payment_status)}">
                        ${getPaymentStatusText(order.payment_status)}
                    </span>
                </p>
            </div>
        `;
        
        // Müşteri bilgileri
        let customerInfo = `
            <div class="customer-info mt-4">
                <h5>Müşteri Bilgileri</h5>
                <p><strong>Ad Soyad:</strong> ${order.first_name} ${order.last_name}</p>
                <p><strong>E-posta:</strong> ${order.email}</p>
                <p><strong>Telefon:</strong> ${order.phone}</p>
                <p><strong>Adres:</strong> ${order.address}, ${order.city} ${order.state || ''}</p>
            </div>
        `;
        
        // Sipariş öğeleri
        let orderItems = `
            <div class="order-items mt-4">
                <h5>Sipariş Öğeleri</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ürün</th>
                            <th>Fiyat</th>
                            <th>Adet</th>
                            <th>Toplam</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        order.items.forEach(item => {
            orderItems += `
                <tr>
                    <td>
                        ${item.product_name}
                        ${item.color_name ? `<br><small>Renk: ${item.color_name}</small>` : ''}
                        ${item.size_name ? `<br><small>Beden: ${item.size_name}</small>` : ''}
                    </td>
                    <td>${item.price} ₼</td>
                    <td>${item.quantity}</td>
                    <td>${item.total} ₼</td>
                </tr>
            `;
        });
        
        orderItems += `
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Toplam:</th>
                            <th>${order.total_amount} ₼</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        `;
        
        // Not
        let noteSection = order.comment ? `
            <div class="order-note mt-4">
                <h5>Sipariş Notu</h5>
                <p>${order.comment}</p>
            </div>
        ` : '';
        
        // Tümünü bir araya getir
        orderDetailsContainer.innerHTML = orderSummary + customerInfo + orderItems + noteSection;
    }
}

// Durum metin ve renk sınıflarını alma
function getStatusText(status) {
    switch(status) {
        case 'pending': return 'Beklemede';
        case 'processing': return 'İşleniyor';
        case 'completed': return 'Tamamlandı';
        case 'cancelled': return 'İptal Edildi';
        default: return status;
    }
}

function getBadgeClass(status) {
    switch(status) {
        case 'pending': return 'bg-warning';
        case 'processing': return 'bg-info';
        case 'completed': return 'bg-success';
        case 'cancelled': return 'bg-danger';
        default: return 'bg-secondary';
    }
}

function getPaymentStatusText(status) {
    switch(status) {
        case 'pending': return 'Beklemede';
        case 'paid': return 'Ödendi';
        case 'failed': return 'Başarısız';
        default: return status;
    }
}

function getPaymentBadgeClass(status) {
    switch(status) {
        case 'pending': return 'bg-warning';
        case 'paid': return 'bg-success';
        case 'failed': return 'bg-danger';
        default: return 'bg-secondary';
    }
}

// Kullanıcının siparişlerini getirme
function getUserOrders(userId) {
    fetch(`/api/orders/user/${userId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Kullanıcı siparişleri:', data.data);
            displayUserOrders(data.data);
        } else {
            console.error('Kullanıcı siparişleri alınamadı.');
        }
    })
    .catch(error => {
        console.error('Bağlantı hatası:', error);
    });
}

// Kullanıcı siparişlerini gösterme
function displayUserOrders(orders) {
    const ordersContainer = document.getElementById('user-orders');
    
    if (ordersContainer) {
        if (orders.length === 0) {
            ordersContainer.innerHTML = '<div class="alert alert-info">Henüz siparişiniz bulunmamaktadır.</div>';
            return;
        }
        
        let html = `
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sipariş No</th>
                            <th>Tarih</th>
                            <th>Toplam</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        orders.forEach(order => {
            html += `
                <tr>
                    <td>${order.order_number}</td>
                    <td>${new Date(order.created_at).toLocaleDateString()}</td>
                    <td>${order.total_amount} ₼</td>
                    <td>
                        <span class="badge ${getBadgeClass(order.status)}">
                            ${getStatusText(order.status)}
                        </span>
                    </td>
                    <td>
                        <a href="javascript:void(0)" onclick="getOrderDetails(${order.id})" class="btn btn-sm btn-info">
                            Detaylar
                        </a>
                    </td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        ordersContainer.innerHTML = html;
    }
}

// Belirli bir kullanıcının siparişlerini yükle
document.addEventListener('DOMContentLoaded', function() {
    const userOrdersContainer = document.getElementById('user-orders');
    
    if (userOrdersContainer && window.userId) {
        getUserOrders(window.userId);
    }
    
    // Sipariş detayları sayfasında sipariş yükle
    const orderDetailsContainer = document.getElementById('order-details');
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order');
    
    if (orderDetailsContainer && orderId) {
        getOrderDetails(orderId);
    }
}); 