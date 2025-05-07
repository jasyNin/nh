@props(['user', 'size' => 40])

@if($user && $user->id && $user->avatar)
    <img src="{{ asset('storage/' . $user->avatar) }}" 
         alt="{{ $user->name }}" 
         class="rounded-circle" 
         width="{{ $size }}" 
         height="{{ $size }}">
@elseif($user && $user->id)
    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
         style="width: {{ $size }}px; height: {{ $size }}px; font-size: {{ $size * 0.4 }}px;">
        {{ mb_strtoupper(mb_substr($user->name, 0, 1, 'UTF-8'), 'UTF-8') }}
    </div>
@else
    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
         style="width: {{ $size }}px; height: {{ $size }}px; font-size: {{ $size * 0.4 }}px;">
        <i class="bi bi-person"></i>
    </div>
@endif 