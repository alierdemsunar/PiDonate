<div>
    <h3>Başarılı Siparişler</h3>
    <button wire:click="loadOrders">Siparişleri Yenile</button>

    <pre>{{ json_encode($orders, JSON_PRETTY_PRINT) }}</pre>
</div>
