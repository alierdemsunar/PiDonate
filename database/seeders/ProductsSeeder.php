<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            [ 'code' => '153,014,027', 'name' => 'Trakya Birlik Pet Ayçiçek Yağı 5 lt', 'image' => 'trakya-birlik-pet-aycicek-yagi-5-lt.webp', 'price' => 379.25, 'sale_price' => 322.36 ],
            [ 'code' => '153,014,023', 'name' => 'Trakya Birlik Pet Ayçiçek Yağı 0,5 lt', 'image' => 'trakya-birlik-pet-aycicek-yagi-05-lt.webp', 'price' => 43.25, 'sale_price' => 36.76 ],
            [ 'code' => '153,014,028', 'name' => 'Trakya Birlik Teneke Ayçiçek Yağı 5 lt', 'image' => 'trakya-birlik-teneke-aycicek-yagi-5-lt.webp', 'price' => 402, 'sale_price' => 341.7 ],
            [ 'code' => '153,014,025', 'name' => 'Trakya Birlik Pet Ayçiçek Yağı 2 lt', 'image' => 'trakya-birlik-pet-aycicek-yagi-2-lt.webp', 'price' => 159.25, 'sale_price' => 135.36 ],
            [ 'code' => '153,014,024', 'name' => 'Trakya Birlik Pet Ayçiçek Yağı 1 lt', 'image' => 'trakya-birlik-pet-aycicek-yagi-1-lt.webp', 'price' => 82, 'sale_price' => 69.7 ],
            [ 'code' => '153,008,014', 'name' => 'Yeşilağaç Kooperatifi Tarhana 1000 gr', 'image' => 'yesilagac-koop-tarhana-1000-gr.webp', 'price' => 124.9, 'sale_price' => 106.17 ],
            [ 'code' => '153,008,015', 'name' => 'Yeşilağaç Kooperatifi Tarhana 500 gr', 'image' => 'yesilagac-koop-tarhana-500-gr.webp', 'price' => 69.9, 'sale_price' => 59.42 ],
            [ 'code' => '153,010,035', 'name' => 'Yeşilağaç Kooperatifi Sade Erişte 1000 gr', 'image' => 'yesilagac-koop-sade-eriste-1000-gr.webp', 'price' => 124.9, 'sale_price' => 106.17 ],
            [ 'code' => '153,010,036', 'name' => 'Yeşilağaç Kooperatifi Sade Erişte 500 gr', 'image' => 'yesilagac-koop-sade-eriste-500-gr.webp', 'price' => 69.9, 'sale_price' => 59.42 ],
            [ 'code' => '153,008,006', 'name' => 'Kızılcahamam Kooperatifi Tarhana 1000 gr', 'image' => 'kizilcahamam-koop-tarhana-1000-gr.webp', 'price' => 98.5, 'sale_price' => 83.73 ],
            [ 'code' => '153,010,012', 'name' => 'Kızılcahamam Kooperatifi Erişte 1000 gr', 'image' => 'kizilcahamam-koop-eriste-1000-gr.webp', 'price' => 98.5, 'sale_price' => 83.73 ],
            [ 'code' => '153,007,017', 'name' => 'Tirebolu Kooperatifi Keyif Çayı 500 gr', 'image' => 'tirebolu-koopkeyif-cay-500gr.webp', 'price' => 69.9, 'sale_price' => 59.42 ],
            [ 'code' => '153,007,018', 'name' => 'Tirebolu Kooperatifi Keyif Çayı 1000 gr', 'image' => 'tirebolu-koopkeyif-cay-1000gr.webp', 'price' => 176.75, 'sale_price' => 150.24 ],
            [ 'code' => '153,007,011', 'name' => 'Tirebolu Kooperatifi Özel Tirebolu Çayı Poşet 500 gr', 'image' => 'tirebolu-koopozel-tirebolu-cayi-poset-500gr.webp', 'price' => 102, 'sale_price' => 86.7 ],
            [ 'code' => '153,007,012', 'name' => 'Tirebolu Kooperatifi Özel Tirebolu Çayı Poşet 1000 gr', 'image' => 'tirebolu-koopozel-tirebolu-cayi-poset-poset-1000g.webp', 'price' => 199.75, 'sale_price' => 169.79 ],
            [ 'code' => '153,007,013', 'name' => 'Tirebolu Kooperatifi Export Çayı 500 gr', 'image' => 'tirebolu-koopexport-cay-500gr.webp', 'price' => 97.75, 'sale_price' => 83.09 ],
            [ 'code' => '153,007,014', 'name' => 'Tirebolu Kooperatifi Export Çayı 1000 gr', 'image' => 'tirebolu-koopexport-cay-1000gr.webp', 'price' => 192.75, 'sale_price' => 163.84 ],
            [ 'code' => '153,007,015', 'name' => 'Tirebolu Kooperatifi Filiz Çayı 500 gr', 'image' => 'tirebolu-koopfiliz-cay-500gr.webp', 'price' => 87.5, 'sale_price' => 74.38 ],
            [ 'code' => '153,007,016', 'name' => 'Tirebolu Kooperatifi Filiz Çayı 1000 gr', 'image' => 'tirebolu-koopfiliz-cay-1000gr.webp', 'price' => 175, 'sale_price' => 148.75 ],
            [ 'code' => '153,013,019', 'name' => 'Akkaya Cam Salça 680 gr', 'image' => 'akkaya-salca-680-gr-cam.webp', 'price' => 72.75, 'sale_price' => 61.84 ],
            [ 'code' => '153,011,081', 'name' => 'Polatlı Ziraat Odası Kırmızı Mercimek 2000 gr', 'image' => 'polatli-ziraat-odasi-kirmizi-mercimek-2000-gr.webp', 'price' => 105.8, 'sale_price' => 89.93 ],
            [ 'code' => '153,011,082', 'name' => 'Polatlı Ziraat Odası Yeşil Mercimek 2000 gr', 'image' => 'polatli-ziraat-odasi-yesil-mercimek-2000-gr.webp', 'price' => 159.5, 'sale_price' => 135.58 ],
            [ 'code' => '153,011,034', 'name' => 'Polatlı Ziraat Odası Nohut 1000 gr', 'image' => 'polatli-ziraat-odasi-nohut-kg.webp', 'price' => 57.25, 'sale_price' => 48.66 ],
            [ 'code' => '153,011,026', 'name' => 'Polatlı Ziraat Odası Pilavlık Bulgur 1000 gr', 'image' => 'polatli-ziraat-odasi-pilavlik-bulgur-kg.webp', 'price' => 29.9, 'sale_price' => 25.42 ],
            [ 'code' => '153,011,065', 'name' => 'Polatlı Ziraat Odası Köftelik-Kısırılık Bulgur 1000 gr', 'image' => 'polatli-ziraat-odasikoftelik-kisirlik-bulgur-kg.webp', 'price' => 24.5, 'sale_price' => 20.83 ],
            [ 'code' => '153,011,078', 'name' => 'Boğazlıyan Kooperatifi Baldo Pirinç 1000 gr', 'image' => 'bogazliyan-koop-baldo-pirinc-kg.webp', 'price' => 64, 'sale_price' => 54.4 ],
            [ 'code' => '153,011,069', 'name' => 'Boğazlıyan Kooperatifi Osmancık Pirinç 1000 gr', 'image' => 'bogazliyan-koop-osmancik-pirinc-kg.webp', 'price' => 53, 'sale_price' => 45.05 ],
            [ 'code' => '153,011,077', 'name' => 'Bala Kooperatifi Kunduru Bulguru 1000 gr', 'image' => 'bala-koop-kunduru-bulguru-1000-gr.webp', 'price' => 69.9, 'sale_price' => 59.42 ],
            [ 'code' => '153,011,042', 'name' => 'Denizli Kooperatifi Kuru Fasulye Dermason 1000 gr', 'image' => 'denizli-koop-kuru-fasulye-dermason-1000-gr.webp', 'price' => 78.9, 'sale_price' => 67.07 ],
            [ 'code' => '153,020,001', 'name' => 'Şeker 1000 gr', 'image' => 'seker.webp', 'price' => 39.5, 'sale_price' => 33.58 ],
            [ 'code' => '999,999,999', 'name' => 'Un 1000 gr', 'image' => 'un.webp', 'price' => 22.25, 'sale_price' => 18.91 ],
            [ 'code' => '152,004,016', 'name' => "3'lü Çerez", 'image' => '3lu-cerez.webp', 'price' => 26, 'sale_price' => 22.1 ],
            [ 'code' => '152,004,011', 'name' => 'Sade Grisini', 'image' => 'sade-grisini.webp', 'price' => 14, 'sale_price' => 11.9 ],
            [ 'code' => '152,004,012', 'name' => 'Kepekli Grisini', 'image' => 'kepekli-grisini.webp', 'price' => 14.5, 'sale_price' => 12.33 ],
            [ 'code' => '152,004,014', 'name' => 'Çörek Otlulu Grisini', 'image' => 'corek-otlu-grisini.webp', 'price' => 15, 'sale_price' => 12.75 ],
            [ 'code' => '152,004,013', 'name' => 'Susamlı Grisini', 'image' => 'susamli-grisini.webp', 'price' => 15, 'sale_price' => 12.75 ],
            [ 'code' => '153,027,035', 'name' => 'Zinde Kooperatifi Keçiboynuzlu Fındık Kreması 350 gr', 'image' => 'zinde-koop-keciboynuzlu-findik-kremasi-350-gr.webp', 'price' => 75, 'sale_price' => 63.75 ],
            [ 'code' => '153,026,038', 'name' => 'Yeşilağaç Çilek Reçeli 450 gr', 'image' => 'yesilagac-cilek-receli-450gr.webp', 'price' => 49.9, 'sale_price' => 42.42 ],
            [ 'code' => '153,026,035', 'name' => 'Yeşilağaç Havuç Reçeli 450 gr', 'image' => 'yesilagac-havuc-receli-450gr.webp', 'price' => 48.8, 'sale_price' => 41.48 ],
            [ 'code' => '153,026,036', 'name' => 'Yeşilağaç İncir Reçeli 450 gr', 'image' => 'yesilagac-incir-receli-450gr.webp', 'price' => 49.9, 'sale_price' => 42.42 ],
            [ 'code' => '153,026,034', 'name' => 'Yeşilağaç Karadut Reçeli 450 gr', 'image' => 'yesilagac-karadut-receli-450gr.webp', 'price' => 49.9, 'sale_price' => 42.42 ],
            [ 'code' => '153,026,037', 'name' => 'Yeşilağaç Vişne Reçeli 450 gr', 'image' => 'yesilagac-visne-receli-450gr.webp', 'price' => 49.9, 'sale_price' => 42.42 ],
            [ 'code' => '153,026,033', 'name' => 'Yeşilağaç Gül Reçeli 450 gr', 'image' => 'yesilagac-gul-receli-450gr.webp', 'price' => 46.9, 'sale_price' => 39.87 ],
            [ 'code' => '153,023,002', 'name' => 'Konurca Kooperatifi Gemlik Yağlı Sele Siyah (290-320) 1000 gr', 'image' => 'konurca-koop-gemlik-yagli-sele-siyah-290-320-1000gr.webp', 'price' => 203.75, 'sale_price' => 173.19 ],
            [ 'code' => '153,023,003', 'name' => 'Konurca Kooperatifi Gemlik Yağlı Sele Siyah (350-380) 1000 gr', 'image' => 'konurca-koop-gemlik-yagli-sele-siyah-350-380-1000gr.webp', 'price' => 163.75, 'sale_price' => 139.19 ],
        ]);
    }
}
