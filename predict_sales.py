# predict_sales.py - Predict Future Sales

import pickle
from datetime import datetime, timedelta
import numpy as np

# ===== 1. LOAD TRAINED MODELS =====
print("📦 Loading trained models...")
with open('sales_models.pkl', 'rb') as f:
    models = pickle.load(f)

print(f"✅ Loaded models for {len(models)} products\n")

# ===== 2. SHOW AVAILABLE PRODUCTS =====
print("🛒 Available products:")
for pid, info in models.items():
    print(f"Product ID: {pid} | Name: {info['product_name']}")

# ===== 3. USER INPUT =====
product_id = int(input("\nEnter Product ID to predict: "))
days_ahead = int(input("Predict sales for how many days ahead? "))

if product_id not in models:
    print("❌ Invalid Product ID")
    exit()

product = models[product_id]
model = product['model']

# ===== 4. CALCULATE DAYS SINCE START =====
today = datetime.today()
days_since_start = (today - product['first_date']).days
future_day = days_since_start + days_ahead

# ===== 5. MAKE PREDICTION =====
predicted_sales = model.predict([[future_day]])[0]

# Avoid negative predictions
predicted_sales = max(0, predicted_sales)

# ===== 6. OUTPUT =====
print("\n📈 SALES PREDICTION RESULT")
print(f"Product: {product['product_name']}")
print(f"Days ahead: {days_ahead}")
print(f"Predicted sales: {predicted_sales:.2f} units")
print(f"Model accuracy (R²): {product['score']:.2%}")
