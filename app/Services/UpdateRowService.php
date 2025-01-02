<?php

namespace App\Services;

use App\Models\Criterion;
use App\Services\ScoringService;

class UpdateRowService
{
    public static function update($id, $field, $value)
    {
        $criterion = Criterion::find($id);

        if ($criterion) {
            // Handle both numeric and categorical fields dynamically
            $criterion->$field = is_numeric($value) && !in_array($criterion->pilihan_jawaban, ['Ya/Tidak', 'A/B/C', 'A/B/C/D', 'A/B/C/D/E'])
                ? (float) $value
                : $value;

                
            // Update 'last_updated_by' only for specific fields
            if (in_array($field, ['jawaban_unit', 'catatan_unit', 'bukti_dukung'])) {
                $criterion->last_updated_by = auth()->user()->name;
            }

            // Save the new value immediately to ensure calculations use the latest data
            $criterion->save();

            // Handle cascading updates for specific fields
            if (
                $criterion->penilaian === '- Kepala satuan kerja' ||
                $criterion->penilaian === '- Pejabat yang diwajibkan menyampaikan LHKPN' ||
                $criterion->penilaian === '- Lainnya' ||
                $criterion->penilaian === '- Jumlah yang sudah melaporkan LHKPN'
            ) {
                self::updatePercentageFields($criterion->category);
            }

            // Handle cascading updates for specific fields
            if (
                $criterion->penilaian === '- Pejabat administrator (eselon III)' ||
                $criterion->penilaian === '- Pejabat pengawas (eselon IV)' ||
                $criterion->penilaian === '- Jumlah Fungsional dan Pelaksana' ||
                $criterion->penilaian === '- Jumlah yang sudah melaporkan Non LHKPN'
            ) {
                self::updatePercentageFields($criterion->category);
            }

            // Check if this is a "Jumlah" field that impacts a percentage field
            if (str_contains($criterion->penilaian, 'Jumlah')) {
                self::updatePercentageFields($criterion->category);
            } else {
                // Dynamically calculate scores for regular fields
                if ($field === 'jawaban_unit' || $field === 'jawaban_tpi') {
                    if ($criterion->pilihan_jawaban === '%') {
                        // Automate "jawaban" for percentages
                        $automatedValues = self::automatePercentageJawaban($criterion);

                        if ($field === 'jawaban_unit') {
                            $criterion->jawaban_unit = $automatedValues['jawaban_unit'];
                            $criterion->nilai_unit = $criterion->jawaban_unit > 1 ? 1 : $criterion->jawaban_unit; // Cap at 1
                        }

                        if ($field === 'jawaban_tpi') {
                            $criterion->jawaban_tpi = $automatedValues['jawaban_tpi'];
                            $criterion->nilai_tpi = $criterion->jawaban_tpi > 1 ? 1 : $criterion->jawaban_tpi; // Cap at 1
                        }
                    } else {
                        // Handle regular scoring using ScoringService
                        $criterion->{'nilai_' . ($field === 'jawaban_unit' ? 'unit' : 'tpi')} =
                            ScoringService::calculateScore($value, $criterion->pilihan_jawaban);
                    }
                }
            }

            // Save the recalculated values
            $criterion->save();

            return 'Row updated successfully!';
        }

        return 'Row not found!';
    }


    private static function updatePercentageFields($category)
    {
        // Update all percentage fields in this category
        $percentageCriteria = Criterion::where('category', $category)
            ->where('pilihan_jawaban', '%')
            ->get();

        foreach ($percentageCriteria as $criterion) {
            $automatedValues = self::automatePercentageJawaban($criterion);

            // Update jawaban_unit and jawaban_tpi
            $criterion->jawaban_unit = $automatedValues['jawaban_unit'];
            $criterion->jawaban_tpi = $automatedValues['jawaban_tpi'];

            // Update nilai_unit and nilai_tpi based on the calculated jawaban
            if ($criterion->jawaban_unit !== null) {
                $criterion->nilai_unit = $criterion->jawaban_unit > 1 ? 1 : $criterion->jawaban_unit; // Cap at 1
            }
            if ($criterion->jawaban_tpi !== null) {
                $criterion->nilai_tpi = $criterion->jawaban_tpi > 1 ? 1 : $criterion->jawaban_tpi; // Cap at 1
            }

            $criterion->save();
        }
    }

