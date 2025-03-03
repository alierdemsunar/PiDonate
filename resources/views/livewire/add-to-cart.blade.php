<div>
    <div class="input-group mb-3">
        <button class="btn btn-outline-secondary" type="button" wire:click="$set('quantity', Math.max(1, parseInt($wire.quantity) - 1))" onclick="this.blur()">-</button>
        <input wire:model.live="quantity" type="number" min="1" class="form-control text-center" value="1">
        <button class="btn btn-outline-secondary" type="button" wire:click="$set('quantity', parseInt($wire.quantity) + 1)" onclick="this.blur()">+</button>
    </div>

    <button wire:click="addToCart" class="btn btn-primary w-100" wire:loading.attr="disabled" onclick="this.blur()">
        <span wire:loading.remove>
            <i class="bi bi-cart-plus"></i> Sepete Ekle
        </span>
        <span wire:loading>
            <i class="bi bi-hourglass-split"></i> Ekleniyor...
        </span>
    </button>
</div>
