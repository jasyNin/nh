@props(['commentId'])

<button 
    class="dropdown-item d-flex align-items-center report-button" 
    data-bs-toggle="modal" 
    data-bs-target="#reportCommentModal{{ $commentId }}"
    style="width: 160px; height: 40px; border-radius: 12px;"
>
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2" style="margin-left: 10px;">
        <g clip-path="url(#clip0_1376_3409)">
            <path d="M5.33301 14.5003C5.33301 14.5003 6.16634 13.667 8.66634 13.667C11.1663 13.667 12.833 15.3337 15.333 15.3337C17.833 15.3337 18.6663 14.5003 18.6663 14.5003V4.50033C18.6663 4.50033 17.833 5.33366 15.333 5.33366C12.833 5.33366 11.1663 3.66699 8.66634 3.66699C6.16634 3.66699 5.33301 4.50033 5.33301 4.50033V14.5003Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M5.33301 20.3338V14.5005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </g>
        <defs>
            <clipPath id="clip0_1376_3409">
                <rect width="20" height="20" fill="white" transform="matrix(1 0 0 -1 2 22.0005)"/>
            </clipPath>
        </defs>
    </svg>
    <span>Пожаловаться</span>
</button>

<style>
.report-button {
    color: #272727 !important;
    transition: background-color 0.2s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    padding: 8px 16px;
}
.report-button:hover {
    background-color: #F9F9F9 !important;
    color: #272727 !important;
}
.report-button svg {
    color: #272727;
}
.report-button span {
    font-size: 14px;
    line-height: 24px;
}
</style> 