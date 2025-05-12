@props(['commentId'])

<a 
    href="#" 
    class="dropdown-item d-flex align-items-center report-button edit-comment-btn" 
    data-comment-id="{{ $commentId }}"
    style="width: 160px; height: 40px; border-radius: 12px;"
>
    <svg width="24" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2" style="margin-left: 10px;">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M16.2818 3.58606C16.6569 3.21112 17.1655 3.00049 17.6958 3.00049C18.2261 3.00049 18.7348 3.21112 19.1098 3.58606L20.5238 5.00006C20.7096 5.18579 20.857 5.4063 20.9576 5.64901C21.0581 5.89171 21.1099 6.15185 21.1099 6.41456C21.1099 6.67727 21.0581 6.93741 20.9576 7.18012C20.857 7.42282 20.7096 7.64333 20.5238 7.82906L18.8268 9.52506L8.50082 19.8511C8.21402 20.1379 7.84678 20.3308 7.44782 20.4041L4.29082 20.9841C4.13148 21.0135 3.96738 21.0037 3.81265 20.9556C3.65792 20.9075 3.51719 20.8226 3.40257 20.7081C3.28795 20.5935 3.20288 20.4529 3.15467 20.2982C3.10646 20.1435 3.09656 19.9794 3.12582 19.8201L3.70582 16.6611C3.7791 16.2621 3.97197 15.8949 4.25882 15.6081L16.2818 3.58606ZM18.1198 7.40406L19.1098 6.41406L17.6958 5.00006L16.7058 5.99006L18.1198 7.40406ZM15.2918 7.40406L5.67282 17.0221L5.35482 18.7551L7.08682 18.4371L16.7058 8.81806L15.2918 7.40406Z" fill="currentColor"/>
    </svg>
    <span>Редактировать</span>
</a>

<style>
.report-button {
    color: #272727 !important;
    transition: background-color 0.2s ease;
    text-decoration: none;
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