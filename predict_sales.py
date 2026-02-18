import sys
import os
import pickle
from datetime import datetime

if len(sys.argv) != 3:
    print("Usage: predict_sales.py <product_id> <days_ahead>")
    sys.exit(1)

try:
    product_id = int(sys.argv[1])
    days_ahead = int(sys.argv[2])
except ValueError:
    print("product_id and days_ahead must be integers")
    sys.exit(1)

if days_ahead < 1 or days_ahead > 365:
    print("days_ahead must be between 1 and 365")
    sys.exit(1)

script_dir = os.path.dirname(os.path.abspath(__file__))
model_path = os.path.join(script_dir, "sales_models.pkl")

if not os.path.exists(model_path):
    print("sales_models.pkl not found. Run train_model.py first.")
    sys.exit(1)

with open(model_path, "rb") as f:
    models = pickle.load(f)

if product_id not in models:
    ids = ", ".join(str(k) for k in models.keys())
    print(f"No model for product_id {product_id}. Available: {ids}")
    sys.exit(1)

info = models[product_id]

model      = info.get("model")
first_date = info.get("first_date", datetime(2020, 1, 1))
r2         = info.get("score", 0.0)
avg_sales  = info.get("avg_sales", 0.0)

if isinstance(first_date, str):
    first_date = datetime.fromisoformat(first_date)

today     = datetime.now()
day_start = (today - first_date).days
day_end   = day_start + days_ahead

val_start = max(0.0, float(model.predict([[day_start]])[0]))
val_end   = max(0.0, float(model.predict([[day_end]])[0]))

total    = ((val_start + val_end) / 2.0) * days_ahead
accuracy = max(0.0, min(100.0, r2 * 100))

coef = float(getattr(model, "coef_", [0])[0])
if coef > 0.05:
    trend = "UP"
elif coef < -0.05:
    trend = "DOWN"
else:
    trend = "STABLE"

print(f"PREDICTION:{total:.4f}")
print(f"ACCURACY:{accuracy:.4f}")
print(f"TREND:{trend}")
print(f"AVG_DAILY:{float(avg_sales):.4f}")