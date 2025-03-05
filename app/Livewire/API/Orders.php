<?php
// app/Livewire/API/Orders.php
namespace App\Livewire\API;

use App\Models\Order;
use Livewire\Component;
use App\Exports\SuccessfulOrdersExport;
use App\Exports\UnsuccessfulOrdersExport;
use App\Exports\AllOrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class Orders extends Component
{
    public $orders = [];
    public $type = 'all'; // all, successful, unsuccessful

    public function mount($type = 'all')
    {
        $this->type = $type;
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $query = Order::with('items');

        if ($this->type === 'successful') {
            $query->where('payment_3d_success', 'yes')
                ->where('payment_pos_success', 'yes');
        } elseif ($this->type === 'unsuccessful') {
            $query->where(function($q) {
                $q->where('payment_3d_success', '!=', 'yes')
                    ->orWhere('payment_pos_success', '!=', 'yes');
            });
        }

        $this->orders = $query->get()->toArray();
        $this->dispatch('ordersUpdated');
    }

    public function getJsonOrders()
    {
        $query = Order::with('items');

        if ($this->type === 'successful') {
            $query->where('payment_3d_success', 'yes')
                ->where('payment_pos_success', 'yes');
        } elseif ($this->type === 'unsuccessful') {
            $query->where(function($q) {
                $q->where('payment_3d_success', '!=', 'yes')
                    ->orWhere('payment_pos_success', '!=', 'yes');
            });
        }

        return response()->json($query->get()->toArray());
    }

    public function downloadExcel()
    {
        $timestamp = now()->format('Y-m-d_H-i');

        if ($this->type === 'successful') {
            return Excel::download(new SuccessfulOrdersExport(), "basarili_siparisler_{$timestamp}.xlsx");
        } elseif ($this->type === 'unsuccessful') {
            return Excel::download(new UnsuccessfulOrdersExport(), "basarisiz_siparisler_{$timestamp}.xlsx");
        } else {
            return Excel::download(new AllOrdersExport(), "tum_siparisler_{$timestamp}.xlsx");
        }
    }

    public function render()
    {
        return view('livewire.api.orders');
    }
}
