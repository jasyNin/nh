@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@700&display=swap" rel="stylesheet">
<style>
.right-sidebar .card {
    border: none;
    background-color: transparent;
}

.right-sidebar .card-header {
    background-color: transparent;
    border-bottom: none;
    padding: 1rem 0;
}

.right-sidebar .card-body {
    padding: 0;
}

.right-sidebar .list-group-item {
    background-color: transparent;
    border: none;
    padding: 0.5rem 0;
}

.right-sidebar .badge {
    font-weight: 700;
    font-size: 0.8rem;
}

.right-sidebar .list-group-item:hover {
    background-color: transparent;
}

.right-sidebar .list-group-item a {
    color: #272727;
    text-decoration: none;
    transition: color 0.3s ease;
    font-weight: 700;
}

.right-sidebar .list-group-item a:hover {
    color: #1682FD;
}

.right-sidebar .card-title {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 0;
}

.right-sidebar .text-muted {
    font-size: 0.85rem;
    font-weight: 700;
}

.right-sidebar .user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.right-sidebar .user-name {
    font-size: 0.9rem;
    color: #272727;
    text-decoration: none;
    transition: color 0.3s ease;
    font-weight: 700;
}

.right-sidebar .user-name:hover {
    color: #1682FD;
}

.right-sidebar .user-rating {
    font-size: 0.8rem;
    color: #1682FD;
    font-weight: 700;
}

.right-sidebar .tag-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    margin: 0.25rem;
    background-color: #f8f9fa;
    border-radius: 1rem;
    color: #272727;
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    font-weight: 700;
}

.right-sidebar .tag-badge:hover {
    background-color: #e9ecef;
    color: #1682FD;
}

.right-sidebar .tag-count {
    font-size: 0.75rem;
    color: #6c757d;
    margin-left: 0.25rem;
    font-weight: 700;
}

.right-sidebar .card, 
.right-sidebar .card-header, 
.right-sidebar .card-body, 
.right-sidebar .list-group-item {
    font-family: 'Ubuntu', sans-serif;
}

.right-sidebar .card-title {
    font-weight: 700;
}

.right-sidebar .list-group-item a,
.right-sidebar .user-name,
.right-sidebar .tag-badge {
    font-weight: 700;
}
</style>
@endpush 