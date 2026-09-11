@if ($errors->any())
    <div class="alert alert--error" role="alert">
        <ul style="margin:0; padding-left:1.2rem;">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif
