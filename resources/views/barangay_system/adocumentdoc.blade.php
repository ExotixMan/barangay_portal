<!DOCTYPE html>
<html>
<head>
    <title>Generate Document</title>
</head>
<body>
    <h2>Generate Word Document by Request ID</h2>

    <form method="GET" action="{{ route('generate.document') }}" target="_blank">
        <label for="request_id">Enter Request ID:</label>
        <input type="number" name="request_id" id="request_id" required>

        <button type="submit" name="action" value="download">Download Word</button>
        <button type="submit" name="action" value="print">Print PDF</button>
    </form>


</body>
</html>
