<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SURAT PERJANJIAN KERJASAMA - {{ $agreement->agreement_number ?? 'SPK' }}</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 40px;
            color: #1e293b;
            font-size: 13px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            color: #0f172a;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #64748b;
            font-family: monospace;
            font-size: 12px;
        }

        .badge {
            display: inline-block;
            background-color: #dcfce7;
            color: #166534;
            font-weight: bold;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            margin-top: 10px;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-top: 20px;
        }

        .signatures {
            margin-top: 50px;
            width: 100%;
        }

        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .stamp {
            margin-top: 40px;
            font-weight: bold;
            color: #059669;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()"
            style="background-color: #0f172a; color: white; border: none; padding: 10px 20px; font-weight: bold; border-radius: 8px; cursor: pointer;">
            🖨️ Cetak / Download PDF
        </button>
    </div>

    <div class="header">
        <img src="{{ asset('images/LOGO CIREVA.jpeg') }}" alt="CIREVA Logo"
            style="height: 70px; width: auto; margin-bottom: 12px; border-radius: 8px;">
        <h2>SURAT PERJANJIAN KERJASAMA (SPK)</h2>
        <p>Nomor Registrasi: {{ $agreement->agreement_number ?? 'SPK-00001-15PCT' }}</p>
        <div class="badge">STATUS: TERVERIFIKASI & DIGITAL SIGNED</div>
    </div>

    <p>Pada hari ini, disepakati Perjanjian Kerjasama Kemitraan Penyelenggaraan event Kebudayaan Cirebon antara:</p>

    <div class="section-title">1. PIHAK PERTAMA (PLATFORM)</div>
    <p><strong>Nama Instansi:</strong> Platform Kebudayaan CIREVA<br>
        <strong>Wewenang:</strong> Pengelola Sistem E-Ticketing & Publikasi event Kebudayaan Cirebon
    </p>

    <div class="section-title">2. PIHAK KEDUA (ORGANIZER MITRA)</div>
    <p><strong>Nama Organisasi:</strong> {{ $profile->organization_name ?? Auth::user()->name }}<br>
        <strong>Penanggung Jawab:</strong> {{ $profile->owner_name ?? Auth::user()->name }}<br>
        <strong>Nomor Telepon:</strong> {{ $profile->phone ?? '-' }}<br>
        <strong>Alamat Sekretariat:</strong> {{ $profile->address ?? '-' }}
    </p>

    <div class="section-title">3. PASAL KETENTUAN BAGI HASIL</div>
    <p>1. Pihak Kedua menyetujui pemotongan bagi hasil sebesar <strong>15% (lima belas persen)</strong> dari total
        transaksi tiket yang berhasil terjual melalui Platform CIREVA.<br>
        2. Sisa pendapatan sebesar 85% akan ditransfer otomatis ke rekening Pihak Kedua setelah verifikasi transaksi
        disetujui.<br>
        3. Perjanjian ini berlaku efektif sejak tanggal otorisasi digital oleh Administrator.</p>

    <table class="signatures">
        <tr>
            <td>
                <p>PIHAK PERTAMA<br><strong>Administrator CIREVA</strong></p>
                <div class="stamp">[ DIGITAL STAMP VALID ]</div>
            </td>
            <td>
                <p>PIHAK KEDUA<br><strong>{{ $profile->organization_name ?? Auth::user()->name }}</strong></p>
                <div class="stamp">[ SIGNED BY ORGANIZER ]</div>
            </td>
        </tr>
    </table>

</body>

</html>