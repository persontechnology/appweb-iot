<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary my-2']) }}>
    {{ $slot }}
</button>
