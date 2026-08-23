<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Table;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Support\Str;

class CustomerMenu extends Component
{
    public $code;
    public $table;
    public $cart = [];
    public $successMessage = '';
    public $toastKey = 0;
    public $search = '';
    public $selectedCategory = 'all';
    public $sessionExpired = false;
    public $serviceClosed = false;

    public function mount($code)
    {
        $currentHour = now()->hour;
        // Shift Siang: 07:00 - 17:00 atau Shift Malam: 19:00 - 23:00
        $autoIsOpen = ($currentHour >= 7 && $currentHour < 17) || ($currentHour >= 19 && $currentHour < 23);
        
        // Check manual override from database
        $manualOverride = Setting::getValue('admin_manual_override', false);
        
        // Determine final status
        if ($manualOverride === 'closed') {
            // Force closed by admin
            $isOpen = false;
        } elseif ($manualOverride === true || $manualOverride === '1' || $manualOverride === 'true') {
            // Force open by admin
            $isOpen = true;
        } else {
            // Follow automatic schedule
            $isOpen = $autoIsOpen;
        }

        $this->code = $code;
        
        // Try to find table by short_code first, fallback to uuid for backward compatibility
        $this->table = Table::where('short_code', $code)
            ->orWhere('uuid', $code)
            ->firstOrFail();

        if (!$isOpen) {
            $this->serviceClosed = true;
            return;
        }

        $sessionKey = 'table_session_' . $this->table->id;
        $currentVisitorToken = session($sessionKey);

        // Check if table is already occupied by another session
        if (!empty($this->table->active_session_token) && $currentVisitorToken !== $this->table->active_session_token) {
            $this->sessionExpired = true;
            return;
        }

        // Generate new session token if table is available
        if (empty($this->table->active_session_token)) {
            $token = (string) Str::uuid();
            $this->table->update([
                'status_meja' => 'terisi',
                'active_session_token' => $token,
                'qr_available' => false,
            ]);
            session([$sessionKey => $token]);
        }
    }

    public function addToCart($productId)
    {
        if ($this->sessionExpired) {
            return;
        }

        // Validate productId is numeric and positive
        if (!is_numeric($productId) || $productId <= 0) {
            session()->flash('error', 'ID produk tidak valid.');
            return;
        }

        $product = Product::where('id', $productId)
            ->where('is_available', true)
            ->first();

        if (!$product) {
            session()->flash('error', 'Produk tidak tersedia.');
            return;
        }

        // Limit max quantity per item
        $maxQtyPerItem = 50;
        
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] >= $maxQtyPerItem) {
                session()->flash('error', 'Jumlah maksimal per item adalah ' . $maxQtyPerItem);
                return;
            }
            $this->cart[$productId]['qty']++;
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'nama' => $product->nama,
                'harga' => $product->harga,
                'kategori' => $product->kategori,
                'image_url' => $product->image_url,
                'qty' => 1,
            ];
        }

        $this->toastKey = microtime(true);
        $this->successMessage = 'Berhasil menambahkan ' . $product->nama . ' ke keranjang!';
    }

    public function updateQty($productId, $change)
    {
        if ($this->sessionExpired) {
            return;
        }

        // Validate inputs
        if (!is_numeric($productId) || !is_numeric($change)) {
            return;
        }

        // Limit change amount to prevent manipulation
        if (abs($change) > 10) {
            return;
        }

        $maxQtyPerItem = 50;

        if (isset($this->cart[$productId])) {
            $newQty = $this->cart[$productId]['qty'] + $change;
            
            if ($newQty <= 0) {
                unset($this->cart[$productId]);
            } elseif ($newQty <= $maxQtyPerItem) {
                $this->cart[$productId]['qty'] = $newQty;
            }
        }
    }

    public function removeFromCart($productId)
    {
        if (!is_numeric($productId)) {
            return;
        }
        
        unset($this->cart[$productId]);
    }

    public $metode_pembayaran = 'cash';

    public function checkout()
    {
        if ($this->sessionExpired) {
            session()->flash('error', 'Sesi meja ini telah berakhir atau sedang digunakan oleh pengunjung lain di restoran.');
            return;
        }

        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang masih kosong!');
            return;
        }

        // Validate cart items before checkout
        $validatedCart = [];
        $maxTotalItems = 100;
        $totalItems = 0;

        foreach ($this->cart as $productId => $item) {
            // Validate product ID and quantity
            if (!is_numeric($productId) || !is_numeric($item['qty']) || $item['qty'] <= 0 || $item['qty'] > 50) {
                session()->flash('error', 'Data keranjang tidak valid. Silakan muat ulang halaman.');
                return;
            }

            // Verify product still exists and available
            $product = Product::where('id', $productId)
                ->where('is_available', true)
                ->first();

            if (!$product) {
                session()->flash('error', 'Produk ' . ($item['nama'] ?? 'tidak diketahui') . ' tidak lagi tersedia.');
                return;
            }

            // Verify price hasn't been tampered with
            if ($item['harga'] != $product->harga) {
                session()->flash('error', 'Harga produk telah berubah. Silakan muat ulang halaman.');
                return;
            }

            $totalItems += $item['qty'];
            $validatedCart[$productId] = $item;
        }

        // Check total items limit
        if ($totalItems > $maxTotalItems) {
            session()->flash('error', 'Jumlah total item melebihi batas maksimal.');
            return;
        }

        // Sanitize customer name
        $customerName = session('customer_name', 'Pelanggan ' . $this->table->nomor_meja);
        $customerName = htmlspecialchars(strip_tags($customerName), ENT_QUOTES, 'UTF-8');
        $customerName = substr($customerName, 0, 100); // Limit length

        // Buat Order baru
        $order = Order::create([
            'table_id' => $this->table->id,
            'status' => 'menunggu',
            'customer_name' => $customerName,
        ]);

        // Buat Order Items dengan data yang sudah divalidasi
        foreach ($validatedCart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'qty' => $item['qty'],
                'kategori_item' => $item['kategori'],
                'status_item' => 'menunggu',
            ]);
        }

        // Kosongkan keranjang
        $this->cart = [];

        // Redirect ke halaman status pesanan
        return redirect()->route('customer.order-status', $order->id);
    }

    public function render()
    {
        $query = Product::query()->where('is_available', true);

        if (!empty($this->search)) {
            $query->where('nama', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedCategory !== 'all') {
            $query->where('kategori', $this->selectedCategory);
        }

        $products = $query->get();
        $makanan = $products->where('kategori', 'makanan');
        $minuman = $products->where('kategori', 'minuman');

        return view('livewire.customer-menu', [
            'makanan' => $makanan,
            'minuman' => $minuman,
            'allProducts' => $products,
        ]);
    }
}