<?php
$products = [
    ['id' => 10,    'name' => 'Chicken Meat'],
    ['id' => 101,   'name' => 'Product 101'],
    ['id' => 232,   'name' => 'Real Aanar Juice'],
    ['id' => 1223,  'name' => 'Wai Wai Quick Veg'],
    ['id' => 2323,  'name' => 'Khajuri Puff'],
    ['id' => 76456, 'name' => 'Chupa Chups Lollipop']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'predict') {
    header('Content-Type: application/json');

    $productId = intval($_POST['product_id']);
    $days      = intval($_POST['days']);

    if ($productId <= 0 || $days <= 0 || $days > 365) {
        echo json_encode(['success' => false, 'error' => 'Invalid input. Days must be between 1 and 365.']);
        exit;
    }

    $scriptDir    = __DIR__;
    $pythonScript = $scriptDir . DIRECTORY_SEPARATOR . 'predict_sales.py';

    $pythonCmd = 'python3';
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $pythonCmd = 'python';
    } else {
        exec('which python3 2>/dev/null', $out, $code);
        if ($code !== 0) {
            exec('which python 2>/dev/null', $out2, $code2);
            $pythonCmd = ($code2 === 0) ? 'python' : 'python3';
        }
    }

    $command = sprintf(
        '%s %s %d %d 2>&1',
        escapeshellcmd($pythonCmd),
        escapeshellarg($pythonScript),
        $productId,
        $days
    );

    $output     = [];
    $returnCode = 0;
    exec($command, $output, $returnCode);
    $outputString = implode("\n", $output);

    $result = [
        'success'    => false,
        'prediction' => 0,
        'accuracy'   => 0,
        'trend'      => '',
        'avg_daily'  => 0,
        'error'      => '',
        'debug'      => $outputString
    ];

    if ($returnCode === 0 && $outputString) {
        if (preg_match('/PREDICTION:([\d.]+)/', $outputString, $m)) $result['prediction'] = floatval($m[1]);
        if (preg_match('/ACCURACY:([\d.]+)/',   $outputString, $m)) $result['accuracy']   = floatval($m[1]);
        if (preg_match('/TREND:([A-Z_]+)/',     $outputString, $m)) $result['trend']      = $m[1];
        if (preg_match('/AVG_DAILY:([\d.]+)/',  $outputString, $m)) $result['avg_daily']  = floatval($m[1]);

        if ($result['prediction'] > 0) {
            $result['success'] = true;
            $result['debug']   = '';
        } else {
            $result['error'] = 'Could not parse prediction output: ' . $outputString;
        }
    } else {
        if (!file_exists($pythonScript)) {
            $result['error'] = 'predict_sales.py not found. Make sure it is in the same folder as index.php.';
        } elseif (!file_exists($scriptDir . DIRECTORY_SEPARATOR . 'sales_models.pkl')) {
            $result['error'] = 'sales_models.pkl not found. Run train_model.py first.';
        } elseif (empty($outputString)) {
            $result['error'] = 'Python returned no output. Check that Python is installed.';
        } else {
            $result['error'] = 'Prediction failed. See debug info below.';
        }
    }

    echo json_encode($result);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Prediction System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --blue:         #3b5bdb;
            --blue-light:   #4c6ef5;
            --violet:       #7048e8;
            --violet-light: #845ef7;
            --bg:           #0d0f1c;
            --surface:      #13162a;
            --card:         #181b30;
            --border:       #252847;
            --text:         #e9ecff;
            --muted:        #7a7fa8;
            --error:        #fa5252;
            --green:        #40c057;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(91,101,241,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(91,101,241,0.05) 1px, transparent 1px);
            background-size: 44px 44px;
            pointer-events: none;
            z-index: 0;
        }

        .glow-top {
            position: fixed;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(112,72,232,0.12) 0%, transparent 65%);
            top: -300px;
            right: -200px;
            pointer-events: none;
            z-index: 0;
        }

        .glow-bottom {
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59,91,219,0.1) 0%, transparent 65%);
            bottom: -250px;
            left: -150px;
            pointer-events: none;
            z-index: 0;
        }

        .wrapper {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: 370px 1fr;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow:
                0 30px 80px rgba(0,0,0,0.5),
                0 0 0 1px rgba(112,72,232,0.1),
                inset 0 1px 0 rgba(255,255,255,0.04);
            position: relative;
            z-index: 1;
        }

        .left-panel {
            background: var(--surface);
            border-right: 1px solid var(--border);
            padding: 44px 36px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--blue), var(--violet));
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 40px;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--blue), var(--violet));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            box-shadow: 0 4px 16px rgba(112,72,232,0.4);
        }

        .brand-name {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .panel-heading {
            font-size: 30px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 14px;
            color: var(--text);
        }

        .panel-heading span {
            background: linear-gradient(135deg, var(--blue-light), var(--violet-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .panel-desc {
            font-size: 13.5px;
            color: var(--muted);
            line-height: 1.65;
            margin-bottom: 36px;
        }

        .online-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 40px;
        }

        .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--green);
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.3; }
        }

        .badge-text {
            font-size: 12px;
            font-weight: 600;
            color: var(--green);
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: auto;
        }

        .info-box {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px;
        }

        .info-box-label {
            font-size: 10.5px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 6px;
        }

        .info-box-value {
            font-size: 20px;
            font-weight: 800;
            color: var(--text);
        }

        .info-box-value small {
            font-size: 11px;
            font-weight: 500;
            color: var(--violet-light);
        }

        .right-panel {
            padding: 44px 40px;
            display: flex;
            flex-direction: column;
        }

        .form-title {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 28px;
        }

        .field {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 7px;
        }

        select,
        input[type="number"] {
            width: 100%;
            padding: 12px 14px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: inherit;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            -webkit-appearance: none;
            appearance: none;
        }

        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath fill='%237a7fa8' d='M5 7L0 2h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 34px;
            cursor: pointer;
        }

        select option {
            background: var(--surface);
        }

        select:focus,
        input[type="number"]:focus {
            border-color: var(--violet);
            box-shadow: 0 0 0 3px rgba(112,72,232,0.15);
        }

        .days-wrap {
            position: relative;
        }

        .days-tag {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            font-weight: 700;
            color: var(--violet-light);
            pointer-events: none;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            margin-top: 6px;
            background: linear-gradient(135deg, var(--blue) 0%, var(--violet) 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14.5px;
            font-weight: 700;
            letter-spacing: 0.04em;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 6px 20px rgba(112,72,232,0.35);
        }

        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(112,72,232,0.45);
        }

        .submit-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .result-area {
            margin-top: 26px;
            flex: 1;
        }

        .loading-box {
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 14px;
            padding: 30px;
        }

        .loading-box.show {
            display: flex;
        }

        .spinner {
            width: 42px;
            height: 42px;
            border: 3px solid var(--border);
            border-top-color: var(--violet);
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-label {
            font-size: 13px;
            color: var(--muted);
        }

        .result-box {
            display: none;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
            animation: rise 0.35s ease-out;
        }

        .result-box.show {
            display: block;
        }

        .result-box.ok   { border-color: rgba(112,72,232,0.35); }
        .result-box.fail { border-color: rgba(250,82,82,0.3); }

        @keyframes rise {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .result-top {
            display: flex;
            align-items: center;
            gap: 9px;
            padding-bottom: 16px;
            margin-bottom: 18px;
            border-bottom: 1px solid var(--border);
        }

        .result-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }

        .result-icon.ok   { background: rgba(112,72,232,0.15); color: var(--violet-light); }
        .result-icon.fail { background: rgba(250,82,82,0.12);  color: var(--error); }

        .result-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .result-label.ok   { color: var(--violet-light); }
        .result-label.fail { color: var(--error); }

        .big-num {
            text-align: center;
            padding: 18px 0 22px;
        }

        .big-num-value {
            font-size: 58px;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, var(--blue-light), var(--violet-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .big-num-label {
            font-size: 11.5px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.09em;
            margin-top: 6px;
        }

        .metrics {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 9px;
            margin-bottom: 10px;
        }

        .metric {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 14px;
        }

        .metric-label {
            font-size: 10.5px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 5px;
        }

        .metric-value {
            font-size: 17px;
            font-weight: 800;
            color: var(--text);
        }

        .acc-wrap {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 10px;
        }

        .acc-bar-bg {
            height: 5px;
            background: var(--border);
            border-radius: 5px;
            margin-top: 8px;
            overflow: hidden;
        }

        .acc-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--blue), var(--violet));
            border-radius: 5px;
            width: 0%;
            transition: width 0.9s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .trend-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .trend-tag.UP     { background: rgba(64,192,87,0.12);   color: #51cf66; }
        .trend-tag.DOWN   { background: rgba(250,82,82,0.12);   color: #ff6b6b; }
        .trend-tag.STABLE { background: rgba(122,127,168,0.12); color: var(--muted); }

        .product-note {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
        }

        .product-note strong {
            color: var(--text);
        }

        .err-msg {
            font-size: 13px;
            color: var(--error);
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .debug-dump {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: var(--muted);
            max-height: 140px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
        }

        @media (max-width: 700px) {
            .wrapper {
                grid-template-columns: 1fr;
                max-width: 440px;
            }
            .left-panel {
                border-right: none;
                border-bottom: 1px solid var(--border);
                padding: 36px 28px;
            }
            .right-panel {
                padding: 36px 28px;
            }
            .panel-heading {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="glow-top"></div>
<div class="glow-bottom"></div>

<div class="wrapper">

    <div class="left-panel">
        <div class="brand">
            <div class="brand-icon">📊</div>
            <span class="brand-name">SalesCast</span>
        </div>

        <div class="panel-heading">
            Sales<br><span>Prediction</span><br>System
        </div>

        <p class="panel-desc">
            Forecast future product sales using a Linear Regression model trained on your MySQL sales history.
        </p>

        <div class="online-badge">
            <div class="dot"></div>
            <span class="badge-text"><?= count($products) ?> models ready</span>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <div class="info-box-label">Products</div>
                <div class="info-box-value"><?= count($products) ?> <small>items</small></div>
            </div>
            <div class="info-box">
                <div class="info-box-label">Model</div>
                <div class="info-box-value" style="font-size:15px;padding-top:2px;">Linear <small>Regression</small></div>
            </div>
            <div class="info-box">
                <div class="info-box-label">Max Range</div>
                <div class="info-box-value">365 <small>days</small></div>
            </div>
            <div class="info-box">
                <div class="info-box-label">Source</div>
                <div class="info-box-value" style="font-size:15px;padding-top:2px;">MySQL <small>goodsout</small></div>
            </div>
        </div>
    </div>

    <div class="right-panel">
        <div class="form-title">Run a Forecast</div>

        <form id="predForm">
            <div class="field">
                <label for="product">Product</label>
                <select id="product" name="product_id" required>
                    <option value="">— Pick a product —</option>
                    <?php foreach ($products as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= htmlspecialchars($p['name']) ?> (ID: <?= $p['id'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label for="days">Days to Forecast</label>
                <div class="days-wrap">
                    <input
                        type="number"
                        id="days"
                        name="days"
                        min="1"
                        max="365"
                        value="30"
                        required
                        placeholder="e.g. 30"
                    >
                    <span class="days-tag" id="daysTag">30d</span>
                </div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                Predict Sales
            </button>
        </form>

        <div class="result-area">
            <div class="loading-box" id="loadingBox">
                <div class="spinner"></div>
                <span class="loading-label">Running prediction...</span>
            </div>

            <div class="result-box" id="resultBox"></div>
        </div>
    </div>

</div>

<script>
var form      = document.getElementById('predForm');
var submitBtn = document.getElementById('submitBtn');
var loading   = document.getElementById('loadingBox');
var resultBox = document.getElementById('resultBox');
var daysInput = document.getElementById('days');
var daysTag   = document.getElementById('daysTag');

daysInput.addEventListener('input', function() {
    daysTag.textContent = (daysInput.value || '0') + 'd';
});

form.addEventListener('submit', async function(e) {
    e.preventDefault();

    var productId = document.getElementById('product').value;
    var days = daysInput.value;

    if (!productId || !days) {
        alert('Please select a product and enter the number of days.');
        return;
    }

    loading.classList.add('show');
    resultBox.className = 'result-box';
    submitBtn.disabled = true;
    submitBtn.textContent = 'Processing...';

    try {
        var fd = new FormData();
        fd.append('action', 'predict');
        fd.append('product_id', productId);
        fd.append('days', days);

        var res  = await fetch(window.location.href, { method: 'POST', body: fd });
        var data = await res.json();

        if (data.success) {
            var productName = document.getElementById('product').selectedOptions[0].text;
            var trend = data.trend || 'STABLE';
            var arrow = trend === 'UP' ? '↑' : trend === 'DOWN' ? '↓' : '→';
            var acc   = Math.max(0, Math.min(100, data.accuracy));

            resultBox.innerHTML =
                '<div class="result-top">' +
                    '<div class="result-icon ok">✓</div>' +
                    '<div class="result-label ok">Prediction Result</div>' +
                '</div>' +
                '<div class="big-num">' +
                    '<div class="big-num-value">' + Math.round(data.prediction) + '</div>' +
                    '<div class="big-num-label">units predicted over ' + days + ' day' + (days > 1 ? 's' : '') + '</div>' +
                '</div>' +
                '<div class="metrics">' +
                    '<div class="metric">' +
                        '<div class="metric-label">Avg Daily Sales</div>' +
                        '<div class="metric-value">' + data.avg_daily.toFixed(1) + '</div>' +
                    '</div>' +
                    '<div class="metric">' +
                        '<div class="metric-label">Sales Trend</div>' +
                        '<div class="metric-value"><span class="trend-tag ' + trend + '">' + arrow + ' ' + trend + '</span></div>' +
                    '</div>' +
                '</div>' +
                '<div class="acc-wrap">' +
                    '<div class="metric-label">Model Accuracy (R²)</div>' +
                    '<div class="metric-value">' + acc.toFixed(1) + '%</div>' +
                    '<div class="acc-bar-bg"><div class="acc-bar" id="accBar"></div></div>' +
                '</div>' +
                '<div class="product-note">Product: <strong>' + productName + '</strong></div>';

            resultBox.classList.add('show', 'ok');

            setTimeout(function() {
                var bar = document.getElementById('accBar');
                if (bar) bar.style.width = acc + '%';
            }, 100);

        } else {
            resultBox.innerHTML =
                '<div class="result-top">' +
                    '<div class="result-icon fail">✕</div>' +
                    '<div class="result-label fail">Failed</div>' +
                '</div>' +
                '<div class="err-msg">' + data.error + '</div>' +
                (data.debug ? '<div class="debug-dump">' + escHtml(data.debug) + '</div>' : '');

            resultBox.classList.add('show', 'fail');
        }

    } catch (err) {
        resultBox.innerHTML =
            '<div class="result-top">' +
                '<div class="result-icon fail">✕</div>' +
                '<div class="result-label fail">Network Error</div>' +
            '</div>' +
            '<div class="err-msg">' + err.message + '</div>';

        resultBox.classList.add('show', 'fail');
    }

    loading.classList.remove('show');
    submitBtn.disabled = false;
    submitBtn.textContent = 'Predict Sales';
});

function escHtml(str) {
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}
</script>
</body>
</html>