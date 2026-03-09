<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #374151; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-corner { background: transparent; }

    /* Completely hide native select arrow cross-browser */
    .entries-select {
        color: #d1d5db;
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 4px;
        border: none;
        outline: none;
        cursor: pointer;
        background-color: #374151;
        background-image: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        /* Clip right side to hide arrow even in IE/Edge legacy */
        width: 52px;
        overflow: hidden;
    }
    .entries-select:focus,
    .entries-select:focus-visible {
        outline: none;
        border: none;
        box-shadow: none;
    }
    /* For Firefox specifically */
    .entries-select:-moz-focusring {
        color: transparent;
        text-shadow: 0 0 0 #d1d5db;
    }
</style>
