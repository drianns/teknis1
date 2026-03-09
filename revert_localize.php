<?php
$filePath = 'c:/laragon/www/kanmo/resources/views/pages/inbox-email/index.blade.php';
$content = file_get_contents($filePath);

// Inverting the previous translations to go back to English
$reverseTranslations = [
    '<span>Tulis Pesan</span>' => '<span>Compose</span>',
    '<h6 class="text-white font-semibold m-0 text-base">Folder</h6>' => '<h6 class="text-white font-semibold m-0 text-base">Folders</h6>',
    '<span class="font-medium">Kotak Masuk</span>' => '<span class="font-medium">Inbox</span>',
    '<span class="font-medium">Terkirim</span>' => '<span class="font-medium">Sent</span>',
    '<span class="font-medium">Draf</span>' => '<span class="font-medium">Drafts</span>',
    '<span class="font-medium">Departemen</span>' => '<span class="font-medium">Department</span>',
    '<span>Menampilkan 1 sampai 10 dari 50 entri</span>' => '<span>Showing 1 to 10 of 50 entries</span>',
    'class="px-3 py-1 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 disabled:opacity-50 transition-colors">Sebelumnya</button>' => 'class="px-3 py-1 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 disabled:opacity-50 transition-colors">Previous</button>',
    'class="px-3 py-1 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 disabled:opacity-50 transition-colors">Berikutnya</button>' => 'class="px-3 py-1 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 disabled:opacity-50 transition-colors">Next</button>',
    'Tidak ada email terkirim' => 'No sent emails',
    'Pesan Baru' => 'New Message',
    'Dari' => 'From',
    'Kepada' => 'To',
    'Penerima' => 'Recipients',
    'Subjek' => 'Subject',
    'Tanggal' => 'Date',
    'Aksi' => 'Action',
    'Subjek / Konten' => 'Subject / Content',
    'Pertanyaan Pelanggan' => 'Customer Question',
    'Respon Agen' => 'Agent Response',
    'Catatan Internal' => 'Internal Note',
    // Spam Modal Specifics
    'Pindahkan ke Spam?' => 'Move to Spam?',
    'Apakah Anda yakin ingin memindahkan pesan ini ke folder spam?' => 'Are you sure you want to move this message to the spam folder?',
    'Batal' => 'Cancel',
    'Proses' => 'Process',
    // Javascript Notifications (Swal)
    'Berhasil!' => 'Success!',
    'Pesan berhasil dipindahkan ke folder Spam.' => 'Message successfully moved to Spam folder.',
    'Kesalahan!' => 'Error!',
    'Terjadi kesalahan saat memindahkan pesan ke folder Spam.' => 'An error occurred while moving the message to the Spam folder.',
    'Email berhasil ditandai sebagai dibaca' => 'Email successfully marked as read'
];

foreach ($reverseTranslations as $indonesian => $english) {
    $content = str_replace($indonesian, $english, $content);
}

file_put_contents($filePath, $content);
echo "Reverted $filePath to English.\n";
?>