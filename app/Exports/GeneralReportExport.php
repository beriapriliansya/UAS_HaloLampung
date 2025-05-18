<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Agar lebar kolom otomatis
use Maatwebsite\Excel\Concerns\WithEvents; // Untuk styling (opsional)
use Maatwebsite\Excel\Events\AfterSheet; // Event untuk styling

class GeneralReportExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $view;
    protected $data;

    public function __construct(string $view, array $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        // Render view Blade yang sama dengan yg ditampilkan di layar/PDF
        // tapi pastikan view ini hanya berisi tabel HTML sederhana
        return view($this->view, $this->data);
    }

     /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Contoh: Membuat header bold (opsional)
                $event->sheet->getDelegate()->getStyle('A1:' . $event->sheet->getDelegate()->getHighestColumn() . '1')->getFont()->setBold(true);
            },
        ];
    }
}