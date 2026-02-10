import pandas as pd
import mysql.connector

# Read Excel file
print("📂 Reading data.xlsx...")
df = pd.read_excel('data.xlsx')

# Convert product_id to integer (removes .0)
df['product_id'] = df['product_id'].astype(int)

print(f"✅ Loaded {len(df)} records")
print("\nPreview:")
print(df.head())

# Connect to database
print("\n📊 Connecting to database...")
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="root",
    database="my_database"
)
cursor = conn.cursor()

# First, let's make sure the table can handle large product IDs
print("\n🔧 Checking table structure...")
cursor.execute("""
    ALTER TABLE goodsout 
    MODIFY COLUMN product_id INT
""")

# Insert data
print("\n📥 Inserting data into goodsout table...")
for index, row in df.iterrows():
    query = """
        INSERT INTO goodsout (product_id, product_name, quantity, date)
        VALUES (%s, %s, %s, %s)
    """
    cursor.execute(query, (int(row['product_id']), row['product_name'], int(row['quantity']), row['date']))

conn.commit()
print(f"✅ Successfully inserted {len(df)} records!")

cursor.close()
conn.close()
print("\n🎉 Done! You can now run train_model.py")