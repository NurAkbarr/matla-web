<table>
    <thead>
        <tr>
            <th colspan="16" style="font-weight: bold; font-size: 14pt; color: #00703C;">
                KAMPUS MAHAD TA'LIM LUGHOH AROBIYYAH (MATLA)
            </th>
        </tr>
        <tr>
            <th colspan="16" style="font-weight: bold; font-size: 11pt;">
                LEMBAR KERJA REKAPITULASI PEMBAYARAN SPP PER SEMESTER - {{ strtoupper($periodeLabel) }}
            </th>
        </tr>
        <tr>
            <th colspan="16" style="font-size: 9pt; color: #555555;">
                Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i') }} | Program Studi: {{ $selectedProdi ?: 'Semua Prodi' }} | Semester Mhs: {{ $selectedSemester ?: 'Semua' }} | Status: {{ $statusAkademik }}
            </th>
        </tr>
        <tr>
            <th colspan="16"></th>
        </tr>
        <tr>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: center; border: 1px solid #000000;">NO</th>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: center; border: 1px solid #000000;">NIM</th>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: left; border: 1px solid #000000;">NAMA MAHASISWA & PRODI</th>
            @foreach($daftarBulan as $b)
                <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ strtoupper($b['nama']) }}</th>
            @endforeach
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: right; border: 1px solid #000000;">TARIF SPP (RP)</th>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: center; border: 1px solid #000000;">TGK</th>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: right; border: 1px solid #000000;">TAGIHAN (RP)</th>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: right; border: 1px solid #000000;">DIBAYAR (RP)</th>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: right; border: 1px solid #000000;">SISA (RP)</th>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: center; border: 1px solid #000000;">STATUS</th>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: left; border: 1px solid #000000;">KETERANGAN</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rekapItems as $index => $item)
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
                <td style="text-align: center; border: 1px solid #000000; font-family: monospace;">{{ $item['nim'] }}</td>
                <td style="border: 1px solid #000000;">
                    <b>{{ $item['nama'] }}</b> ({{ $item['prodi'] }} - Smst {{ $item['semester_mhs'] }})
                </td>
                @foreach($item['matriks_bulan'] as $m)
                    <td style="text-align: center; border: 1px solid #000000; {{ $m['is_lunas'] ? 'background-color: #D1FAE5; color: #047857; font-weight: bold;' : (!empty($m['is_sebagian']) ? 'background-color: #FEF3C7; color: #B45309; font-weight: bold;' : 'color: #9CA3AF;') }}">
                        @if($m['is_lunas'])
                            ✔ LUNAS
                        @elseif(!empty($m['is_sebagian']))
                            ◐ SEBAGIAN ({{ number_format($m['nominal_bayar'], 0, ',', '.') }})
                        @else
                            -
                        @endif
                    </td>
                @endforeach
                <td style="text-align: right; border: 1px solid #000000;">{{ $item['nominal_spp'] }}</td>
                <td style="text-align: center; border: 1px solid #000000; font-weight: bold; {{ $item['bulan_menunggak'] > 0 ? 'color: #DC2626;' : 'color: #047857;' }}">
                    {{ $item['bulan_menunggak'] }}
                </td>
                <td style="text-align: right; border: 1px solid #000000;">{{ $item['tagihan'] }}</td>
                <td style="text-align: right; border: 1px solid #000000; color: #047857; font-weight: bold;">{{ $item['total_dibayar'] }}</td>
                <td style="text-align: right; border: 1px solid #000000; {{ $item['sisa_tunggakan'] > 0 ? 'color: #DC2626; font-weight: bold;' : '' }}">
                    {{ $item['sisa_tunggakan'] }}
                </td>
                <td style="text-align: center; border: 1px solid #000000; font-weight: bold; {{ $item['status'] === 'Lunas' ? 'background-color: #D1FAE5; color: #047857;' : ($item['status'] === 'Sebagian' ? 'background-color: #FEF3C7; color: #B45309;' : 'background-color: #FEE2E2; color: #B91C1C;') }}">
                    {{ strtoupper($item['status']) }}
                </td>
                <td style="border: 1px solid #000000;">{{ $item['metode'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="16" style="text-align: center; border: 1px solid #000000; padding: 20px;">
                    Tidak ada data mahasiswa yang cocok dengan kriteria filter.
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="9" style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: right; border: 1px solid #000000;">
                TOTAL KESELURUHAN ({{ count($rekapItems) }} Mahasiswa):
            </th>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: right; border: 1px solid #000000;">-</th>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: center; border: 1px solid #000000;">
                {{ $rekapItems ? array_sum(array_column($rekapItems, 'bulan_menunggak')) : 0 }}
            </th>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: right; border: 1px solid #000000;">
                {{ $rekapItems ? array_sum(array_column($rekapItems, 'tagihan')) : 0 }}
            </th>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: right; border: 1px solid #000000;">
                {{ $summary['total_terkumpul'] }}
            </th>
            <th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: right; border: 1px solid #000000;">
                {{ $summary['total_tunggakan'] }}
            </th>
            <th colspan="2" style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: center; border: 1px solid #000000;">
                {{ $summary['persentase_lunas'] }}% Terkumpul
            </th>
        </tr>
    </tfoot>
</table>
