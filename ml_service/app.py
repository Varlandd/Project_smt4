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
        
    print("[OK] System Ready: Model berhasil dimuat.")
except FileNotFoundError:
    print("[ERROR] Model belum di-train. Jalankan 'python train_model.py' terlebih dahulu.")
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


@app.route('/recommend', methods=['POST'])
def recommend_topsis():
    """
    TOPSIS (Technique for Order of Preference by Similarity to Ideal Solution)
    
    Input JSON:
    {
        "properties": [ { "id": "...", "nama": "...", "harga": ..., "luas_tanah": ..., 
                          "luas_bangunan": ..., "kamar_tidur": ..., "kamar_mandi": ..., "lokasi": "..." } ],
        "weights": { "harga": 5, "luas_tanah": 3, "luas_bangunan": 4, "kamar_tidur": 3, "kamar_mandi": 2 },
        "budget_max": 1000000000  (optional)
        "lokasi_filter": "Jember" (optional)
    }
    """
    try:
        data = request.json
        properties = data.get('properties', [])
        weights = data.get('weights', {})
        budget_max = data.get('budget_max', 0)
        lokasi_filter = data.get('lokasi_filter', '')

        if len(properties) == 0:
            return jsonify({'status': 'error', 'message': 'No properties provided'}), 400

        # Filter by lokasi if specified
        if lokasi_filter:
            properties = [p for p in properties if p.get('lokasi', '') == lokasi_filter]

        # Filter by budget if specified
        if budget_max and budget_max > 0:
            properties = [p for p in properties if (p.get('harga', 0) or 0) <= budget_max]

        if len(properties) == 0:
            return jsonify({
                'status': 'success',
                'data': {'rankings': [], 'method': 'TOPSIS', 'total': 0}
            })

        # Default weights
        w = {
            'harga':         weights.get('harga', 3),
            'luas_tanah':    weights.get('luas_tanah', 3),
            'luas_bangunan': weights.get('luas_bangunan', 3),
            'kamar_tidur':   weights.get('kamar_tidur', 3),
            'kamar_mandi':   weights.get('kamar_mandi', 2),
        }

        # Criteria types: cost (lower=better) or benefit (higher=better)
        criteria_types = {
            'harga': 'cost',
            'luas_tanah': 'benefit',
            'luas_bangunan': 'benefit',
            'kamar_tidur': 'benefit',
            'kamar_mandi': 'benefit',
        }

        criteria = list(w.keys())

        # Step 1: Membangun Matriks Keputusan (Tabel angka dari database)
        n = len(properties) # Jumlah rumah
        m = len(criteria)   # Jumlah kriteria (Harga, Luas, dll)
        matrix = np.zeros((n, m)) # Buat matriks kosong seukuran rumah x kriteria

        for i, prop in enumerate(properties):
            for j, crit in enumerate(criteria):
                val = prop.get(crit, 0)
                # Ambil nilai kriteria dari tiap rumah dan masukkan ke matriks
                matrix[i][j] = float(val) if val else 0.0

        # Step 2: Normalisasi Matriks (Menyamakan skala semua kriteria agar adil)
        # Menghitung akar jumlah kuadrat tiap kolom
        col_sums = np.sqrt(np.sum(matrix ** 2, axis=0))
        col_sums[col_sums == 0] = 1  # Hindari pembagian dengan nol
        norm_matrix = matrix / col_sums

        # Step 3: Normalisasi Bobot (Menyesuaikan dengan slider user)
        total_weight = sum(w.values())
        norm_weights = np.array([w[c] / total_weight for c in criteria])

        # Step 4: Matriks Terbobot (Hasil normalisasi dikalikan bobot user)
        weighted_matrix = norm_matrix * norm_weights

        # Step 5: Menentukan Solusi Ideal (A+) dan Anti-Ideal (A-)
        ideal_pos = np.zeros(m) # Titik terbaik
        ideal_neg = np.zeros(m) # Titik terburuk

        for j, crit in enumerate(criteria):
            if criteria_types[crit] == 'benefit':
                # Untuk kriteria benefit (Luas/Kamar): Ideal = Terbesar, Anti-ideal = Terkecil
                ideal_pos[j] = np.max(weighted_matrix[:, j])
                ideal_neg[j] = np.min(weighted_matrix[:, j])
            else:  # Kriteria Cost (Harga)
                # Untuk kriteria cost (Harga): Ideal = Termurah (Min), Anti-ideal = Termahal (Max)
                ideal_pos[j] = np.min(weighted_matrix[:, j])
                ideal_neg[j] = np.max(weighted_matrix[:, j])

        # Step 6: Menghitung Jarak ke Solusi Ideal dan Anti-Ideal
        # dist_pos = Jarak rumah ke titik terbaik (inginnya sekecil mungkin)
        dist_pos = np.sqrt(np.sum((weighted_matrix - ideal_pos) ** 2, axis=1))
        # dist_neg = Jarak rumah ke titik terburuk (inginnya sejauh mungkin)
        dist_neg = np.sqrt(np.sum((weighted_matrix - ideal_neg) ** 2, axis=1))

        # Step 7: Menghitung Skor Preferensi (Ranking Akhir)
        denom = dist_pos + dist_neg
        denom[denom == 0] = 1  # Hindari pembagian dengan nol
        # Rumus TOPSIS: Kedekatan relatif terhadap solusi ideal
        scores = dist_neg / denom

        # Menyusun Hasil Ranking
        rankings = []
        for i, prop in enumerate(properties):
            rankings.append({
                'id': str(prop.get('_id', prop.get('id', ''))),
                'nama': prop.get('nama', ''),
                'lokasi': prop.get('lokasi', ''),
                'harga': prop.get('harga', 0),
                'luas_tanah': prop.get('luas_tanah', 0),
                'luas_bangunan': prop.get('luas_bangunan', 0),
                'kamar_tidur': prop.get('kamar_tidur', 0),
                'kamar_mandi': prop.get('kamar_mandi', 0),
                'topsis_score': round(float(scores[i]) * 100, 2), # Skor diubah ke skala 0-100
                'dist_ideal': round(float(dist_pos[i]), 6),
                'dist_anti_ideal': round(float(dist_neg[i]), 6),
            })

        # Urutkan berdasarkan skor tertinggi (Descending)
        rankings.sort(key=lambda x: x['topsis_score'], reverse=True)

        # Berikan nomor urut ranking
        for idx, r in enumerate(rankings):
            r['rank'] = idx + 1

        return jsonify({
            'status': 'success',
            'data': {
                'rankings': rankings,
                'method': 'TOPSIS',
                'total': len(rankings),
                'weights_used': w,
                'criteria_types': criteria_types,
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
