$endpoints = @(
    "/taskboard",
    "/ticketing-system",
    "/inbox-email",
    "/journey",
    "/history-email",
    "/apps/thread-transaction",
    "/apps/ticketing-department",
    "/history-ticketing",
    "/data-table-customer",
    "/data-customer"
)

foreach ($endpoint in $endpoints) {
    try {
        $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000$endpoint" -Method Head
        Write-Output "$endpoint : $($response.StatusCode)"
    } catch {
        Write-Output "$endpoint : FAILED ($($_.Exception.Message))"
    }
}
