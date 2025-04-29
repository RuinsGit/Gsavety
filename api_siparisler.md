# Siparişler API Endpoint'leri

## Tüm Siparişleri Getir
- **URL**: `/api/orders`
- **Method**: `GET`
- **Filtre Parametreleri**: `type` (retail veya corporate)
- **Açıklama**: Tüm siparişleri listeler, yetki seviyesine göre filtreleme yapar:
  - Admin: Tüm siparişleri görür
  - Kullanıcı: Sadece kendi siparişlerini görür
- **Örnek İstek**:
  ```
  GET /api/orders?type=retail
  ```
- **Gerekli Header**: `Authorization: Bearer {token}`

## Sipariş Detayını Getir
- **URL**: `/api/orders/{id}`
- **Method**: `GET`
- **Açıklama**: Belirli bir siparişin detaylarını gösterir
- **Gerekli Header**: `Authorization: Bearer {token}`

## Kullanıcının Siparişlerini Getir
- **URL**: `/api/orders/user/{userId}`
- **Method**: `GET` 
- **Filtre Parametreleri**: `type` (retail veya corporate)
- **Açıklama**: Belirli bir kullanıcının siparişlerini getirir
- **Örnek İstek**:
  ```
  GET /api/orders/user/5?type=corporate
  ```
- **Gerekli Header**: `Authorization: Bearer {token}`

## Giriş Yapan Kullanıcının Siparişlerini Getir
- **URL**: `/api/orders/my-orders`
- **Method**: `GET`
- **Filtre Parametreleri**: `type` (retail veya corporate)
- **Açıklama**: Token sahibi kullanıcının siparişlerini getirir
- **Örnek İstek**:
  ```
  GET /api/orders/my-orders?type=retail
  ```
- **Gerekli Header**: `Authorization: Bearer {token}`

## Perakende Siparişleri Listele
- **URL**: `/api/orders/retail`
- **Method**: `GET`
- **Açıklama**: Sadece perakende tipi siparişleri listeler
- **Gerekli Header**: `Authorization: Bearer {token}`

## Kurumsal Siparişleri Listele
- **URL**: `/api/orders/corporate` 
- **Method**: `GET`
- **Açıklama**: Sadece kurumsal tipi siparişleri listeler
- **Gerekli Header**: `Authorization: Bearer {token}`

## Sipariş Durumunu Güncelle
- **URL**: `/api/orders/{id}/status`
- **Method**: `PUT`
- **Açıklama**: Bir siparişin durumunu günceller (Admin yetkisi gerekli)
- **Parametreler**: 
  ```json
  {
    "status": "processing" // pending, processing, completed, cancelled
  }
  ```
- **Gerekli Header**: `Authorization: Bearer {token}`

## Ödeme Durumunu Güncelle
- **URL**: `/api/orders/{id}/payment-status`
- **Method**: `PUT`
- **Açıklama**: Bir siparişin ödeme durumunu günceller (Admin yetkisi gerekli)
- **Parametreler**: 
  ```json
  {
    "payment_status": "paid" // pending, paid, failed
  }
  ```
- **Gerekli Header**: `Authorization: Bearer {token}`

## Sipariş Tipini Güncelle
- **URL**: `/api/orders/{id}/type`
- **Method**: `PUT`
- **Açıklama**: Bir siparişin tipini (perakende/kurumsal) günceller (Admin yetkisi gerekli)
- **Parametreler**: 
  ```json
  {
    "type": "corporate", // retail, corporate
    "company_name": "ABC Şirketi" // Kurumsal sipariş ise şirket adı
  }
  ```
- **Gerekli Header**: `Authorization: Bearer {token}` 