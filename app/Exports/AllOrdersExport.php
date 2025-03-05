<?php
// app/Exports/AllOrdersExport.php
namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class AllOrdersExport implements FromCollection, WithEvents, WithTitle
{
    use Exportable;

    public function title(): string
    {
        return 'Tüm Siparişler';
    }

    public function collection()
    {
        $collection = collect();

        // Ana sipariş başlıkları - İndirim Yüzdesi sütunu eklendi
        $collection->push([
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
            'İndirim Yüzdesi (%)', // Yeni sütun eklendi
        ]);

        $orders = Order::with('items')
            ->where('payment_3d_success', 'yes')
            ->where('payment_pos_success', 'yes')
            ->orderBy('created_at', 'desc')
            ->get();
        $orderCount = 0;

        foreach($orders as $order) {
            $orderCount++;
            $isEvenOrder = $orderCount % 2 === 0;

            // Ürünlerin toplam tutarını hesapla
            $itemsTotalAmount = 0;
            foreach($order->items as $item) {
                $quantity = $item->quantity ?? 1;
                $price = $item->price ?? $item->unit_price ?? 0;
                $itemsTotalAmount += ($quantity * $price);
            }

            // İndirim yüzdesini hesapla
            $discountPercentage = 0;
            if ($itemsTotalAmount > 0 && $itemsTotalAmount != $order->sale_amount) {
                // İndirim veya artış yüzdesi (negatif değer indirim demektir)
                $difference = $order->sale_amount - $itemsTotalAmount;
                $discountPercentage = ($difference / $itemsTotalAmount) * 100;
            }

            // Ana Sipariş bilgileri
            $collection->push([
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
                ($order->payment_3d_success == 'yes' && $order->payment_pos_success == 'yes') ? 'Başarılı' : 'Başarısız',
                // İndirim yüzdesi - negatifse indirim var, pozitifse fiyat artışı var
                $discountPercentage != 0 ? number_format($discountPercentage, 2, ',', '.') : '0',
            ]);

            // Sipariş ve ürün arasında boş satır
            $collection->push([]);

            // Eğer siparişte ürün varsa, ürün bilgilerini ekle
            if(count($order->items) > 0) {
                // Ürün başlıkları
                $collection->push([
                    'Ürün ID',
                    'Ürün Adı',
                    'Ürün Adedi',
                    'Birim Fiyat (₺)',
                    'Satır Toplamı (₺)',
                    '', '', '', '', '', '', '', '', '', '', '' // Boş alanlar (bir fazla)
                ]);

                $totalQuantity = 0;
                $totalItemAmount = 0;

                // Ürün detayları
                foreach($order->items as $item) {
                    $quantity = $item->quantity ?? 1;
                    $price = $item->price ?? $item->unit_price ?? 0;
                    $lineTotal = $quantity * $price;

                    $totalQuantity += $quantity;
                    $totalItemAmount += $lineTotal;

                    $collection->push([
                        $item->id ?? '',
                        $item->name ?? $item->product_name ?? 'Belirtilmemiş',
                        $quantity,
                        number_format($price, 2, ',', '.'),
                        number_format($lineTotal, 2, ',', '.'),
                        '', '', '', '', '', '', '', '', '', '', '' // Boş alanlar (bir fazla)
                    ]);
                }

                // Toplam satırını ekle
                $collection->push([
                    '', // Boş
                    'TOPLAM', // Toplam başlığı
                    $totalQuantity, // Toplam adet
                    '', // Boş
                    number_format($totalItemAmount, 2, ',', '.'), // Toplam tutar
                    '', '', '', '', '', '', '', '', '', '', '' // Boş alanlar (bir fazla)
                ]);

                // Son tutar ve indirim satırı
                if ($discountPercentage != 0) {
                    $discountText = $discountPercentage < 0 ? 'İNDİRİM SONRASI TUTAR' : 'EK ÜCRETLER SONRASI TUTAR';
                    $collection->push([
                        '', // Boş
                        $discountText, // İndirim veya ek ücret açıklaması
                        '', // Boş
                        '', // Boş
                        number_format($order->sale_amount, 2, ',', '.'), // Son tutar
                        '% ' . number_format(abs($discountPercentage), 2, ',', '.') . ($discountPercentage < 0 ? ' İNDİRİM' : ' ARTIŞ'), // İndirim yüzdesi açıklaması
                        '', '', '', '', '', '', '', '', '', '' // Boş alanlar
                    ]);
                }
            }

            // Siparişler arasında ayırıcı satır
            $collection->push(['AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ', 'AYIRAÇ']);
        }

        return $collection;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $worksheet = $event->sheet->getDelegate();
                $lastRow = $worksheet->getHighestRow();

                // Ana başlık satırını ayarla
                $event->sheet->getStyle('A1:P1')->getFont()->setBold(true);
                $event->sheet->getStyle('A1:P1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF4472C4'); // Koyu mavi
                $event->sheet->getStyle('A1:P1')->getFont()->getColor()
                    ->setARGB('FFFFFFFF'); // Beyaz yazı

                // Başlık satırını dondur
                $event->sheet->freezePane('A2');

                // Excel'in otomatik filtreleme özelliğini başlık satırına ekle
                $event->sheet->setAutoFilter('A1:P1');

                // Hücreleri formatlamak için değişkenler
                $orderCount = 0;
                $currentRow = 2; // Başlık satırından sonra başla

                while ($currentRow <= $lastRow) {
                    $cellValue = $worksheet->getCell('A' . $currentRow)->getValue();

                    // Ayıraç satırı
                    if($cellValue == 'AYIRAÇ') {
                        $event->sheet->getStyle('A'.$currentRow.':P'.$currentRow)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFB4C6E7'); // Orta mavi

                        // Ayıraç metnini gizle
                        $event->sheet->getStyle('A'.$currentRow.':P'.$currentRow)->getFont()
                            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));

                        // Kalın çizgi ekle
                        $event->sheet->getStyle('A'.$currentRow.':P'.$currentRow)->getBorders()->getAllBorders()
                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF4472C4')); // Koyu mavi kenarlık

                        $currentRow++;
                        continue;
                    }

                    // Ana Sipariş Satırları
                    if(!empty($cellValue) && $cellValue != 'Ürün ID' && $cellValue != 'TOPLAM') {
                        $orderCount++;
                        $isEvenOrder = $orderCount % 2 === 0;

                        // Çift ve tek siparişler için farklı renkler
                        if($isEvenOrder) {
                            // Çift sipariş - Kırmızı tonları
                            $event->sheet->getStyle('A'.$currentRow.':P'.$currentRow)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFF8CBAD'); // Açık kırmızı

                            // Dış kenarlık - kalın kırmızı
                            $event->sheet->getStyle('A'.$currentRow.':P'.$currentRow)->getBorders()->getAllBorders()
                                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                                ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFE74C3C')); // Kırmızı kenarlık
                        } else {
                            // Tek sipariş - Yeşil tonları
                            $event->sheet->getStyle('A'.$currentRow.':P'.$currentRow)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFD5E8D4'); // Açık yeşil

                            // Dış kenarlık - kalın yeşil
                            $event->sheet->getStyle('A'.$currentRow.':P'.$currentRow)->getBorders()->getAllBorders()
                                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                                ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF2ECC71')); // Yeşil kenarlık
                        }

                        // İndirim varsa renklendir
                        $discountValue = $worksheet->getCell('P' . $currentRow)->getValue();
                        if (!empty($discountValue) && $discountValue != '0') {
                            // Negatif değer (indirim) için yeşil arka plan, pozitif değer (artış) için kırmızı
                            if (strpos($discountValue, '-') === 0) {
                                $event->sheet->getStyle('P'.$currentRow)->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FF92D050'); // Yeşil (indirim)
                            } else {
                                $event->sheet->getStyle('P'.$currentRow)->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FFFF5050'); // Kırmızı (artış)
                            }
                            $event->sheet->getStyle('P'.$currentRow)->getFont()->setBold(true);
                        }
                    }

                    // Ürün Başlık Satırı
                    if($cellValue == 'Ürün ID') {
                        $isEvenOrder = $orderCount % 2 === 0;

                        if($isEvenOrder) {
                            // Çift sipariş için ürün başlığı - kırmızı tonları
                            $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getFont()->setBold(true);
                            $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFC65E37'); // Orta kırmızı
                            $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getFont()->getColor()
                                ->setARGB('FFFFFFFF'); // Beyaz yazı
                        } else {
                            // Tek sipariş için ürün başlığı - yeşil tonları
                            $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getFont()->setBold(true);
                            $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FF70AD47'); // Orta yeşil
                            $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getFont()->getColor()
                                ->setARGB('FFFFFFFF'); // Beyaz yazı
                        }
                    }

                    // Ürün Detay Satırları
                    if(empty($cellValue) && !empty($worksheet->getCell('B' . $currentRow)->getValue()) &&
                        $worksheet->getCell('B' . $currentRow)->getValue() != 'Ürün Adı' &&
                        $worksheet->getCell('B' . $currentRow)->getValue() != 'TOPLAM' &&
                        $worksheet->getCell('B' . $currentRow)->getValue() != 'İNDİRİM SONRASI TUTAR' &&
                        $worksheet->getCell('B' . $currentRow)->getValue() != 'EK ÜCRETLER SONRASI TUTAR' &&
                        $worksheet->getCell('B' . $currentRow)->getValue() != 'AYIRAÇ') {

                        $isEvenOrder = $orderCount % 2 === 0;

                        if($isEvenOrder) {
                            // Çift sipariş için ürün detayları - çok açık kırmızı
                            $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFFBE5D6'); // Çok açık kırmızı
                        } else {
                            // Tek sipariş için ürün detayları - çok açık yeşil
                            $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFF1F8EF'); // Çok açık yeşil
                        }
                    }

                    // Toplam Satırı
                    if(!empty($worksheet->getCell('B' . $currentRow)->getValue()) &&
                        $worksheet->getCell('B' . $currentRow)->getValue() == 'TOPLAM') {

                        $isEvenOrder = $orderCount % 2 === 0;

                        // Toplam satırını kalın yap
                        $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getFont()->setBold(true);

                        if($isEvenOrder) {
                            // Çift sipariş için toplam satırı - açık kırmızı
                            $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFF4B084'); // Açık kırmızı
                        } else {
                            // Tek sipariş için toplam satırı - açık yeşil
                            $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFA8D08D'); // Açık yeşil
                        }

                        // Toplam satırı için çift çizgi
                        $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getBorders()->getTop()
                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                    }

                    // İndirim Sonrası Tutar Satırı
                    if(!empty($worksheet->getCell('B' . $currentRow)->getValue()) &&
                        ($worksheet->getCell('B' . $currentRow)->getValue() == 'İNDİRİM SONRASI TUTAR' ||
                            $worksheet->getCell('B' . $currentRow)->getValue() == 'EK ÜCRETLER SONRASI TUTAR')) {

                        $isIndirim = $worksheet->getCell('B' . $currentRow)->getValue() == 'İNDİRİM SONRASI TUTAR';

                        // İndirim satırını kalın yap
                        $event->sheet->getStyle('A'.$currentRow.':F'.$currentRow)->getFont()->setBold(true);

                        if ($isIndirim) {
                            // İndirim için yeşil tonlar
                            $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFC6E0B4'); // Yeşil ton

                            $event->sheet->getStyle('F'.$currentRow)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FF92D050'); // Daha koyu yeşil
                        } else {
                            // Ek ücretler için kırmızı tonlar
                            $event->sheet->getStyle('A'.$currentRow.':E'.$currentRow)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFFF9999'); // Kırmızı ton

                            $event->sheet->getStyle('F'.$currentRow)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFFF5050'); // Daha koyu kırmızı
                        }

                        // İndirim satırı için çizgiler
                        $event->sheet->getStyle('A'.$currentRow.':F'.$currentRow)->getBorders()->getAllBorders()
                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $event->sheet->getStyle('A'.$currentRow.':F'.$currentRow)->getBorders()->getBottom()
                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
                    }

                    $currentRow++;
                }

                // Sütun genişliklerini ayarla
                foreach(range('A', 'P') as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Tüm metin içeriğini ortalanmış yap
                $event->sheet->getStyle('A1:P'.$lastRow)->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // Tüm içeriği çerçevele
                $borderStyle = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['argb' => 'FF4472C4'], // Koyu mavi kenarlık
                        ],
                    ],
                ];

                $event->sheet->getStyle('A1:P'.$lastRow)->applyFromArray($borderStyle);
            },
        ];
    }
}
