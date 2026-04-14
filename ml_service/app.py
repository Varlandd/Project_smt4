from flask import Flask, request, jsonify
import pandas as pd
import pickle
import numpy as np

app = Flask(__name__)

# Load model dan tools saat server start
try:
    with open('rumah_model.pkl', 'rb') as f:
        model = pickle.load(f)
        
    with open('scaler.pkl', 'rb') as f:
        scaler = pickle.load(f)
        
    with open('feature_columns.pkl', 'rb') as f:
        feature_columns = pickle.load(f)
        
    print("✅ System Ready: Model berhasil dimuat.")
except FileNotFoundError:
    print("❌ Error: Model belum di-train. Jalankan 'python train_model.py' terlebih dahulu.")
    exit(1)

@app.route('/', methods=['GET'])
def health_check():
    return jsonify({
        'status': 'success',
        'message': 'Rumah ML Prediction API is running',
        'model_type': 'RandomForestRegressor'
    })

@app.route('/predict', methods=['POST'])
def predict_price():
    try:
        # Ambil input JSON
        data = request.json
        
        # Validasi input
        required_fields = ['luas_tanah', 'luas_bangunan', 'kamar_tidur', 'kamar_mandi', 'lokasi']
        for field in required_fields:
            if field not in data:
                return jsonify({
                    'status': 'error',
                    'message': f'Field {field} is required'
                }), 400
                
        # Format input menjadi DataFrame dengan struktur yang sama seperti feature_columns
        input_dict = {col: 0 for col in feature_columns}
        
        # Set nilai numerik
        input_dict['luas_tanah'] = data['luas_tanah']
        input_dict['luas_bangunan'] = data['luas_bangunan']
        input_dict['kamar_tidur'] = data['kamar_tidur']
        input_dict['kamar_mandi'] = data['kamar_mandi']
        
        # Set nilai lokasi (One-Hot Encoding)
        lokasi_col = f"lokasi_{data['lokasi']}"
        if lokasi_col in feature_columns:
            input_dict[lokasi_col] = 1
            
        input_df = pd.DataFrame([input_dict])
        
        # Standardize numerik (ingat: harus pakai kolom numerik saja)
        num_cols = ['luas_tanah', 'luas_bangunan', 'kamar_tidur', 'kamar_mandi']
        input_df[num_cols] = scaler.transform(input_df[num_cols])
        
        # Prediksi
        prediction_result = (model.predict(input_df)[0])
        
        # Hitung range akurasi (misal +- 5% untuk memberikan rentang harga)
        min_price = int(prediction_result * 0.95)
        max_price = int(prediction_result * 1.05)
        
        return jsonify({
            'status': 'success',
            'data': {
                'prediksi_harga': int(prediction_result),
                'prediksi_formatted': f"Rp {int(prediction_result):,}".replace(',', '.'),
                'range_harga': {
                    'min': min_price,
                    'max': max_price
                },
                'input': data
            }
        })
        
    except Exception as e:
        return jsonify({
            'status': 'error',
            'message': str(e)
        }), 500

if __name__ == '__main__':
    # Berjalan di port 5000
    app.run(host='0.0.0.0', port=5000, debug=True)
