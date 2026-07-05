# Phase 5: Infrastructure & Architecture Documentation

Dokumen ini menjelaskan secara mendalam tentang arsitektur, fondasi, dan standar operasional (*infrastructure skeletons & SOP*) yang telah disiapkan untuk **Fase 5** dari pengembangan sistem ERP Pesantren (SIPES). Fase ini bertujuan membangun jalur komunikasi data (API) ke layanan pihak ketiga dan aplikasi eksternal, yaitu: **API Mobile App**, **Payment Gateway**, dan **WhatsApp Gateway**.

Infrastruktur ini didesain menggunakan **Interface-driven Pattern** dan **Asynchronous Queuing** agar sistem mudah di-*scale*, vendor agnostik, dan tidak membebani memori utama (*zero-blocking*).

---

## 1. API Mobile App (Santri & Pengurus)

Infrastruktur API disiapkan menggunakan pendekatan pengelompokan (_grouping_) berdasarkan versi (v1) dan subjek pengguna (santri vs. pengurus). Hal ini dirancang agar API mudah diatur (scalable) dan mempermudah tim _frontend/mobile_ dalam membaca referensi.

### Struktur Endpoint (`routes/api.php`)
Semua *request* dialamatkan pada URL dengan awalan `/api/v1/...`

| Endpoint | Method | Keterangan | Middleware |
| --- | --- | --- | --- |
| `/api/v1/santri/login` | POST | Otentikasi Santri via NIS/No. HP | - |
| `/api/v1/santri/logout` | POST | Menggugurkan Token (Revoke) | `auth:sanctum` |
| `/api/v1/santri/profile` | GET | Mendapatkan detail profil Santri | `auth:sanctum` |
| `/api/v1/pengurus/login` | POST | Otentikasi Pengurus (Ustadz/Musyrif) | - |
| `/api/v1/pengurus/logout` | POST | Menggugurkan Token (Revoke) | `auth:sanctum` |

### Standar Respons JSON
Untuk menjaga konsistensi integrasi dengan tim Mobile (Flutter/Kotlin/React Native), setiap *controller* kelak wajib mengembalikan struktur JSON seperti ini:
```json
{
  "success": true,
  "message": "Pesan deskriptif aksi",
  "data": { ... } // Null jika error
}
```

### Skeletons Controller
Berada pada *namespace* `App\Http\Controllers\Api\V1\`
- **`Santri\AuthController.php`**: Mengelola *issue* token (via Laravel Sanctum).
- **`Pengurus\AuthController.php`**: Mengelola otentikasi pengurus/karyawan.
- **`Santri\ProfileController.php`**: Skeleton resource tertutup.

---

## 2. Payment Gateway Architecture

Pesantren mungkin akan menggunakan *Payment Gateway* yang berbeda-beda seiring waktu (contoh: awal menggunakan Midtrans, kelak berganti ke Tripay). Oleh karena itu, kita **TIDAK BOLEH** melakukan *hard-code* spesifik vendor di dalam *controller* tagihan/pembayaran.

### Pendekatan Agnostik (*Interface Pattern*)
Sebuah *Interface* mengikat fungsi wajib untuk setiap vendor pembayaran. Terdapat pada `app/Services/Payment/PaymentGatewayInterface.php`:
```php
namespace App\Services\Payment;

interface PaymentGatewayInterface {
    public function createInvoice(array $payload): array;
    public function checkStatus(string $referenceId): array;
    public function handleWebhook(array $payload): bool;
}
```

### Panduan Implementasi Vendor Baru
Ketika tim *developer* ingin mengimplementasikan **Midtrans**:
1. Buat *class* `App\Services\Payment\MidtransGateway implements PaymentGatewayInterface`.
2. Isi metode `createInvoice` dengan spesifikasi CURL ke Midtrans.
3. Di dalam `AppServiceProvider`, tambahkan *binding*:
   ```php
   $this->app->bind(PaymentGatewayInterface::class, function ($app) {
       $provider = config('payment.default');
       if ($provider === 'midtrans') return new MidtransGateway();
       if ($provider === 'tripay') return new TripayGateway();
   });
   ```

### Konfigurasi Credential
Lokasi: `config/payment.php`
Semua rahasia (*Keys*, *Merchant ID*) diletakkan di file `.env`. Jangan pernah menulis _Key_ langsung di dalam _controller_.

### Webhook & Security
Telah disediakan *route* `/api/webhooks/payment/{provider}`. Webhook **harus** memiliki validasi _signature/hash_ dari pihak Payment Gateway untuk mencegah peretasan status tagihan menjadi lunas.

---

## 3. WhatsApp Gateway Architecture

Notifikasi WhatsApp kepada wali santri (seperti pemberitahuan tunggakan, tagihan, atau nilai) harus diproses di belakang layar. Proses CURL ke server WhatsApp API sangat lambat dan berpotensi membuat *loading* aplikasi *error* (Time-out 504).

### File Konfigurasi
Terdapat di `config/whatsapp.php`, mendukung vendor _Wablas_, _Fonnte_, dan _RuangWA_.

### Pendekatan Agnostik (*Interface Pattern*)
Berada di `app/Services/WhatsApp/WhatsAppGatewayInterface.php`:
```php
namespace App\Services\WhatsApp;

interface WhatsAppGatewayInterface {
    public function sendMessage(string $to, string $message): array;
    public function sendDocument(string $to, string $fileUrl, string $caption = ''): array;
}
```

### Standar Pemanggilan Melalui Job Queue
Untuk mengirim pesan, *developer* **TIDAK BOLEH** memanggil Interface secara langsung dari Controller. Wajib menggunakan antrean (*Queue*).
File *Job* telah disediakan di `app/Jobs/SendWhatsAppMessage.php`.

**Cara Penggunaan di Controller/Observer:**
```php
use App\Jobs\SendWhatsAppMessage;

// Kirim pesan tanpa blocking response
SendWhatsAppMessage::dispatch(
    $santri->orangTua->no_hp, 
    "Tagihan bulanan sejumlah Rp 500.000 untuk ananda {$santri->nama} telah diterbitkan."
)->onQueue('whatsapp');
```

**Kelebihan Menggunakan Queue:**
- Respons web / portal menjadi *instant*.
- Terintegrasi dengan fitur otomatisasi *retry* milik Laravel. Jika vendor WA *down*, server lokal (ERP) akan menunda (*delay*) dan mencoba kirim ulang 5 menit kemudian, tanpa perlu intervensi manusia.

---

## 4. Persiapan Lanjutan Database (Sprint Berikutnya)

Meski belum diimplementasikan, tim *Database Administrator* wajib menyusun hal-hal berikut untuk mendukung infrastruktur di atas:

1. **Table `personal_access_tokens`**: Di-*generate* oleh Laravel Sanctum untuk mencatat *session login* multi-perangkat.
2. **Table `payment_logs`**: Untuk merekam seluruh lalu lintas *request & response* antara ERP dengan *Payment Gateway*. Berguna untuk audit transaksi gagal.
3. **Table `whatsapp_logs`**: Mencatat *history* pengiriman WA (Sukses / Gagal / Pending) agar pengurus pesantren bisa melacak keluhan wali murid yang "merasa tidak menerima notifikasi WA".

---

*Dokumen ini merupakan standar panduan (Blueprint) resmi untuk fase pengembangan Integrasi Pihak Ketiga (Fase 5) pada Sistem Informasi Pesantren (SIPES).*
