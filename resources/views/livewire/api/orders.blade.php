<!-- resources/views/livewire/api/orders.blade.php -->
<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">
                    @if($type === 'successful')
                        Başarılı Siparişler
                    @elseif($type === 'unsuccessful')
                        Başarısız Siparişler
                    @else
                        Tüm Siparişler
                    @endif
                </h4>
            </div>
            <div>
                <button wire:click="downloadExcel" class="btn btn-success">
                    <i class="bi bi-file-excel me-1"></i> Excel İndir
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Müşteri</th>
                        <th>Tutar</th>
                        <th>Ürün Sayısı</th>
                        <th>Tarih</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order['id'] }}</td>
                            <td>{{ $order['buyer_name'] ?? 'Belirtilmemiş' }}</td>
                            <td>{{ number_format($order['sale_amount'], 2) }} TL</td>
                            <td>{{ count($order['items']) }}</td>
                            <td>{{ date('d.m.Y H:i', strtotime($order['created_at'])) }}</td>
                            <td>
                                @if(($order['payment_3d_success'] ?? '') == 'yes' && ($order['payment_pos_success'] ?? '') == 'yes')
                                    <span class="badge bg-success">Başarılı</span>
                                @else
                                    <span class="badge bg-danger">Başarısız</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary">Detay</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Sipariş bulunamadı.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
