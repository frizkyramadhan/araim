# Analisa pengecekan aset karyawan

CRUD perbekalan tetap di ARAIM. Arka Hero hanya menampilkan, di detail karyawan, perbekalan yang sedang dipegang orang itu.

## Istilah

Lihat [glosarium](/cursor/stores/self/docs/glossary.md).

- **Perbekalan** adalah barang yang dipegang karyawan. **Aset** adalah jenis di katalog.
- **Ikatan karyawan** mengikuti orang Arka Hero, bukan NIK dan bukan data administrasi.
- **Riwayat perbekalan** adalah urutan pemegang, termasuk pindah ke karyawan lain.
- **Mutasi** di Arka Hero tetap berarti pindah proyek.

## Yang dibangun

**ARAIM, menu karyawan.** Pengguna mencari karyawan Hero lewat nama atau NIK saat ini. Yang disimpan adalah employee id Hero. NIK yang berubah, termasuk karyawan kontrak yang ganti NIK, tidak memutus ikatan.

**Arka Hero, detail karyawan.** Saat halaman dibuka, Hero membaca ARAIM. Tidak ada salinan perbekalan di Hero. Daftar hanya perbekalan yang sedang dipegang. Kalau barang itu pernah pindah, detailnya menampilkan riwayat pemegang. Barang yang sudah pindah darinya tidak tetap di daftarnya. Karyawan ARAIM yang belum diikat tidak muncul di detail siapa pun.

## Yang tidak dibangun

- Form verifikasi atau opname. Belum ada di ARAIM, dan tidak diminta.
- Perubahan data perbekalan dari Hero.
- Menyamakan NIK ARAIM setiap kali NIK Hero berubah.

## Kondisi kode ARAIM.v2

Padanan istilah analisa di kode ini:

| Analisa | Di ARAIM.v2 |
| --- | --- |
| Perbekalan | Baris `inventories` |
| Pemegang | `inventories.employee_id` → `employees.id` |
| Nomor inventaris | `inventories.inventory_no` |
| Aset (katalog) | `assets` |
| Sedang dipegang | `transfer_status = Available` |
| Baris lama yang sudah pindah | `transfer_status = Mutated` |

- `employees` menyimpan `nik` integer, `id` lokal, dan `hero_employee_id` (UUID Hero, boleh kosong, unik).
- Pindah pemegang ada di `InventoryController::transferProcess`. Baris baru menyalin `inventory_no` dan `transfer_status = Available`. Kalau kuantitas lama habis, baris lama menjadi `Mutated`. Kalau masih ada sisa, baris lama tetap `Available` dengan kuantitas yang berkurang, jadi satu `inventory_no` bisa dipegang dua orang untuk bagian yang berbeda.
- Riwayat pemegang adalah kumpulan baris dengan `inventory_no` yang sama, urut `id`. Tidak ada tabel riwayat terpisah.
- `GET /api/hero/employees/{heroEmployeeId}/inventories` memakai `X-API-Key` (`API_KEY`). Hero memanggil jalur ini. `holdings` membalas isi yang sama. Tidak ada endpoint tulis.
- Detail karyawan Hero belum menampilkan perbekalan. NIK Hero hidup di administrasi (`active_nik`). Employee id Hero yang stabil.

## Langkah kerja di ARAIM.v2

Diterapkan 2026-11-16. CRUD perbekalan tidak berubah. Yang ditambah: ikatan orang, ikat data lama sekali, dan API baca untuk Hero.

### 1. Simpan employee id Hero

Migrasi `employees.hero_employee_id`: string 36 karakter, boleh kosong, unik. Ini UUID `employees.id` di Hero, bukan id administrasi dan bukan NIK.

`EmployeeController::store` dan `update` menyimpan nilai itu dari pilihan pengguna. `employees.nik` tidak diisi ulang dari Hero.

### 2. Pencarian orang di menu karyawan

Di `employees/create` dan `employees/edit`, pengguna mencari nama atau NIK Hero saat ini. ARAIM memanggil API baca Hero dari server, lalu menyimpan employee id yang dipilih.

Daftar karyawan menampilkan apakah baris itu sudah terikat. Pengguna bisa mengganti atau melepas ikatan dari form yang sama.

Butuh URL dan token Hero di `.env` (`HERO_API_URL`, `HERO_API_TOKEN`). Hero saat ini punya pencarian by NIK. Pencarian nama memakai endpoint Hero yang mengembalikan employee id, nama, dan NIK aktif. Sebelum endpoint nama itu ada, ikat manual lewat NIK tetap bisa jalan.

### 3. Ikat baris lama sekali

Perintah artisan, misalnya `employees:bind-hero`. Untuk `employees` yang `hero_employee_id`-nya masih kosong, cocokkan `employees.nik` dengan `active_nik` Hero sebagai teks.

Hanya ikat kalau tepat satu orang Hero yang cocok. Nol atau lebih dari satu dibiarkan kosong dan dicetak di laporan, lalu diikat manual lewat form langkah 2. Baris yang sudah terisi tidak ditimpa. NIK ARAIM tidak diubah.

### 4. API baca untuk Hero

`GET /api/hero/employees/{heroEmployeeId}/inventories`, middleware `api.key`. `holdings` tetap membalas daftar yang sama.

- Kalau tidak ada karyawan ARAIM dengan id itu, balas daftar kosong.
- Isi daftar hanya baris `inventories` milik karyawan itu dengan `transfer_status = Available` dan `is_active = 1`.
- Setiap barang menyertakan riwayat: semua baris `inventory_no` yang sama, urut `id`, beserta nama pemegang, NIK ARAIM, `hero_employee_id`, tanggal, kuantitas, dan `transfer_status`.
- Tidak ada endpoint tulis. Hero tidak mengubah perbekalan.

### Di luar langkah ini

- Form verifikasi atau opname.
- Perubahan perbekalan dari Hero.
- Menyamakan `employees.nik` saat NIK Hero berubah.
- Mengubah cara `transferProcess` memecah kuantitas.

Keputusan ikatannya: employee id Hero, bukan NIK. Tercatat di `docs/decisions.md`.