    private static function automatePercentageJawaban($criterion)
    {
        
        // MANAJEMEN PERUBAHAN	
        // i.	Komitmen dalam perubahan
        
        if ($criterion->penilaian === 'a. Agen perubahan telah membuat perubahan yang konkret di Instansi (dalam 1 tahun)') {
            // Fetch related values
            $jumlahAgenUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Agen Perubahan')
                ->value('jawaban_unit') ?? 0;

            $jumlahPerubahanUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Perubahan yang dibuat')
                ->value('jawaban_unit') ?? 0;

            $jumlahAgenTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Agen Perubahan')
                ->value('jawaban_tpi') ?? 0;

            $jumlahPerubahanTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Perubahan yang dibuat')
                ->value('jawaban_tpi') ?? 0;

            // Calculate jawaban_unit
            $jawabanUnit = $jumlahAgenUnit > 0 ? round($jumlahPerubahanUnit / $jumlahAgenUnit, 2) : null;

            // Calculate jawaban_tpi
            $jawabanTpi = $jumlahAgenTpi > 0 ? round($jumlahPerubahanTpi / $jumlahAgenTpi, 2) : null;

            return [
                'jawaban_unit' => $jawabanUnit,
                'jawaban_tpi' => $jawabanTpi,
            ];
        }


        if ($criterion->penilaian === 'b. Perubahan yang dibuat Agen Perubahan telah terintegrasi dalam sistem manajemen') {
            // Fetch related values
            $jumlahPerubahanUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Perubahan yang dibuat Agen Perubahan')
                ->value('jawaban_unit') ?? 0;

            $integratedChangesUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Perubahan yang telah diintegrasikan dalam sistem manajemen')
                ->value('jawaban_unit') ?? 0;

            $jumlahPerubahanTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Perubahan yang dibuat Agen Perubahan')
                ->value('jawaban_tpi') ?? 0;

            $integratedChangesTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Perubahan yang telah diintegrasikan dalam sistem manajemen')
                ->value('jawaban_tpi') ?? 0;

            // Calculate jawaban_unit
            $jawabanUnit = $jumlahPerubahanUnit > 0 ? round($integratedChangesUnit / $jumlahPerubahanUnit, 2) : null;

            // Calculate jawaban_tpi
            $jawabanTpi = $jumlahPerubahanTpi > 0 ? round($integratedChangesTpi / $jumlahPerubahanTpi, 2) : null;

            return [
                'jawaban_unit' => $jawabanUnit,
                'jawaban_tpi' => $jawabanTpi,
            ];
        }
        

        // PENATAAN SISTEM MANAJEMEN SDM APARATUR
        // iii. Pelanggaran Disiplin Pegawai
        if ($criterion->penilaian === 'a. Penurunan pelanggaran disiplin pegawai') {
            // Fetch related values for Unit
            $pelanggaranSebelumnyaUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah pelanggaran tahun sebelumnya')
                ->value('jawaban_unit') ?? 0;

            $pelanggaranTahunIniUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah pelanggaran tahun ini')
                ->value('jawaban_unit') ?? 0;

            // Fetch related values for TPI
            $pelanggaranSebelumnyaTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah pelanggaran tahun sebelumnya')
                ->value('jawaban_tpi') ?? 0;

            $pelanggaranTahunIniTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah pelanggaran tahun ini')
                ->value('jawaban_tpi') ?? 0;

            // Initialize the result
            $result = [
                'jawaban_unit' => null,
                'jawaban_tpi' => null,
            ];

            // Calculate jawaban_unit if related Unit values are present
            if ($pelanggaranSebelumnyaUnit !== null && $pelanggaranTahunIniUnit !== null) {
                if ($pelanggaranSebelumnyaUnit == 0 && $pelanggaranTahunIniUnit == 0) {
                    $result['jawaban_unit'] = 1; // 100% if no violations in both years
                } else {
                    $percentageReductionUnit = ($pelanggaranSebelumnyaUnit > 0)
                        ? max(0, min(1, ($pelanggaranSebelumnyaUnit - $pelanggaranTahunIniUnit) / $pelanggaranSebelumnyaUnit))
                        : 0;
                    $result['jawaban_unit'] = round($percentageReductionUnit, 2);
                }
            }

            // Calculate jawaban_tpi if related TPI values are present
            if ($pelanggaranSebelumnyaTpi !== null && $pelanggaranTahunIniTpi !== null) {
                if ($pelanggaranSebelumnyaTpi == 0 && $pelanggaranTahunIniTpi == 0) {
                    $result['jawaban_tpi'] = 1; // 100% if no violations in both years
                } else {
                    $percentageReductionTpi = ($pelanggaranSebelumnyaTpi > 0)
                        ? max(0, min(1, ($pelanggaranSebelumnyaTpi - $pelanggaranTahunIniTpi) / $pelanggaranSebelumnyaTpi))
                        : 0;
                    $result['jawaban_tpi'] = round($percentageReductionTpi, 2);
                }
            }

            return $result;
        }


        // PENGUATAN AKUNTABILITAS	
        // i.	Meningkatnya capaian kinerja unit kerja
        
        if ($criterion->penilaian === 'a. Persentase Sasaran dengan capaian 100% atau lebih') {
            // Fetch related values
            $sasaranKinerja100Unit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Sasaran Kinerja yang tercapai 100% atau lebih')
                ->value('jawaban_unit') ?? 0;

            $sasaranKinerjaUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Sasaran Kinerja')
                ->value('jawaban_unit') ?? 0;

            $sasaranKinerja100Tpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Sasaran Kinerja yang tercapai 100% atau lebih')
                ->value('jawaban_tpi') ?? 0;

            $sasaranKinerjaTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Sasaran Kinerja')
                ->value('jawaban_tpi') ?? 0;

            // Calculate jawaban_unit
            $jawabanUnit = $sasaranKinerjaUnit > 0 ? round($sasaranKinerja100Unit / $sasaranKinerjaUnit, 2) : null;

            // Calculate jawaban_tpi
            $jawabanTpi = $sasaranKinerjaTpi > 0 ? round($sasaranKinerja100Tpi / $sasaranKinerjaTpi, 2) : null;

            return [
                'jawaban_unit' => $jawabanUnit,
                'jawaban_tpi' => $jawabanTpi,
            ];
        }


        // PENGUATAN PENGAWASAN
        // ii.	Penanganan Pengaduan Masyarakat

        // Add logic for "Persentase penanganan pengaduan masyarakat"
        if ($criterion->penilaian === 'a. Persentase penanganan pengaduan masyarakat') {
            // Fetch values for jawaban_unit
            $jumlahHarusDitindaklanjutiUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah pengaduan masyarakat yang harus ditindaklanjuti')
                ->value('jawaban_unit') ?? 0;
        
            $jumlahSedangDiprosesUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah pengaduan masyarakat yang sedang diproses')
                ->value('jawaban_unit') ?? 0;
        
            $jumlahSelesaiUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah pengaduan masyarakat yang selesai ditindaklanjuti')
                ->value('jawaban_unit') ?? 0;
        
            // Fetch values for jawaban_tpi
            $jumlahHarusDitindaklanjutiTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah pengaduan masyarakat yang harus ditindaklanjuti')
                ->value('jawaban_tpi') ?? 0;
        
            $jumlahSedangDiprosesTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah pengaduan masyarakat yang sedang diproses')
                ->value('jawaban_tpi') ?? 0;
        
            $jumlahSelesaiTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah pengaduan masyarakat yang selesai ditindaklanjuti')
                ->value('jawaban_tpi') ?? 0;
        
            // Calculate jawaban_unit
            $jawabanUnit = 0;
            if (($jumlahSedangDiprosesUnit == 0 && $jumlahSelesaiUnit == 0) || ($jumlahSedangDiprosesUnit === null && $jumlahSelesaiUnit === null)) {
                $jawabanUnit = 1; // Default to 100% if no complaints
            } elseif ($jumlahHarusDitindaklanjutiUnit > 0) {
                $jawabanUnit = $jumlahSelesaiUnit / $jumlahHarusDitindaklanjutiUnit;
            }
        
            // Calculate jawaban_tpi
            $jawabanTpi = 0;
            if (($jumlahSedangDiprosesTpi == 0 && $jumlahSelesaiTpi == 0) || ($jumlahSedangDiprosesTpi === null && $jumlahSelesaiTpi === null)) {
                $jawabanTpi = 1; // Default to 100% if no complaints
            } elseif ($jumlahHarusDitindaklanjutiTpi > 0) {
                $jawabanTpi = $jumlahSelesaiTpi / $jumlahHarusDitindaklanjutiTpi;
            }
        
            return [
                'jawaban_unit' => round($jawabanUnit, 2),
                'jawaban_tpi' => round($jawabanTpi, 2),
            ];
        }

        // iii.	Penyampaian Laporan Harta Kekayaan
        // a. Penyampaian Laporan Harta Kekayaan Pejabat Negara (LHKPN)

        if ($criterion->penilaian === 'a. Penyampaian Laporan Harta Kekayaan Pejabat Negara (LHKPN)') {
            // Fetch values for jawaban_unit
            $kepalaSatuanKerjaUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Kepala satuan kerja')
                ->value('jawaban_unit') ?? 0;
        
            $pejabatWajibLaporanUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Pejabat yang diwajibkan menyampaikan LHKPN')
                ->value('jawaban_unit') ?? 0;
        
            $lainnyaUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Lainnya')
                ->value('jawaban_unit') ?? 0;
        
            $sudahMelaporkanUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah yang sudah melaporkan LHKPN')
                ->value('jawaban_unit') ?? 0;
        
            // Fetch values for jawaban_tpi
            $kepalaSatuanKerjaTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Kepala satuan kerja')
                ->value('jawaban_tpi') ?? 0;
        
            $pejabatWajibLaporanTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Pejabat yang diwajibkan menyampaikan LHKPN')
                ->value('jawaban_tpi') ?? 0;
        
            $lainnyaTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Lainnya')
                ->value('jawaban_tpi') ?? 0;
        
            $sudahMelaporkanTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah yang sudah melaporkan LHKPN')
                ->value('jawaban_tpi') ?? 0;
        
            // Calculate "Jumlah yang harus melaporkan" (K175)
            $jumlahHarusMelaporkanUnit = $kepalaSatuanKerjaUnit + $pejabatWajibLaporanUnit + $lainnyaUnit;
            $jumlahHarusMelaporkanTpi = $kepalaSatuanKerjaTpi + $pejabatWajibLaporanTpi + $lainnyaTpi;
        
            // Save "Jumlah yang harus melaporkan" into database
            Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah yang harus melaporkan')
                ->update([
                    'jawaban_unit' => $jumlahHarusMelaporkanUnit,
                    'jawaban_tpi' => $jumlahHarusMelaporkanTpi,
                ]);
        
            // Calculate "Persentase penyampaian LHKPN" (K179 / K175)
            $persentaseUnit = $jumlahHarusMelaporkanUnit > 0
                ? $sudahMelaporkanUnit / $jumlahHarusMelaporkanUnit
                : 0;
        
            $persentaseTpi = $jumlahHarusMelaporkanTpi > 0
                ? $sudahMelaporkanTpi / $jumlahHarusMelaporkanTpi
                : 0;
        
            // Return calculated percentages
            return [
                'jawaban_unit' => round($persentaseUnit, 2),
                'jawaban_tpi' => round($persentaseTpi, 2),
            ];
        }

        // b. Penyampaian Laporan Harta Kekayaan Non LHKPN
        
        if ($criterion->penilaian === 'b. Penyampaian Laporan Harta Kekayaan Non LHKPN') {
            // Fetch values for jawaban_unit
            $administratorUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Pejabat administrator (eselon III)')
                ->value('jawaban_unit') ?? 0;
        
            $pengawasUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Pejabat Pengawas (eselon IV)')
                ->value('jawaban_unit') ?? 0;
        
            $fungsionalUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Fungsional dan Pelaksana')
                ->value('jawaban_unit') ?? 0;
        
            $sudahMelaporkanUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah yang sudah melaporkan Non LHKPN')
                ->value('jawaban_unit') ?? 0;
        
            // Fetch values for jawaban_tpi
            $administratorTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Pejabat administrator (eselon III)')
                ->value('jawaban_tpi') ?? 0;
        
            $pengawasTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Pejabat Pengawas (eselon IV)')
                ->value('jawaban_tpi') ?? 0;
        
            $fungsionalTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah Fungsional dan Pelaksana')
                ->value('jawaban_tpi') ?? 0;
        
            $sudahMelaporkanTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah yang sudah melaporkan Non LHKPN')
                ->value('jawaban_tpi') ?? 0;
        
            // Calculate "Jumlah yang harus melaporkan (tidak wajib LHKPN)"
            $jumlahHarusMelaporkanUnit = $administratorUnit + $pengawasUnit + $fungsionalUnit;
            $jumlahHarusMelaporkanTpi = $administratorTpi + $pengawasTpi + $fungsionalTpi;
        
            // Update "Jumlah yang harus melaporkan (tidak wajib LHKPN)" row
            Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah yang harus melaporkan (tidak wajib LHKPN)')
                ->update([
                    'jawaban_unit' => $jumlahHarusMelaporkanUnit,
                    'jawaban_tpi' => $jumlahHarusMelaporkanTpi,
                ]);
        
            // Calculate "Persentase penyampaian Non LHKPN"
            $persentaseUnit = $jumlahHarusMelaporkanUnit > 0
                ? $sudahMelaporkanUnit / $jumlahHarusMelaporkanUnit
                : 0;
        
            $persentaseTpi = $jumlahHarusMelaporkanTpi > 0
                ? $sudahMelaporkanTpi / $jumlahHarusMelaporkanTpi
                : 0;
        
            return [
                'jawaban_unit' => round($persentaseUnit, 2),
                'jawaban_tpi' => round($persentaseTpi, 2),
            ];
        }



        // PENINGKATAN KUALITAS PELAYANAN PUBLIK

        // b. Upaya dan/atau inovasi pada perijinan/pelayanan telah dipermudah
        
    
        if ($criterion->penilaian === 'Upaya dan/atau inovasi pada perijinan/pelayanan telah dipermudah') {
            // Fetch related values
            $perijinanDipermudahUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah perijinan/pelayanan yang telah dipermudah')
                ->value('jawaban_unit') ?? 0;

            $perijinanTerdaftarUnit = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah perijinan/pelayanan yang terdata/terdaftar')
                ->value('jawaban_unit') ?? 0;

            $perijinanDipermudahTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah perijinan/pelayanan yang telah dipermudah')
                ->value('jawaban_tpi') ?? 0;

            $perijinanTerdaftarTpi = (float) Criterion::where('category', $criterion->category)
                ->where('penilaian', '- Jumlah perijinan/pelayanan yang terdata/terdaftar')
                ->value('jawaban_tpi') ?? 0;

            // Calculate jawaban_unit
            $jawabanUnit = $perijinanTerdaftarUnit > 0 ? round($perijinanDipermudahUnit / $perijinanTerdaftarUnit, 2) : null;

            // Calculate jawaban_tpi
            $jawabanTpi = $perijinanTerdaftarTpi > 0 ? round($perijinanDipermudahTpi / $perijinanTerdaftarTpi, 2) : null;

            return [
                'jawaban_unit' => $jawabanUnit,
                'jawaban_tpi' => $jawabanTpi,
            ];
        }
        
        

        
        


        return [
            'jawaban_unit' => null, // Default null for unsupported cases
            'jawaban_tpi' => null,
        ];
    }
}
