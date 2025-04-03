@push('styles')
<style>
.side-menu {
    position: sticky;
    top: 0;
    display: flex;
    flex-direction: column;
    height: calc(100vh - 40px);
    padding-left: 0;
    /* margin-top: 50px; */
}

.menu-section {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.menu-item {
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
    margin-left: 0;
}

.menu-item:hover {
    background-color: #F8F9FA;
}

.menu-item.active {
    background-color: white;
}

.menu-item a {
    color: #272727;
    font-size: 15px;
    font-weight: 500;
}

.menu-item img {
    opacity: 0.8;
}

.menu-item:hover img {
    opacity: 1;
}

.menu-item.active img {
    filter: brightness(0) saturate(100%) invert(32%) sepia(98%) saturate(1035%) hue-rotate(210deg) brightness(97%) contrast(101%);
}

.menu-section:last-child {
    margin-top: auto;
    padding-top: 12px;
    border-top: 1px solid #F0F0F0;
}
</style>
@endpush 