<!DOCTYPE html>
    <head>
        @livewireStyles
    </head>
    <body>
        livewireテスト
        <div>
            @if(session('message'))
            {{session('message')}}
            @endif
        </div>
        <livewire:counter />

        @livewireScripts
    </body>
</html>