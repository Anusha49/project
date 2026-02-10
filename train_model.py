# train_model.py - Linear Regression Model Training
# This script trains a model to predict future sales

import pandas as pd
import mysql.connector
from sklearn.linear_model import LinearRegression
from datetime import datetime, timedelta
import pickle
import json

# ===== 1. CONNECT TO DATABASE =====
print("📊 Connecting to database...")
try:
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="root",
        database="my_database"
    )
    print("✅ Connected successfully!")
except Exception as e:
    print(f"❌ Connection failed: {e}")
    exit()

# ===== 2. FETCH SALES DATA =====
print("\n📥 Fetching sales data from goodsout table...")
query = """
    SELECT product_id, product_name, quantity, date 
    FROM goodsout 
    WHERE date IS NOT NULL 
    ORDER BY date
"""
df = pd.read_sql(query, conn)
conn.close()

print(f"✅ Loaded {len(df)} sales records")
print("\nSample data:")
print(df.head())

if len(df) < 5:
    print("\n⚠️ Warning: You need at least 5 sales records to train the model!")
    print("Add more sales data and try again.")
    exit()

# ===== 3. PREPARE DATA FOR EACH PRODUCT =====
print("\n🔧 Preparing data for training...")

# Convert date to datetime
df['date'] = pd.to_datetime(df['date'])

# Group by product and date to get total quantity sold per day
daily_sales = df.groupby(['product_id', 'product_name', 'date'])['quantity'].sum().reset_index()

# Create a dictionary to store models for each product
models = {}
product_info = {}

# ===== 4. TRAIN MODEL FOR EACH PRODUCT =====
print("\n🤖 Training Linear Regression models...\n")

for product_id in daily_sales['product_id'].unique():
    product_data = daily_sales[daily_sales['product_id'] == product_id].copy()
    product_name = product_data['product_name'].iloc[0]
    
    if len(product_data) < 3:
        print(f"⚠️ Skipping {product_name} (ID: {product_id}) - Not enough data")
        continue
    
    # Sort by date
    product_data = product_data.sort_values('date')
    
    # Create features: days since first sale
    first_date = product_data['date'].min()
    product_data['days_since_start'] = (product_data['date'] - first_date).dt.days
    
    # Prepare X (features) and y (target)
    X = product_data[['days_since_start']].values
    y = product_data['quantity'].values
    
    # Train Linear Regression Algorithm
    model = LinearRegression()
    model.fit(X, y)
    
    # Calculate model accuracy (R² score)
    score = model.score(X, y)
    
    # Store model and info
    models[product_id] = {
        'model': model,
        'first_date': first_date,
        'product_name': product_name,
        'score': score,
        'avg_sales': product_data['quantity'].mean(),
        'total_sales': product_data['quantity'].sum(),
        'days_tracked': int(X.max())
    }
    
    print(f"✅ Trained model for: {product_name}")
    print(f"   Product ID: {product_id}")
    print(f"   Accuracy (R² score): {score:.2%}")
    print(f"   Average daily sales: {product_data['quantity'].mean():.2f} units")
    print(f"   Formula: Sales = {model.coef_[0]:.4f} × days + {model.intercept_:.2f}\n")

# ===== 5. SAVE MODELS =====
print("💾 Saving trained models...")
with open('sales_models.pkl', 'wb') as f:
    pickle.dump(models, f)

print("✅ Models saved successfully!")
print(f"\n🎉 Training complete! {len(models)} products ready for prediction.")
print("\nNext step: Run predict_sales.py to make predictions!")