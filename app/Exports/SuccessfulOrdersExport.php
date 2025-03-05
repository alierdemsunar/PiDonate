<?php
// app/Exports/SuccessfulOrdersExport.php
namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SuccessfulOrdersExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return Order::query()
            ->with('items')
            ->where('payment_3d_success', 'yes')
            ->where('payment_pos_success', 'yes');
    }

    public function headings(): array
    {
        return [
            'Yardım Numarası',
            'İyiliksever',
            'IP Adresi',
            'TC Kimlik Numarası',
            'Telefon Numarası',
            'E-Posta Adresi',
            'Adres',
            'Yardım Tutarı (₺)',
            'Sepetteki Ürün Adedi',
            'POS İşlem Kodu',
            'POS Doğrulama Kodu',
            'Ödeme 3D (MPI) Durumu',
            'Ödeme VPOS Durumu',
            'Yardım Zamanı',
            'Ödeme Sonucu',
        ];
    }

    public function map($order): array
    {
        $status = ($order->payment_3d_success == 'yes' && $order->payment_pos_success == 'yes')
            ? 'Başarılı'
            : 'Başarısız';

        return [
            $order->order_uuid ?? 'Belirtilmemiş',
            $order->buyer_name ?? 'Belirtilmemiş',
            $order->buyer_ip ?? 'Belirtilmemiş',
            $order->identification_no ?? 'Belirtilmemiş',
            $order->phone_no ?? 'Belirtilmemiş',
            $order->email_address ?? 'Belirtilmemiş',
            $order->city ?? 'Belirtilmemiş',
            number_format($order->sale_amount, 2, ',', '.'),
            count($order->items),
            $order->payment_pos_transaction_id ?? 'Belirtilmemiş',
            $order->payment_pos_auth_code ?? 'Belirtilmemiş',
            $order->payment_3d_success == 'yes' ? 'Başarılı' : 'Başarısız',
            $order->payment_pos_success == 'yes' ? 'Başarılı' : 'Başarısız',
            $order->created_at->format('d/m/Y H:i:s'),
            $status,
        ];
    }}
