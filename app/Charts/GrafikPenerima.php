<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\DaftarLaporan;

class GrafikPenerima
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        // Ambil data jumlah penerima per provinsi
        $data = DaftarLaporan::select('provinsi', \DB::raw('SUM(jumlah_penerima) as total_penerima'))
            ->groupBy('provinsi')
            ->get();

        // Persiapkan data untuk grafik
        $provinsi = $data->pluck('provinsi')->toArray();
        $jumlahPenerima = $data->pluck('total_penerima')->toArray();

        // Buat grafik bar chart
        return $this->chart->barChart()
            ->setTitle('Jumlah Penerima per Provinsi')
            ->setSubtitle('Grafik Jumlah Penerima per Provinsi')
            ->addData('Jumlah Penerima', $jumlahPenerima)
            ->setXAxis($provinsi);
    }
}
