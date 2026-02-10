<?php
// Sales Prediction System
// This file handles both UI display and prediction logic

// Configuration
$products = [
    ['id' => 10, 'name' => 'chicken meat'],
    ['id' => 101, 'name' => ''],
    ['id' => 232, 'name' => 'Real Aanar juice'],
    ['id' => 1223, 'name' => 'wai wai quick veg'],
    ['id' => 2323, 'name' => 'KHAJURI PUFF'],
    ['id' => 76456, 'name' => 'Chupa Chups Lolipop']
];

// Handle AJAX prediction request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'predict') {
    header('Content-Type: application/json');
    
    $productId = intval($_POST['product_id']);
    $days = intval($_POST['days']);
    
    // Call Python script
    $command = escapeshellcmd("python predict_sales.py $productId $days");
    $output = shell_exec($command);
    
    // Parse the output
    $result = [
        'success' => false,
        'prediction' => 0,
        'accuracy' => 0,
        'error' => ''
    ];
    
    if ($output) {
        // Extract prediction and accuracy from output
        if (preg_match('/Predicted sales:\s*([\d.]+)\s*units/', $output, $matches)) {
            $result['prediction'] = floatval($matches[1]);
            $result['success'] = true;
        }
        if (preg_match('/Model accuracy \(R²\):\s*([\d.]+)%/', $output, $matches)) {
            $result['accuracy'] = floatval($matches[1]);
        }
        if (!$result['success']) {
            $result['error'] = 'Could not parse prediction result';
        }
    } else {
        $result['error'] = 'Failed to execute Python script';
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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
            text-align: center;
        }
        
        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .status-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        select, input[type="number"] {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            background: white;
        }
        
        select:focus, input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .product-option {
            padding: 10px;
        }
        
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        
        .result-container {
            margin-top: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%);
            border-radius: 15px;
            border-left: 4px solid #667eea;
            display: none;
        }
        
        .result-container.show {
            display: block;
            animation: slideIn 0.4s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .result-title {
            font-size: 14px;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .result-item {
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .result-label {
            color: #4b5563;
            font-size: 14px;
        }
        
        .result-value {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }
        
        .result-value.highlight {
            color: #667eea;
            font-size: 24px;
        }
        
        .accuracy-bar {
            width: 100%;
            height: 8px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 8px;
        }
        
        .accuracy-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            border-radius: 10px;
            transition: width 0.6s ease-out;
        }
        
        .loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }
        
        .loading.show {
            display: block;
        }
        
        .spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .error {
            background: #fee2e2;
            border-left-color: #ef4444;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Sales Prediction System</h1>
        <p class="subtitle">Predict future sales using machine learning models</p>
        
        <div style="text-align: center;">
            <span class="status-badge">✓ Models Loaded (6 Products)</span>
        </div>
        
        <form id="predictionForm">
            <div class="form-group">
                <label for="product">Select Product</label>
                <select id="product" name="product_id" required>
                    <option value="">-- Choose a product --</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product['id'] ?>">
                            Product ID: <?= $product['id'] ?> 
                            <?php if ($product['name']): ?>
                                | <?= htmlspecialchars($product['name']) ?>
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="days">Predict sales for how many days ahead?</label>
                <input 
                    type="number" 
                    id="days" 
                    name="days" 
                    min="1" 
                    max="365" 
                    value="30" 
                    required
                    placeholder="Enter number of days (e.g., 30)"
                >
            </div>
            
            <button type="submit" id="predictBtn">
                🔮 Predict Sales
            </button>
        </form>
        
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p style="margin-top: 15px; color: #666;">Analyzing data...</p>
        </div>
        
        <div class="result-container" id="result">
            <div class="result-title">📈 Sales Prediction Result</div>
            
            <div class="result-item">
                <span class="result-label">Product:</span>
                <span class="result-value" id="resultProduct">-</span>
            </div>
            
            <div class="result-item">
                <span class="result-label">Days Ahead:</span>
                <span class="result-value" id="resultDays">-</span>
            </div>
            
            <div class="result-item" style="margin-top: 20px; border-top: 2px solid #ddd; padding-top: 20px;">
                <span class="result-label">Predicted Sales:</span>
                <span class="result-value highlight" id="resultSales">-</span>
            </div>
            
            <div class="result-item" style="margin-top: 20px;">
                <span class="result-label">Model Accuracy (R²):</span>
                <span class="result-value" id="resultAccuracy">-</span>
            </div>
            <div class="accuracy-bar">
                <div class="accuracy-fill" id="accuracyBar" style="width: 0%"></div>
            </div>
        </div>
    </div>
    
    <script>
        const form = document.getElementById('predictionForm');
        const loading = document.getElementById('loading');
        const result = document.getElementById('result');
        const predictBtn = document.getElementById('predictBtn');
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const productId = document.getElementById('product').value;
            const days = document.getElementById('days').value;
            
            if (!productId || !days) {
                alert('Please fill in all fields');
                return;
            }
            
            // Show loading
            loading.classList.add('show');
            result.classList.remove('show');
            predictBtn.disabled = true;
            
            try {
                const formData = new FormData();
                formData.append('action', 'predict');
                formData.append('product_id', productId);
                formData.append('days', days);
                
                const response = await fetch('index.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Get product name
                    const productSelect = document.getElementById('product');
                    const productText = productSelect.options[productSelect.selectedIndex].text;
                    
                    // Update results
                    document.getElementById('resultProduct').textContent = productText;
                    document.getElementById('resultDays').textContent = days;
                    document.getElementById('resultSales').textContent = data.prediction.toFixed(2) + ' units';
                    document.getElementById('resultAccuracy').textContent = data.accuracy.toFixed(2) + '%';
                    
                    // Animate accuracy bar
                    setTimeout(() => {
                        document.getElementById('accuracyBar').style.width = data.accuracy + '%';
                    }, 100);
                    
                    result.classList.remove('error');
                    result.classList.add('show');
                } else {
                    // Show error
                    result.innerHTML = `
                        <div class="result-title">❌ Error</div>
                        <p style="color: #991b1b; margin-top: 10px;">${data.error || 'Failed to get prediction'}</p>
                    `;
                    result.classList.add('error');
                    result.classList.add('show');
                }
            } catch (error) {
                console.error('Error:', error);
                result.innerHTML = `
                    <div class="result-title">❌ Error</div>
                    <p style="color: #991b1b; margin-top: 10px;">An error occurred while processing your request.</p>
                `;
                result.classList.add('error');
                result.classList.add('show');
            } finally {
                loading.classList.remove('show');
                predictBtn.disabled = false;
            }
        });
    </script>
</body>
</html>