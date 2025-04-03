@props(['user', 'size' => 32])

@if($user->avatar)
    <img src="{{ asset('storage/' . $user->avatar) }}" 
         alt="{{ $user->name }}" 
         class="rounded-circle" 
         width="{{ $size }}" 
         height="{{ $size }}">
@else
    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
         style="width: {{ $size }}px; height: {{ $size }}px;">
        {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
@endif 