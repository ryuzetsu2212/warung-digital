<?php

namespace App\Livewire\Staff;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class MenuStock extends StaffDashboardBase
{
    use WithFileUploads;

    // Properties untuk form tambah/edit menu
    public $showModal = false;
    public $isEditing = false;
    public $editingProductId = null;
    public $nama = '';
    public $kategori = '';
    public $harga = '';
    public $image_url = '';
    public $image_file = null;
    public $is_available = true;
    
    // Property untuk konfirmasi hapus
    public $showDeleteConfirm = false;
    public $deletingProductId = null;
    public $deletingProductName = '';

    protected $rules = [
        'nama' => 'required|string|max:255',
        'kategori' => 'required|string|max:100',
        'harga' => 'required|numeric|min:0',
        'image_url' => 'nullable|string',
        'image_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:10240',
        'is_available' => 'boolean',
    ];

    public function openCreateModal()
    {
        if (!$this->isLoggedIn) {
            return;
        }

        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function openEditModal($productId)
    {
        if (!$this->isLoggedIn) {
            return;
        }

        // Validate productId
        if (!is_numeric($productId) || $productId <= 0) {
            $this->errorMessage = 'ID produk tidak valid.';
            return;
        }

        $product = Product::find($productId);
        
        if (!$product) {
            $this->errorMessage = 'Produk tidak ditemukan.';
            return;
        }

        $this->editingProductId = $product->id;
        $this->nama = $product->nama;
        $this->kategori = $product->kategori; // Keep lowercase as stored in DB, will be auto-selected in view
        $this->harga = $product->harga;
        $this->image_url = $product->image_url;
        $this->is_available = $product->is_available;
        $this->showModal = true;
        $this->isEditing = true;
    }

    public function saveProduct()
    {
        if (!$this->isLoggedIn) {
            return;
        }

        $this->validate();

        try {
            $data = [
                'nama' => $this->nama,
                'kategori' => strtolower($this->kategori), // Convert to lowercase for ENUM
                'harga' => $this->harga,
                'is_available' => $this->is_available,
            ];

            // Handle image upload
            if ($this->image_file) {
                try {
                    $path = $this->image_file->store('menu-images', 'public');
                    $data['image_url'] = Storage::url($path);
                } catch (\Exception $e) {
                    $this->errorMessage = 'Gagal upload gambar: ' . $e->getMessage();
                    return;
                }
            } elseif ($this->image_url) {
                $data['image_url'] = $this->image_url;
            }

            if ($this->isEditing) {
                $product = Product::find($this->editingProductId);
                if (!$product) {
                    $this->errorMessage = 'Produk tidak ditemukan.';
                    return;
                }
                
                $product->update($data);
                $this->successMessage = "Menu \"{$this->nama}\" berhasil diperbarui.";
            } else {
                Product::create($data);
                $this->successMessage = "Menu \"{$this->nama}\" berhasil ditambahkan.";
            }

            $this->closeModal();
        } catch (\Exception $e) {
            $this->errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }

    public function confirmDelete($productId)
    {
        if (!$this->isLoggedIn) {
            return;
        }

        // Validate productId
        if (!is_numeric($productId) || $productId <= 0) {
            $this->errorMessage = 'ID produk tidak valid.';
            return;
        }

        $product = Product::find($productId);
        
        if (!$product) {
            $this->errorMessage = 'Produk tidak ditemukan.';
            return;
        }

        $this->deletingProductId = $product->id;
        $this->deletingProductName = $product->nama;
        $this->showDeleteConfirm = true;
    }

    public function deleteProduct()
    {
        if (!$this->isLoggedIn) {
            return;
        }

        if (!$this->deletingProductId) {
            $this->errorMessage = 'ID produk tidak valid.';
            return;
        }

        try {
            $product = Product::find($this->deletingProductId);
            
            if (!$product) {
                $this->errorMessage = 'Produk tidak ditemukan.';
                return;
            }

            $productName = $product->nama;
            $product->delete();

            $this->successMessage = "Menu \"{$productName}\" berhasil dihapus.";
            $this->closeDeleteConfirm();
        } catch (\Exception $e) {
            $this->errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeDeleteConfirm()
    {
        $this->showDeleteConfirm = false;
        $this->deletingProductId = null;
        $this->deletingProductName = '';
    }

    private function resetForm()
    {
        $this->editingProductId = null;
        $this->nama = '';
        $this->kategori = '';
        $this->harga = '';
        $this->image_url = '';
        $this->image_file = null;
        $this->is_available = true;
        $this->resetErrorBag();
    }

    public function toggleProductAvailability($productId)
    {
        if (!$this->isLoggedIn) {
            return;
        }

        // Validate productId
        if (!is_numeric($productId) || $productId <= 0) {
            $this->errorMessage = 'ID produk tidak valid.';
            return;
        }

        $product = Product::find($productId);
        
        if (!$product) {
            $this->errorMessage = 'Produk tidak ditemukan.';
            return;
        }

        $product->is_available = !$product->is_available;
        $product->save();

        $statusText = $product->is_available ? 'TERSEDIA (Ditampilkan ke Pelanggan)' : 'HABIS / KOSONG (Disembunyikan dari Pelanggan)';
        $this->successMessage = "Status menu \"" . htmlspecialchars($product->nama, ENT_QUOTES, 'UTF-8') . "\" berhasil diubah menjadi: {$statusText}";
    }

    public function clearMessage()
    {
        $this->successMessage = '';
    }

    public function render()
    {
        $allProducts = collect();
        $completedTodayCount = 0;
        $revenueToday = 0;

        if ($this->isLoggedIn) {
            $allProducts = Product::all();

            $completedTodayCount = Order::where('status', 'selesai')
                ->where('status_pembayaran', 'lunas')
                ->whereDate('created_at', today())
                ->count();

            $completedOrders = Order::with('orderItems.product')
                ->where('status', 'selesai')
                ->where('status_pembayaran', 'lunas')
                ->whereDate('created_at', today())
                ->get();

            foreach ($completedOrders as $ord) {
                foreach ($ord->orderItems as $item) {
                    if ($item->product && $item->status_item !== 'dibatalkan') {
                        $revenueToday += $item->product->harga * $item->qty;
                    }
                }
            }
        }

        return view('livewire.staff.menu-stock', [
            'allProducts' => $allProducts,
            'completedTodayCount' => $completedTodayCount,
            'revenueToday' => $revenueToday,
        ]);
    }
}