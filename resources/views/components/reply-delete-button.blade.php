@props(['replyId'])

<form action="{{ route('replies.destroy', $replyId) }}" method="POST">
    @csrf
    @method('DELETE')
    <button 
        type="submit"
        class="dropdown-item d-flex align-items-center report-button" 
        style="width: 160px; height: 40px; border-radius: 12px;"
        onclick="return confirm('Вы уверены, что хотите удалить этот ответ?')"
    >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2" style="margin-left: 10px;">
            <g clip-path="url(#clip0_1376_3399)">
                <path d="M15.5625 6.65606V5.9435C15.5625 4.94582 15.5625 4.44699 15.3683 4.06592C15.1975 3.73073 14.925 3.45821 14.5898 3.28742C14.2088 3.09326 13.7099 3.09326 12.7123 3.09326H11.2871C10.2895 3.09326 9.79062 3.09326 9.40956 3.28742C9.07437 3.45821 8.80185 3.73073 8.63106 4.06592C8.4369 4.44699 8.4369 4.94582 8.4369 5.9435V6.65606M10.2183 11.5549V16.0084M13.7811 11.5549V16.0084M3.9834 6.65606H20.016M18.2346 6.65606V16.6319C18.2346 18.1284 18.2346 18.8767 17.9434 19.4483C17.6872 19.9511 17.2784 20.3598 16.7756 20.616C16.204 20.9073 15.4558 20.9073 13.9592 20.9073H10.0402C8.54364 20.9073 7.79538 20.9073 7.22379 20.616C6.721 20.3598 6.31222 19.9511 6.05604 19.4483C5.7648 18.8767 5.7648 18.1284 5.7648 16.6319V6.65606" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </g>
            <defs>
                <clipPath id="clip0_1376_3399">
                    <rect width="20" height="20" fill="white" transform="matrix(1 0 0 -1 2 22)"/>
                </clipPath>
            </defs>
        </svg>
        <span>Удалить</span>
    </button>
</form>

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