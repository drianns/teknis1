<?php
$filePath = 'c:/laragon/www/kanmo/resources/views/pages/inbox-email/index.blade.php';
$content = file_get_contents($filePath);

// Step 1: Remove the div that prematurely closes x-data
// It is located after the grid closing, followed by compose-backdrop
$targetPattern = '/<\/div>\s+<\/div>\s+<\/div>\s+<\/div>\s+<\/div>\s+\r?\n\s+\r?\n\s+<!-- Compose Modal Backdrop -->/';
$replacement = "        </div>\n            </div>\n        </div>\n\n        <!-- Compose Modal Backdrop -->";

if (preg_match($targetPattern, $content)) {
    $content = preg_replace($targetPattern, $replacement, $content);
    echo "Removed premature closing div.\n";
} else {
    echo "Target pattern not found. Analyzing content...\n";
    // Alternative approach: find the specific block around line 800
    // We already know line 8 is the start of x-data
    // Let's just trust our view_file output and try to match a slightly larger block
}

// Step 2: Add closing div before </x-slot>
if (strpos($content, '</div>' . "\n" . '    </x-slot>') === false) {
    if (preg_match('/<\/script>\s+<\/x-slot>/', $content)) {
        $content = preg_replace('/<\/script>\s+<\/x-slot>/', "</script>\n        </div>\n    </x-slot>", $content);
        echo "Added closing div before </x-slot>.\n";
    }
}

file_put_contents($filePath, $content);
echo "File updated successfully.\n";
?>