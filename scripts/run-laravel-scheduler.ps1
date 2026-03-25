$ErrorActionPreference = "SilentlyContinue"
Set-Location -LiteralPath "C:\Users\Admin\OneDrive\Desktop\Barangay Portal\barangay-portal"
php artisan schedule:run | Out-Null
Exit 0