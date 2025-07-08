<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\ActivityDocument;
use Carbon\Carbon;

class ActivityDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $activities = Activity::all();

        $documentTemplates = [
            [
                'title' => 'Laporan Bulanan {month} {year}',
                'description' => 'Laporan komprehensif aktivitas dan pencapaian selama bulan {month} {year}.',
                'onedrive_link' => 'https://1drv.ms/w/s!example-monthly-report-{month}-{year}'
            ],
            [
                'title' => 'Dokumentasi Prosedur Standar',
                'description' => 'Dokumentasi lengkap prosedur operasional standar untuk aktivitas ini.',
                'onedrive_link' => 'https://1drv.ms/w/s!example-sop-documentation'
            ],
            [
                'title' => 'Analisis Hasil {month}',
                'description' => 'Analisis mendalam terhadap hasil dan temuan dari aktivitas bulan {month}.',
                'onedrive_link' => 'https://1drv.ms/x/s!example-analysis-{month}'
            ],
            [
                'title' => 'Presentasi Stakeholder',
                'description' => 'Materi presentasi untuk stakeholder mengenai progress dan hasil aktivitas.',
                'onedrive_link' => 'https://1drv.ms/p/s!example-stakeholder-presentation'
            ],
            [
                'title' => 'Data Mentah {month}',
                'description' => 'Dataset mentah yang dikumpulkan selama periode {month} untuk keperluan analisis lanjutan.',
                'onedrive_link' => 'https://1drv.ms/x/s!example-raw-data-{month}'
            ]
        ];

        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        foreach ($activities as $activity) {
            // Create 4-6 documents per activity
            $documentCount = rand(4, 6);
            
            for ($i = 0; $i < $documentCount; $i++) {
                $template = $documentTemplates[array_rand($documentTemplates)];
                $month = $months[array_rand($months)];
                $year = rand(2023, 2025);
                
                // Replace placeholders in template
                $title = str_replace(['{month}', '{year}'], [$month, $year], $template['title']);
                $description = str_replace(['{month}', '{year}'], [$month, $year], $template['description']);
                $onedrive_link = str_replace(['{month}', '{year}'], [strtolower($month), $year], $template['onedrive_link']);
                
                // Generate random date within the last 2 years
                $documentDate = Carbon::now()->subDays(rand(1, 730));
                
                ActivityDocument::create([
                    'activity_id' => $activity->id,
                    'title' => $title,
                    'description' => $description,
                    'onedrive_link' => $onedrive_link,
                    'document_date' => $documentDate
                ]);
            }
        }

        // Add some specific high-quality documents for demonstration
        $specificDocuments = [
            [
                'activity_name' => 'Kampanye Media Sosial',
                'documents' => [
                    [
                        'title' => 'Strategi Konten Media Sosial 2025',
                        'description' => 'Dokumen strategis yang menguraikan pendekatan komprehensif untuk kampanye media sosial tahun 2025, termasuk target audience, platform prioritas, dan KPI.',
                        'onedrive_link' => 'https://1drv.ms/w/s!social-media-strategy-2025',
                        'document_date' => Carbon::create(2025, 1, 15)
                    ],
                    [
                        'title' => 'Template Konten Visual',
                        'description' => 'Kumpulan template desain untuk konten visual media sosial yang konsisten dengan brand guidelines organisasi.',
                        'onedrive_link' => 'https://1drv.ms/f/s!visual-content-templates',
                        'document_date' => Carbon::create(2024, 12, 20)
                    ]
                ]
            ],
            [
                'activity_name' => 'Validasi Data Survei',
                'documents' => [
                    [
                        'title' => 'Protokol Validasi Data Terbaru',
                        'description' => 'Protokol terbaru untuk validasi data survei yang mencakup metode deteksi outlier, konsistensi data, dan standar kualitas.',
                        'onedrive_link' => 'https://1drv.ms/w/s!data-validation-protocol-v2',
                        'document_date' => Carbon::create(2025, 2, 1)
                    ]
                ]
            ]
        ];

        foreach ($specificDocuments as $activityDoc) {
            $activity = Activity::where('name', $activityDoc['activity_name'])->first();
            
            if ($activity) {
                foreach ($activityDoc['documents'] as $docData) {
                    ActivityDocument::create([
                        'activity_id' => $activity->id,
                        'title' => $docData['title'],
                        'description' => $docData['description'],
                        'onedrive_link' => $docData['onedrive_link'],
                        'document_date' => $docData['document_date']
                    ]);
                }
            }
        }
    }
}
