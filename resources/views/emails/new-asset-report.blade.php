<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Laporan Aset Baru</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 24px; color: #1e293b; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; }
        .header { background: #0f172a; padding: 28px 32px; color: #ffffff; }
        .header h1 { margin: 0 0 8px 0; font-size: 20px; font-weight: 800; letter-spacing: -0.02em; }
        .badge-source { display: inline-block; background: rgba(13, 148, 136, 0.2); border: 1px solid #14b8a6; color: #2dd4bf; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; }
        .content { padding: 32px; }
        .alert-box { background: #f0fdfa; border: 1px solid #ccfbf1; border-left: 4px solid #0d9488; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-size: 13px; color: #134e4a; }
        .table-info { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .table-info td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
        .table-info td.label { font-weight: 600; color: #64748b; width: 35%; }
        .table-info td.val { font-weight: 700; color: #0f172a; }
        .badge-cond { display: inline-block; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; background: #fee2e2; color: #991b1b; }
        .badge-use { display: inline-block; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; background: #ccfbf1; color: #0f766e; }
        .desc-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 16px; border-radius: 12px; font-size: 13px; line-height: 1.6; color: #334155; margin-bottom: 28px; }
        .btn-action { display: block; text-align: center; background: #008080; color: #ffffff !important; text-decoration: none; padding: 14px 24px; border-radius: 10px; font-size: 13px; font-weight: 700; }
        .footer { background: #f8fafc; padding: 20px 32px; font-size: 11px; color: #94a3b8; text-align: center; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div style="margin-bottom: 12px;">
                <span class="badge-source">&#x2728; {{ $source }}</span>
            </div>
            <h1>Laporan Aset Desa Baru Masuk</h1>
            <p style="margin: 0; font-size: 13px; color: #94a3b8;">Notifikasi Realtime Sistem Pemantauan Aset Daerah Kabupaten Gresik</p>
        </div>

        <div class="content">
            <div class="alert-box">
                <strong>Pemberitahuan Sistem:</strong> Laporan aset non-aktif / terlantar baru saja dikirimkan dan siap untuk ditinjau oleh aparatur pemerintah desa / kecamatan.
            </div>

            <table class="table-info">
                <tr>
                    <td class="label">Judul Objek Aset:</td>
                    <td class="val">{{ $report->title }}</td>
                </tr>
                <tr>
                    <td class="label">Wilayah Desa / Lokasi:</td>
                    <td class="val">Desa {{ $report->village ? $report->village->name : 'Gresik' }} (Kecamatan {{ $report->village && $report->village->district ? $report->village->district->name : 'Manyar' }})</td>
                </tr>
                <tr>
                    <td class="label">Kategori Aset:</td>
                    <td class="val">{{ $report->category ? $report->category->name : 'Aset Desa' }}</td>
                </tr>
                <tr>
                    <td class="label">Kondisi Fisik:</td>
                    <td class="val"><span class="badge-cond">{{ $report->condition_label }}</span></td>
                </tr>
                <tr>
                    <td class="label">Rekomendasi Pemanfaatan:</td>
                    <td class="val"><span class="badge-use">{{ $report->suggested_use ?? 'Optimalisasi UMKM' }}</span></td>
                </tr>
                <tr>
                    <td class="label">Alamat / Koordinat:</td>
                    <td class="val">{{ $report->address ?? '-' }} <br><span style="font-size: 11px; color: #64748b;">(Lat: {{ $report->latitude }}, Lng: {{ $report->longitude }})</span></td>
                </tr>
                <tr>
                    <td class="label">Pelapor:</td>
                    <td class="val">{{ $report->user ? $report->user->name : 'Warga Masyarakat' }} ({{ $report->user ? $report->user->email : '-' }})</td>
                </tr>
                <tr>
                    <td class="label">Waktu Dilaporkan:</td>
                    <td class="val">{{ $report->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</td>
                </tr>
            </table>

            @if($report->description)
                <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Deskripsi & Analisis Pelapor / AI:</div>
                <div class="desc-box">
                    {{ $report->description }}
                </div>
            @endif

            <div style="text-align: center; margin-top: 10px;">
                <a href="{{ url('/dashboard/village') }}" class="btn-action">
                    Buka Dashboard & Verifikasi Laporan &rarr;
                </a>
            </div>
        </div>

        <div class="footer">
            <strong>KENTONGAN AI Platform &bull; Pemerintah Kabupaten Gresik</strong><br>
            Bappedalitbang & Dinas Pemberdayaan Masyarakat dan Desa (DPMD)<br>
            Email ini dikirimkan otomatis oleh sistem SMTP KENTONGAN AI ke aparatur dan pengawas daerah.
        </div>
    </div>
</body>
</html>
