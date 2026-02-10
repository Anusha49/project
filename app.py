import pickle
import numpy as np
import os

# Load models dictionary
pkl_path = os.path.join(os.path.dirname(__file__), "sales_models.pkl")

with open(pkl_path, "rb") as f:
    models = pickle.load(f)   # dict of models

# Select one product (for now)
product_id = list(models.keys())[0]
product_data = models[product_id]

model = product_data['model']
product_name = product_data['product_name']

# Predict for next 10 days
days_ahead = 10
input_data = np.array([[days_ahead]])

prediction = model.predict(input_data)

# PRINT RESULT (PHP WILL READ THIS)
print(f"Expected Sales for {product_name}: Rs. {round(prediction[0], 2)}")
