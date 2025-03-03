<div class="add-to-cart-wrapper">
    <button wire:click="addToCart" class="add-to-cart-button" wire:loading.attr="disabled" onclick="this.blur()">
        <span wire:loading.remove>
            <i class="bi bi-cart-plus"></i> Koliye Ekle
        </span>
        <span wire:loading>
            <i class="bi bi-hourglass-split"></i>
        </span>
    </button>
</div>
