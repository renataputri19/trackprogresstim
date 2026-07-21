<?php

/*
|--------------------------------------------------------------------------
| OMEGA — Outstanding Member of Great ASN
|--------------------------------------------------------------------------
|
| Tahap 1 (Penjaringan Awal) OMEGA di BPS Kota Batam.
|
| Setiap pegawai memilih satu rekan kerja TERBAIK di setiap tim yang ia
| ikuti. Data keanggotaan tim di bawah ini di-generate dari berkas
| "Tim 2026.xlsx". Setiap pegawai adalah anggota dari sebuah tim jika sel
| tim tersebut tidak kosong (baik bernilai "1" maupun label peran).
|
| Suara (votes) disimpan di tabel `omega_votes`. Roster ini adalah sumber
| kebenaran untuk keanggotaan tim — cukup ubah array di bawah bila susunan
| tim berubah untuk triwulan berikutnya.
|
*/

return [

    /*
     | Periode pemilihan aktif dideteksi OTOMATIS dari tanggal hari ini.
     |
     | Pemilihan sebuah triwulan dibuka pada bulan setelah triwulan tersebut
     | berakhir dan berlangsung selama satu triwulan penuh:
     |   TW I  (Jan–Mar)  -> voting dibuka April
     |   TW II (Apr–Jun)  -> voting dibuka Juli
     |   TW III(Jul–Sep)  -> voting dibuka Oktober
     |   TW IV (Okt–Des)  -> voting dibuka Januari (tahun berikutnya)
     |
     | Jadi pada bulan Juli, yang dipilih adalah TW II. Saat Oktober tiba,
     | TW III otomatis terbuka dan TW II otomatis terkunci.
     |
     | Isi `force_period` (mis. '2026-Q2') hanya bila ingin menimpa deteksi
     | otomatis (berguna untuk uji coba). Biarkan null untuk mode otomatis.
     */
    'force_period' => null,

    /*
     | Daftar tim/bidang di BPS Kota Batam.
     */
    'teams' => [
        'SUBBAGIAN UMUM',
        'SOSIAL',
        'IT',
        'NERWILIS',
        'HUMAS',
        'RB',
        'PROTOKOLER',
        'SEKTORAL',
        'DISEMINASI',
        'MANAJEMEN MITRA',
        'SENSUS EKONOMI',
        'PRODUKSI',
        'DISTRIBUSI',
    ],

    /*
     | Keanggotaan tim per pegawai: nama => [daftar tim].
     | Pegawai dengan array kosong tidak tergabung di tim manapun sehingga
     | tidak dapat memilih dan tidak menjadi kandidat.
     */
    'members' => [
        'Adi Darmanto, S.E.' => ['SUBBAGIAN UMUM', 'IT', 'RB', 'SENSUS EKONOMI'],
        'Adlina Khairunnisa, SST, M.Si.' => ['SUBBAGIAN UMUM', 'NERWILIS', 'RB', 'SEKTORAL', 'SENSUS EKONOMI'],
        'Anditia Pratiwi, S.Tr.Stat' => ['NERWILIS', 'RB', 'DISEMINASI', 'SENSUS EKONOMI'],
        'Arief Tirtana' => ['SOSIAL', 'HUMAS', 'RB', 'PROTOKOLER', 'SENSUS EKONOMI'],
        'Catur Ariadi Wahyono' => ['RB', 'SENSUS EKONOMI', 'PRODUKSI'],
        'Cuan Wanti Gultom, A.Md' => ['SUBBAGIAN UMUM', 'HUMAS', 'RB', 'SENSUS EKONOMI'],
        'Debora Sinaga, S.E.' => ['HUMAS', 'RB', 'SENSUS EKONOMI', 'DISTRIBUSI'],
        'Dekha Dwi Harianja, SST' => ['SOSIAL', 'RB', 'SENSUS EKONOMI'],
        'Desmaini, S.Si' => ['RB', 'PROTOKOLER', 'SENSUS EKONOMI', 'PRODUKSI'],
        'Dewi Feronika, A.Md.' => ['SUBBAGIAN UMUM', 'RB', 'MANAJEMEN MITRA', 'SENSUS EKONOMI'],
        'Eko Aprianto, SST, M.T.I.' => [],
        'Ema Aprilia Fitriani, SST' => ['SUBBAGIAN UMUM', 'RB', 'SENSUS EKONOMI'],
        'Evawane Fahma Kusumawardani, S.Tr.Stat' => ['SOSIAL', 'RB', 'SEKTORAL', 'SENSUS EKONOMI'],
        'Febry Utami, S.Tr.Stat.' => [],
        'Florentz Magdalena, SST, M.Sc' => ['SOSIAL', 'RB', 'SEKTORAL', 'DISEMINASI', 'SENSUS EKONOMI'],
        'Gideon Marpaung, S.Tr.Stat.' => ['RB', 'PROTOKOLER', 'SENSUS EKONOMI', 'PRODUKSI'],
        'Hanifah Ayu SST' => ['RB', 'SENSUS EKONOMI', 'DISTRIBUSI'],
        'Hardoni' => ['IT', 'RB', 'DISEMINASI', 'MANAJEMEN MITRA', 'SENSUS EKONOMI'],
        'Hogan Da Costa Sinurat S.M.' => ['SUBBAGIAN UMUM', 'RB', 'SENSUS EKONOMI'],
        'Ignatius Aprianto A S, S.Tr.Stat' => ['RB', 'PROTOKOLER', 'SENSUS EKONOMI', 'PRODUKSI'],
        'Intan Nur Arifah, A.Md.Ak.' => ['SUBBAGIAN UMUM', 'RB', 'SENSUS EKONOMI'],
        'Ivana Yoselin Purba Siboro, S.Tr.Stat.' => ['NERWILIS', 'HUMAS', 'RB', 'SEKTORAL', 'SENSUS EKONOMI'],
        'M. Fadel Pahleva Yacoeb, SST' => [],
        'Maria Lisbetaria Nababan, SST' => ['NERWILIS', 'RB', 'DISEMINASI', 'SENSUS EKONOMI'],
        'Maulidya Fan Ghul Udzan Utami, S.Tr.Stat' => ['IT', 'HUMAS', 'RB', 'SEKTORAL', 'DISEMINASI', 'SENSUS EKONOMI'],
        'Metha Arfiandty, A.Md' => ['HUMAS', 'RB', 'SENSUS EKONOMI', 'PRODUKSI'],
        'Moch Yailani' => ['RB', 'SENSUS EKONOMI', 'PRODUKSI'],
        'Moon Bangun Simamora, S.E.' => ['IT', 'RB', 'DISEMINASI', 'SENSUS EKONOMI'],
        'Nina Martini' => ['SUBBAGIAN UMUM', 'RB', 'SENSUS EKONOMI'],
        'Nurmawiya, S.Tr.Stat.' => [],
        'Pretty Melati Pardede S.Tr.Stat.' => ['HUMAS', 'RB', 'SENSUS EKONOMI', 'PRODUKSI'],
        'Radhitya Noor Adhyaksani, S.Tr.Stat.' => ['HUMAS', 'RB', 'SEKTORAL', 'SENSUS EKONOMI', 'DISTRIBUSI'],
        'Ratih Nurhabibah, S.Tr.Stat.' => ['RB', 'SEKTORAL', 'SENSUS EKONOMI', 'DISTRIBUSI'],
        'Renata Putri Henessa, S.Tr.Stat.' => ['IT', 'HUMAS', 'RB', 'SEKTORAL', 'SENSUS EKONOMI'],
        'Reno Fitria, SST' => ['SOSIAL', 'RB', 'PROTOKOLER', 'MANAJEMEN MITRA', 'SENSUS EKONOMI'],
        'Retza Bahtiar Anugrah, S.Si.' => ['SUBBAGIAN UMUM', 'IT', 'RB', 'DISEMINASI', 'SENSUS EKONOMI', 'DISTRIBUSI'],
        'Ridha Amalia Hakim, S.Si., M.M.' => ['RB', 'DISEMINASI', 'SENSUS EKONOMI', 'PRODUKSI'],
        'Sri Desmiwati, S.ST' => ['RB', 'DISEMINASI', 'SENSUS EKONOMI', 'DISTRIBUSI'],
        'Sri Fahrina, A.Md.Stat' => ['SUBBAGIAN UMUM', 'RB', 'SENSUS EKONOMI'],
        'Tania Viona Sirait, S.Tr.Stat.' => ['RB', 'SENSUS EKONOMI', 'DISTRIBUSI'],
        'Weldy Melsa Saputra' => ['SUBBAGIAN UMUM', 'RB', 'PROTOKOLER', 'DISEMINASI', 'SENSUS EKONOMI'],
    ],

    /*
     | Pimpinan yang boleh memilih di SEMUA bidang, namun BUKAN kandidat
     | (tidak muncul di daftar pilihan tim manapun). Nilai 'all' berarti semua
     | tim; bisa juga diisi array tim tertentu.
     |
     | Kepala BPS Kota Batam memilih Pegawai Terbaik di tiap bidang, lalu
     | menetapkan pemenang akhir pada Tahap 3.
     */
    'leaders' => [
        'Devi Indriastuti, SST, M.Si.' => 'all', // Kepala BPS Kota Batam
        'Eko Aprianto, SST, M.T.I.' => 'all',     // Kepala
    ],

    /*
     | Siapa yang boleh membuka halaman Rekapitulasi Suara (/omega/results).
     | Hanya email di daftar ini yang dapat mengakses — bukan semua admin.
     | Tambahkan email lain di sini bila perlu.
     */
    'results_access' => [
        'yoselin.purba@bps.go.id',
    ],

];
