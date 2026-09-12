<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use App\Livewire\Concerns\HandlesTableAutoStatus;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Livewire\Staff\StaffDashboardBase;

class StaffDashboard extends StaffDashboardBase
{
    use HandlesTableAutoStatus;
    public $tab, $staffName = '', $revenueFilter = 'hari ini', $tableFilter = '', $dateFilter = '', $ratingFilter = '';

    public function mount() { parent::mount(); $this->tab = request()->query('tab', 'antrean'); $this->staffName = Session::get('staff_username', 'Staff'); }
    public function updatedTab($value) { $this->js("window.history.replaceState(null, '', '?tab={$value}')"); }
    public function toggleProductAvailability($id) { if (!$this->isLoggedIn) return; $p=Product::findOrFail($id); $p->update(['is_available'=>!$p->is_available]); $this->successMessage='Status menu berhasil diubah.'; }
    public function clearMessage() { $this->successMessage = ''; }
    public function resetTableSession($id) { if (!$this->isLoggedIn) return; Table::findOrFail($id)->update(['status_meja'=>'tersedia','active_session_token'=>null]); $this->successMessage='Sesi meja berhasil direset.'; }
    public function setTableOccupied($id) { if (!$this->isLoggedIn) return; Table::findOrFail($id)->update(['status_meja'=>'terisi','active_session_token'=>(string)Str::uuid()]); $this->successMessage='Meja berhasil ditandai terisi.'; }
    public function updateItemStatus($id,$status) { if (!$this->isLoggedIn) return; $item=OrderItem::findOrFail($id); $item->update(['status_item'=>$status]); $order=$item->order; if($order && $order->orderItems()->where('status_item','!=','selesai')->count()===0)$order->update(['status'=>'selesai']); elseif($order && $status==='diproses' && $order->status==='menunggu')$order->update(['status'=>'diproses']); }

    public function render()
    {
        $empty=collect();
        if (!$this->isLoggedIn) return view('livewire.staff-dashboard',['makananItems'=>$empty,'minumanItems'=>$empty,'tables'=>$empty,'recentOrders'=>$empty,'completedTodayCount'=>0,'revenueToday'=>0,'allProducts'=>$empty]);
        $allProducts=Product::all();
        $makananItems=OrderItem::with(['order.table','product'])->where('kategori_item','makanan')->whereNotIn('status_item',['selesai','dibatalkan'])->orderBy('created_at')->get();
        $minumanItems=OrderItem::with(['order.table','product'])->where('kategori_item','minuman')->whereNotIn('status_item',['selesai','dibatalkan'])->orderBy('created_at')->get();
        $tables=Table::orderByRaw('CAST(nomor_meja AS INTEGER) ASC')->get();
        $query=Order::with(['table','orderItems.product'])->where('status','!=','dibatalkan')->latest(); $this->applyFilters($query); $recentOrders=$query->limit(20)->get();
        $completed=Order::where('status','selesai'); $this->applyFilters($completed); $completedTodayCount=$completed->count();
        $paid=Order::with('orderItems.product')->where('status','selesai')->where('status_pembayaran','lunas'); $this->applyFilters($paid); $revenueToday=$paid->get()->sum(fn($o)=>$o->total_harga);
        return view('livewire.staff-dashboard',compact('makananItems','minumanItems','tables','recentOrders','completedTodayCount','revenueToday','allProducts'));
    }
    private function applyFilters($q): void { if($this->revenueFilter==='hari ini')$q->whereDate('created_at',today()); elseif($this->revenueFilter==='minggu ini')$q->whereBetween('created_at',[now()->startOfWeek(),now()->endOfWeek()]); elseif($this->revenueFilter==='bulan ini')$q->whereMonth('created_at',now()->month)->whereYear('created_at',now()->year); elseif($this->revenueFilter==='tahun ini')$q->whereYear('created_at',now()->year); if($this->tableFilter!=='')$q->whereHas('table',fn($x)=>$x->where('nomor_meja',$this->tableFilter)); if($this->dateFilter!=='')$q->whereDate('created_at',$this->dateFilter); if($this->ratingFilter!=='')$q->where('rating',$this->ratingFilter); }
}
