@props(['user'])

<img src="{{ asset('images/' . $user->rank_icon) }}" 
     alt="{{ $user->rank_name }}" 
     title="{{ $user->rank_name }}" 
     class="rank-icon"
     width="20" 
     height="20">

<style>
.rank-icon {
    position: absolute;
    bottom: -4px;
    right: -4px;
    border: 1px solid white;
    border-radius: 50%;
}
</style> 