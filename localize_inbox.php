<?php
$filePath = 'c:/laragon/www/kanmo/resources/views/pages/inbox-email/index.blade.php';
$content = file_get_contents($filePath);

$translations = [
    '<span>Compose</span>' => '<span>Tulis Pesan</span>',
    '<h6 class="text-white font-semibold m-0 text-base">Folders</h6>' => '<h6 class="text-white font-semibold m-0 text-base">Folder</h6>',
    '<span class="font-medium">Inbox</span>' => '<span class="font-medium">Kotak Masuk</span>',
    '<span class="font-medium">Sent</span>' => '<span class="font-medium">Terkirim</span>',
    '<span class="font-medium">Drafts</span>' => '<span class="font-medium">Draf</span>',
    '<span class="font-medium">Department</span>' => '<span class="font-medium">Departemen</span>',
    '<span>Showing 1 to 10 of 50 entries</span>' => '<span>Menampilkan 1 sampai 10 dari 50 entri</span>',
    'class="px-3 py-1 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 disabled:opacity-50 transition-colors">Previous</button>' => 'class="px-3 py-1 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 disabled:opacity-50 transition-colors">Sebelumnya</button>',
    'class="px-3 py-1 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 disabled:opacity-50 transition-colors">Next</button>' => 'class="px-3 py-1 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 disabled:opacity-50 transition-colors">Berikutnya</button>',
    'No sent emails' => 'Tidak ada email terkirim',
    'New Message' => 'Pesan Baru',
    'From' => 'Dari',
    'To' => 'Kepada',
    'Recipients' => 'Penerima',
    'Subject' => 'Subjek',
    'Date' => 'Tanggal',
    'Action' => 'Aksi',
    'Subject / Content' => 'Subjek / Konten',
    'Customer Question' => 'Pertanyaan Pelanggan',
    'Agent Response' => 'Respon Agen',
    'Internal Note' => 'Catatan Internal',
];

foreach ($translations as $english => $indonesian) {
    $content = str_replace($english, $indonesian, $content);
}

file_put_contents($filePath, $content);
echo "Localized $filePath to Indonesian.\n";
?>