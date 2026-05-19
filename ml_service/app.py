from flask import Flask, request, jsonify
import pandas as pd
import joblib
import traceback

app = Flask(__name__)

# 1. Load ketiga file PKL kita (PASTIKAN NAMANYA SAMA PERSIS)
try:
    knn_model = joblib.load('knn_model_rumah.pkl')
    scaler = joblib.load('scaler_rumah.pkl')
    kolom_fitur = joblib.load('kolom_fitur.pkl')
    print("BINGGO! Model, Scaler, dan Struktur Kolom berhasil dimuat!")
except Exception as e:
    print(f"ERROR: Gagal memuat file .pkl. Pastikan file ada di folder ini. Detail: {e}")

@app.route('/predict', methods=['POST'])
def predict_cluster():
    try:
        data = request.get_json()

        # Buat cetakan data kosong
        input_data = pd.DataFrame(0, index=[0], columns=kolom_fitur)

        # Masukkan input dari user Laravel
        input_data['harga'] = float(data.get('harga', 0))
        input_data['luas_tanah'] = float(data.get('luas_tanah', 0))
        input_data['luas_bangunan'] = float(data.get('luas_bangunan', 0))
        input_data['kamar_tidur'] = int(data.get('kamar_tidur', 0))
        input_data['kamar_mandi'] = int(data.get('kamar_mandi', 0))

        # Handle One-Hot Encoding Kota & Posisi
        nama_kolom_kota = f"kota_{data.get('kota')}"
        if nama_kolom_kota in input_data.columns:
            input_data[nama_kolom_kota] = 1

        nama_kolom_posisi = f"posisi_kota_{data.get('posisi_kota')}"
        if nama_kolom_posisi in input_data.columns:
            input_data[nama_kolom_posisi] = 1

        # Scale data & Prediksi
        data_scaled = scaler.transform(input_data)
        hasil_prediksi = knn_model.predict(data_scaled)

        return jsonify({
            'status': 'success',
            'predicted_cluster': int(hasil_prediksi[0])
        }), 200

    except Exception as e:
        print(traceback.format_exc())
        return jsonify({
            'status': 'error',
            'message': str(e)
        }), 400

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000, debug=True)