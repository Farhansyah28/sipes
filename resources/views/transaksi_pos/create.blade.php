<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mesin Kasir (Point of Sales)') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="posSystem()">
        <div class="w-full sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div x-data="{ showModal: true }" x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" @click="show4Modal = false"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8211;</span>
                        <div x-show="showModal" x-transition.scale.80 class="inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6 text-center border-t-8 border-green-500">
                            <div>
                                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-50 mb-6 relative">
                                    <div class="absolute inset-0 rounded-full bg-green-500 animate-ping opacity-20"></div>
                                    <svg class="h-12 w-12 text-green-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <h3 class="text-2xl leading-6 font-black text-gray-900" id="modal-title">Transaksi Sukses!</h3>
                                    <div class="mt-3">
                                        <p class="text-sm text-gray-500">{{ session('success') }}</p>
                                        <p class="text-xs text-gray-400 mt-1">Stok dan kas telah disesuaikan.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-8 sm:mt-8 space-y-3">
                                @if(session('print_id'))
                                <a href="{{ route('transaksi_pos.print', session('print_id')) }}" target="_blank" class="inline-flex justify-center w-full rounded-xl border border-transparent shadow-md px-4 py-3 bg-gray-800 text-base font-bold text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 sm:text-sm transition-colors transform active:scale-95">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    CETAK STRUK (THERMAL)
                                </a>
                                @endif
                                <button type="button" @click="showModal = false" class="inline-flex justify-center w-full rounded-xl border border-gray-300 shadow-md px-4 py-3 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm transition-colors transform active:scale-95">
                                    LANJUT TRANSAKSI BERIKUTNYA
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Modal Konfirmasi Checkout -->
            <div x-show="showConfirmModal" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showConfirmModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" @click="showConfirmModal = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8211;</span>
                    <div x-show="showConfirmModal" x-transition.scale.80 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-t-8 border-indigo-500">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Konfirmasi Pembayaran</h3>
                                    <div class="mt-4">
                                        <div class="bg-gray-50 rounded-lg p-3 max-h-48 overflow-y-auto mb-4 border">
                                            <template x-for="(item, index) in cart" :key="index">
                                                <div class="flex justify-between text-sm py-1 border-b last:border-0 border-gray-200">
                                                    <div class="flex-1 text-gray-700" x-text="item.qty + 'x ' + item.nama"></div>
                                                    <div class="font-semibold text-gray-900" x-text="'Rp ' + formatRupiah(item.subtotal)"></div>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="flex justify-between items-center text-lg font-black text-gray-900 pt-2 border-t-2 border-dashed border-gray-300">
                                            <span>TOTAL</span>
                                            <span x-text="'Rp ' + formatRupiah(totalHarga)"></span>
                                        </div>
                                        <div class="mt-2 text-sm text-gray-500">
                                            Metode: <span class="font-bold" x-text="metode"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                            <button type="button" @click="processCheckout()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Proses Sekarang
                            </button>
                            <button type="button" @click="showConfirmModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Kiri: Katalog Barang -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-indigo-600">
                    <div class="p-6">
                        <div class="mb-4 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" x-model="search" @keydown.enter.prevent="scanBarcode()" class="pl-10 w-full shadow border rounded-lg py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Scan Barcode (Enter) atau Ketik Nama Barang..." autofocus autocomplete="off">
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-h-[600px] overflow-y-auto pr-2 pb-2">
                            <template x-for="item in filteredBarangs" :key="item.id">
                                <div @click="addToCart(item)" class="border rounded-xl p-4 cursor-pointer hover:bg-indigo-50 hover:border-indigo-400 transition-all shadow-sm hover:shadow active:scale-95 bg-white relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 bg-indigo-100 text-indigo-800 text-xs font-bold px-2 py-1 rounded-bl-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                        + Tambah
                                    </div>
                                    <div class="font-bold text-gray-800 text-sm mb-1 leading-tight" x-text="item.nama"></div>
                                    <div class="text-xs text-gray-400 font-mono mb-3" x-text="item.kode_barang"></div>
                                    <div class="flex justify-between items-end mt-auto">
                                        <span class="text-green-600 font-bold text-lg leading-none" x-text="'Rp ' + formatRupiah(item.harga_jual)"></span>
                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded font-medium" x-text="item.stok_total + ' stok'"></span>
                                    </div>
                                </div>
                            </template>
                            <template x-if="filteredBarangs.length === 0">
                                <div class="col-span-full py-10 text-center text-gray-400">
                                    Barang tidak ditemukan atau stok kosong.
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Keranjang Belanja -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-green-500 flex flex-col h-[calc(100vh-12rem)] min-h-[600px]">
                    <div class="p-6 flex flex-col h-full">
                        <h3 class="text-xl font-black text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Keranjang
                        </h3>
                        
                        <div class="flex-1 overflow-y-auto mb-4 border-t border-b py-2 space-y-3">
                            <template x-if="cart.length === 0">
                                <div class="text-center flex flex-col items-center justify-center h-full text-gray-400">
                                    <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    Belum ada barang di keranjang
                                </div>
                            </template>
                            
                            <template x-for="(item, index) in cart" :key="index">
                                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <div class="flex-1">
                                        <div class="font-bold text-gray-800 text-sm mb-1" x-text="item.nama"></div>
                                        <div class="flex items-center space-x-2">
                                            <div class="flex items-center border rounded bg-white">
                                                <button @click="decreaseQty(index)" class="px-2 py-1 text-gray-500 hover:text-red-500 hover:bg-gray-100 rounded-l">-</button>
                                                <span class="px-3 py-1 text-sm font-semibold border-l border-r" x-text="item.qty"></span>
                                                <button @click="increaseQty(index)" class="px-2 py-1 text-gray-500 hover:text-green-500 hover:bg-gray-100 rounded-r">+</button>
                                            </div>
                                            <span class="text-xs text-gray-500" x-text="'@ Rp ' + formatRupiah(item.harga_jual)"></span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end justify-between h-full space-y-2">
                                        <button @click="removeFromCart(index)" class="text-red-400 hover:text-red-600 p-1 rounded-full hover:bg-red-50 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        <div class="font-bold text-gray-800" x-text="'Rp ' + formatRupiah(item.subtotal)"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        <div class="pt-2">
                            <div class="flex justify-between items-center mb-6 bg-green-50 text-green-800 p-4 rounded-lg border border-green-200">
                                <span class="text-lg font-bold">TOTAL BAYAR</span>
                                <span class="text-2xl font-black tracking-tight" x-text="'Rp ' + formatRupiah(totalHarga)"></span>
                            </div>

                            <form action="{{ route('transaksi_pos.store') }}" method="POST" id="checkout-form">
                                @csrf
                                <input type="hidden" name="cart" :value="JSON.stringify(cart)">
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Metode Pembayaran</label>
                                    <select name="metode_pembayaran" x-model="metode" class="shadow border border-gray-300 rounded w-full py-2 px-3 text-gray-700 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="Tunai">ðŸ’µ Uang Tunai / Cash</option>
                                        <option value="Tabungan">ðŸ’³ Potong Tabungan Santri</option>
                                    </select>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Pilih Identitas Santri 
                                        <span x-show="metode === 'Tunai'" class="text-xs font-normal text-gray-500">(Opsional untuk Tunai)</span>
                                        <span x-show="metode === 'Tabungan'" class="text-xs font-normal text-red-500">*Wajib</span>
                                    </label>
                                    <select name="santri_id" class="shadow border border-gray-300 rounded w-full py-2 px-3 text-gray-700 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500" :required="metode === 'Tabungan'">
                                        <option value="">-- Pembeli Umum (Bukan Santri) --</option>
                                        @foreach($santris as $s)
                                            <option value="{{ $s->id }}">{{ $s->nama_lengkap }} (Saldo: Rp {{ number_format($s->tabungan->saldo ?? 0, 0, ',', '.') }})</option>
                                        @endforeach
                                    </select>
                                    <p x-show="metode === 'Tabungan'" class="text-xs text-red-500 mt-1 italic">*Pastikan saldo santri mencukupi total belanja.</p>
                                </div>

                                <button type="button" @click="submitCheckout()" class="w-full bg-green-600 hover:bg-green-700 text-white font-black text-lg py-4 px-4 rounded-xl transition-all transform active:scale-95 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2" :disabled="cart.length === 0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>PROSES CHECKOUT</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function posSystem() {
            return {
                barangs: @json($barangs),
                search: '',
                cart: [],
                metode: 'Tunai',
                showConfirmModal: false,
                
                get filteredBarangs() {
                    if (this.search === '') return this.barangs;
                    let keyword = this.search.toLowerCase();
                    return this.barangs.filter(b => 
                        b.nama.toLowerCase().includes(keyword) || 
                        b.kode_barang.toLowerCase() === keyword // exact match for barcode
                    );
                },

                get totalHarga() {
                    return this.cart.reduce((total, item) => total + item.subtotal, 0);
                },

                addToCart(item) {
                    let existing = this.cart.find(c => c.id === item.id);
                    if (existing) {
                        if (existing.qty < item.stok_total) {
                            existing.qty++;
                            existing.subtotal = existing.qty * existing.harga_jual;
                        } else {
                            alert('Maaf, stok ' + item.nama + ' tidak mencukupi (Sisa ' + item.stok_total + ').');
                        }
                    } else {
                        this.cart.push({
                            id: item.id,
                            nama: item.nama,
                            harga_jual: item.harga_jual,
                            qty: 1,
                            subtotal: item.harga_jual,
                            stok_total: item.stok_total
                        });
                    }
                    this.search = '';
                },

                increaseQty(index) {
                    let item = this.cart[index];
                    if (item.qty < item.stok_total) {
                        item.qty++;
                        item.subtotal = item.qty * item.harga_jual;
                    } else {
                        alert('Stok maksimum tercapai.');
                    }
                },

                decreaseQty(index) {
                    let item = this.cart[index];
                    if (item.qty > 1) {
                        item.qty--;
                        item.subtotal = item.qty * item.harga_jual;
                    } else {
                        this.removeFromCart(index);
                    }
                },

                scanBarcode() {
                    let item = this.barangs.find(b => b.kode_barang.toLowerCase() === this.search.toLowerCase());
                    if (item) {
                        this.addToCart(item);
                    } else {
                        // Play error sound or alert for not found barcode? Not strictly necessary for demo.
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID').format(angka);
                },

                submitCheckout() {
                    if (this.cart.length === 0) return;
                    this.showConfirmModal = true;
                },

                processCheckout() {
                    document.getElementById('checkout-form').submit();
                }
            }
        }
    </script>
</x-app-layout>
