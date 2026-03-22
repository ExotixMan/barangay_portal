{{-- resources/views/chatbot/debug.blade.php --}}
{{-- Visit /chatbot/debug to see your setup status --}}
{{-- REMOVE this route in production! --}}
<!DOCTYPE html>
<html>
<head>
    <title>Chatbot Debug</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
</head>
<body class="p-4" style="background:#f8f9fa">
<div class="container" style="max-width:800px">
    <h3 class="mb-4">🔍 Chatbot Debug Panel</h3>

    {{-- 1. DB Connection --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">1. Database Connection</div>
        <div class="card-body">
            @php
                try {
                    \DB::connection()->getPdo();
                    $dbOk = true;
                    $dbMsg = 'Connected to: ' . \DB::connection()->getDatabaseName();
                } catch (\Exception $e) {
                    $dbOk = false;
                    $dbMsg = $e->getMessage();
                }
            @endphp
            @if($dbOk)
                <span class="badge bg-success">✅ OK</span> {{ $dbMsg }}
            @else
                <span class="badge bg-danger">❌ FAILED</span>
                <pre class="mt-2 text-danger small">{{ $dbMsg }}</pre>
                <hr>
                <strong>Fix:</strong> Check your <code>.env</code>:<br>
                <pre class="bg-light p-2 small">DB_CONNECTION=pgsql
DB_HOST=aws-0-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.YOUR_PROJECT_REF
DB_PASSWORD=your_password
DB_SSLMODE=require</pre>
                Also add to <code>config/database.php</code> pgsql options:<br>
                <pre class="bg-light p-2 small">'options' => [PDO::ATTR_EMULATE_PREPARES => true]</pre>
            @endif
        </div>
    </div>

    {{-- 2. Tables --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">2. Database Tables</div>
        <div class="card-body">
            @php
                $tables = ['chatbot_knowledge','chatbot_conversations','chatbot_messages','chatbot_unmatched'];
            @endphp
            @foreach($tables as $table)
                @php
                    try {
                        $count = \DB::table($table)->count();
                        $tableOk = true;
                    } catch (\Exception $e) {
                        $tableOk = false;
                        $count = 0;
                        $tableErr = $e->getMessage();
                    }
                @endphp
                <div class="mb-1">
                    @if($tableOk)
                        <span class="badge bg-success">✅</span> <code>{{ $table }}</code> — {{ $count }} rows
                    @else
                        <span class="badge bg-danger">❌</span> <code>{{ $table }}</code> — NOT FOUND
                        <br><small class="text-danger">Run: <code>php artisan migrate</code></small>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- 3. Routes --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">3. Routes</div>
        <div class="card-body">
            @php
                $routes = [
                    ['POST', '/chatbot/start'],
                    ['POST', '/chatbot/message'],
                    ['GET',  '/admin/chatbot'],
                ];
                $allRoutes = collect(\Route::getRoutes()->getRoutes())->map(fn($r) => $r->uri());
            @endphp
            @foreach($routes as [$method, $uri])
                @php $found = $allRoutes->contains(ltrim($uri, '/')); @endphp
                <div class="mb-1">
                    @if($found)
                        <span class="badge bg-success">✅</span>
                    @else
                        <span class="badge bg-danger">❌</span>
                    @endif
                    <code>{{ $method }} {{ $uri }}</code>
                    @if(!$found)
                        <small class="text-danger">— Add <code>require __DIR__.'/chatbot.php';</code> to routes/web.php</small>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- 4. CSRF --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">4. CSRF Token</div>
        <div class="card-body">
            @if(csrf_token())
                <span class="badge bg-success">✅ Present</span>
                <code class="ms-2">{{ substr(csrf_token(), 0, 20) }}…</code>
            @else
                <span class="badge bg-danger">❌ Missing</span> — Make sure your layout has <code>&#64;csrf</code> or the meta tag.
            @endif
        </div>
    </div>

    {{-- 5. Live API Test --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">5. Live API Test</div>
        <div class="card-body">
            <button class="btn btn-primary btn-sm" onclick="testStart()">Test /chatbot/start</button>
            <button class="btn btn-secondary btn-sm ms-2" onclick="testMessage()">Test /chatbot/message</button>
            <pre id="api-result" class="mt-3 p-3 bg-light small" style="min-height:60px;border-radius:6px">Click a button to test…</pre>
        </div>
    </div>

    {{-- 6. ENV --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">6. Environment</div>
        <div class="card-body small">
            <table class="table table-sm table-bordered mb-0">
                <tr><td>APP_ENV</td><td><code>{{ config('app.env') }}</code></td></tr>
                <tr><td>APP_DEBUG</td><td><code>{{ config('app.debug') ? 'true' : 'false' }}</code></td></tr>
                <tr><td>DB_CONNECTION</td><td><code>{{ config('database.default') }}</code></td></tr>
                <tr><td>DB_HOST</td><td><code>{{ config('database.connections.pgsql.host') }}</code></td></tr>
                <tr><td>DB_PORT</td><td><code>{{ config('database.connections.pgsql.port') }}</code></td></tr>
                <tr><td>DB_DATABASE</td><td><code>{{ config('database.connections.pgsql.database') }}</code></td></tr>
                <tr><td>DB_USERNAME</td><td><code>{{ config('database.connections.pgsql.username') }}</code></td></tr>
            </table>
        </div>
    </div>

</div>

<script>
let sessionId = null;

async function testStart() {
    const el = document.getElementById('api-result');
    el.textContent = 'Testing /chatbot/start …';
    try {
        const res = await fetch('/chatbot/start', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({})
        });
        const data = await res.json();
        sessionId = data.session_id;
        el.textContent = '✅ SUCCESS (' + res.status + '):\n' + JSON.stringify(data, null, 2);
        el.style.color = 'green';
    } catch(e) {
        el.textContent = '❌ FAILED: ' + e.message;
        el.style.color = 'red';
    }
}

async function testMessage() {
    const el = document.getElementById('api-result');
    if (!sessionId) { el.textContent = 'Run "Test /chatbot/start" first!'; return; }
    el.textContent = 'Testing /chatbot/message …';
    try {
        const res = await fetch('/chatbot/message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: 'Hello', session_id: sessionId })
        });
        const text = await res.text();
        el.textContent = (res.ok ? '✅' : '❌') + ' HTTP ' + res.status + ':\n' + text;
        el.style.color = res.ok ? 'green' : 'red';
    } catch(e) {
        el.textContent = '❌ FAILED: ' + e.message;
        el.style.color = 'red';
    }
}
</script>
</body>
</html>
