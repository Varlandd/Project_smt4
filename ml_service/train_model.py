import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestRegressor
from sklearn.preprocessing import StandardScaler
import pickle
import os

# Fungsi untuk membuat mock dataset jika file kaggle tidak ada
# Format ini disesuaikan dengan dataset Kaggle jabodetabek
def generate_mock_data(n_samples=500):
    np.random.seed(42)
    
    # Lokasi mock (Jabodetabek)
    lokasi_list = ['Jakarta Selatan', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Utara', 'Jakarta Pusat', 
                   'Depok', 'Tangerang', 'Tangerang Selatan', 'Bekasi', 'Bogor']
    
    data = []
    for _ in range(n_samples):
        kamar_tidur = np.random.randint(2, 6)
        kamar_mandi = np.random.randint(1, 4)
        
        # Luas bangunan biasanya terkait dengan jumlah kamar
        luas_bangunan = (kamar_tidur * 25) + (kamar_mandi * 10) + np.random.randint(20, 100)
        
        # Luas tanah biasanya sama atau lebih besar dari luas bangunan
        luas_tanah = luas_bangunan + np.random.randint(0, 100)
        
        lokasi = np.random.choice(lokasi_list)
        
        # Harga dasar (dalam Rupiah)
        base_price = 300000000
        
        # Faktor pengali
        price = base_price + (luas_bangunan * 4000000) + (luas_tanah * 3000000)
        price += (kamar_tidur * 50000000) + (kamar_mandi * 25000000)
        
        # Faktor lokasi
        if 'Jakarta Selatan' in lokasi or 'Jakarta Pusat' in lokasi:
            price *= 1.5
        elif 'Tangerang Selatan' in lokasi:
            price *= 1.2
        elif 'Bogor' in lokasi or 'Depok' in lokasi:
            price *= 0.8
            
        # Random noise
        price = price * np.random.uniform(0.9, 1.1)
        
        data.append({
            'kamar_tidur': kamar_tidur,
            'kamar_mandi': kamar_mandi,
            'luas_bangunan': luas_bangunan,
            'luas_tanah': luas_tanah,
            'lokasi': lokasi,
            'harga': int(price)
        })
        
    df = pd.DataFrame(data)
    df.to_csv('rumah_jabodetabek.csv', index=False)
    print("Dataset rumah_jabodetabek.csv berhasil dibuat.")
    return df

def train_model():
    print("Memulai proses training model...")
    
    # Cek apakah dataset asli ada, jika tidak pakai mock data
    if os.path.exists('rumah_jabodetabek.csv'):
        df = pd.read_csv('rumah_jabodetabek.csv')
    else:
        print("Dataset tidak ditemukan. Membuat mock dataset untuk contoh...")
        df = generate_mock_data()
        
    # Preprocessing
    # Pisahkan fitur (X) dan target (y)
    
    # Karena lokasi adalah kategorikal, kita butuh One-Hot Encoding
    X = pd.get_dummies(df.drop('harga', axis=1), columns=['lokasi'])
    y = df['harga']
    
    # Simpan nama kolom fitur untuk digunakan saat prediksi nanti
    feature_columns = list(X.columns)
    with open('feature_columns.pkl', 'wb') as f:
        pickle.dump(feature_columns, f)
        
    # Standardize data numerik 
    scaler = StandardScaler()
    num_cols = ['luas_tanah', 'luas_bangunan', 'kamar_tidur', 'kamar_mandi']
    X[num_cols] = scaler.fit_transform(X[num_cols])
    
    # Split data training & testing
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    
    # Train model Random Forest Regressor
    # Random Forest sangat bagus untuk memodelkan harga rumah karena bisa menangkap pola non-linear
    model = RandomForestRegressor(n_estimators=100, random_state=42)
    model.fit(X_train, y_train)
    
    # Evaluasi akurasi
    score = model.score(X_test, y_test)
    print(f"Model berhasil dilatih dengan akurasi R^2: {score:.2f}")
    
    # Simpan model dan scaler menggunakan pickle
    with open('rumah_model.pkl', 'wb') as f:
        pickle.dump(model, f)
        
    with open('scaler.pkl', 'wb') as f:
        pickle.dump(scaler, f)
        
    print("Model disimpan sebagai 'rumah_model.pkl'")
    print("Scaler disimpan sebagai 'scaler.pkl'")

if __name__ == '__main__':
    train_model()
