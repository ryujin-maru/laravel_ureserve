<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <div style="text-align: center;">
        <button wire:click="increment">+</button>
        <h1>{{$count}}</h1>
        <div class="mb-8"></div>
        こんにちは、{{$name}}さん<br>
        {{-- <input type="text" wire:model.model="name">
        <input type="text" wire:model.debounce.2000ms="name">
        <input type="text" wire:model.lazy="name">
        <input type="text" wire:model.deffer="name"> --}}
        <input type="text" wire:model="name"><br>
        <button wire:mouseover="mouseOver">マウスを合わせる</button>
    </div>
</div>
